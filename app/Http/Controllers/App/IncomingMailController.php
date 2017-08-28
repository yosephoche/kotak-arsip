<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\StorageSub, App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;
use Carbon\Carbon;

class IncomingMailController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		return view('app.incoming_mail.index');
	}

	public function getData()
	{
		$archieve = Archieve::raw(function($collection){
			return $collection->aggregate(array(
				array(
					'$lookup' => array(
						'from'=>'users',
						'localField'=>'share',
						'foreignField'=>'_id',
						'as'=>'share'
					)
				)
			));
		})->where('type', 'incoming_mail')->where('id_company', Auth::user()->id_company)->where('deleted_at', '');

		$users = User::all();

		return response()->json([
			'incomingMail'  =>  $archieve,
			'users' => $users
		]);
	}

	public function detail($id)
	{
		$data['archieve'] = Archieve::find($id);

		return view('app.incoming_mail.detail', $data);
	}

	public function getDetail($id)
	{
		$archieve = Archieve::raw(function($collection){
			return $collection->aggregate(array(
				array(
					'$lookup' => array(
						'from'=>'users',
						'localField'=>'share',
						'foreignField'=>'_id',
						'as'=>'share'
					)
				)
			));
		})->where('_id', $id);

		$users = User::all();

		return response()->json([
			'incomingMail'  =>  $archieve,
			'users' => $users
		]);
	}

	public function upload(Request $r)
	{
		$image = $r->file('image');

		$ext = $image->getClientOriginalExtension();
		$nm_file = "111111.".$ext;
		$destination = public_path('assets/tesseract/image');
		$upload = $image->move($destination, $nm_file);

		return redirect()->route('incoming_mail_create');
	}

	public function uploadAjax(Request $r)
	{
		if ( $r->hasFile('file') ) {
			 // Upload Image
			$destination = public_path('assets/tesseract/image');
			$file = GlobalClass::Upload($r->file('file'), $destination, 200);
		}
	}

	public function removeAjax(Request $r)
	{
		unlink(public_path('assets/tesseract/image').'/'.$r->image);
	}

	public function create()
	{
		// Image
		$dir = public_path('assets/tesseract/image');
		$files = scandir($dir);

		$images = [];
		for ($i=0; $i < count($files); $i++) { 
			// Conditions for find images
			$ext = substr($files[$i], -3);
			if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png') {
				array_push($images, $files[$i]);
			}
		}
		$data['image'] = $images; 

		// --- OCR Code ---
		// Path Variables For Run OCR
		$image = public_path('assets\tesseract\image\111111.jpg');
		$result = public_path('assets\tesseract\out');
		$open = public_path('assets/tesseract/out.txt');

		// OCR Execution By Tesseract
		// For Windows
		$output = exec('tesseract "'.$image.'" "'.$result.'" -l ind+eng');

		// For Mac
		$output = exec('/usr/local/bin/tesseract "'.$image.'" "'.$result.'" -l ind+eng');

		// OCR From
		$data['from'] = GlobalClass::OCRKey($image, $result, $open, 'from');

		// OCR Refrence_Number
		$data['reference_number'] = GlobalClass::OCRKey($image, $result, $open, 'reference_number');

		// OCR Subject
		$data['subject'] = GlobalClass::OCRKey($image, $result, $open, 'subject');

		// OCR Fulltext
		$data['fulltext'] = GlobalClass::OCRKey($image, $result, $open, 'fulltext');

		// --- END OCR Code ---

		return view('app.incoming_mail.create', $data);
	}

	public function store(Request $r)
	{
		$this->validate($r, [
			'from'				=> 'required',
			'reference_number'	=> 'required',
			'subject'			=> 'required',
			'date'				=> 'required'
		]);

		//Check Image From Tesseract
		$dir = public_path('assets/tesseract/image');
		$file = scandir($dir);
		$images = [];
		for ($i=0; $i < count($file); $i++) { 
			// Conditions for find images
			$ext = substr($file[$i], -3);
			if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png') {
				array_push($images, $file[$i]);
			}
		}

		//Move Images from tesseract to incoming mail
		$files = [];
		foreach ($images as $img) {
			$rand = rand(111111,999999);
			$ext = substr($img, -3);
			if ($ext == 'jpg') {
				$nm_file = $rand.'.'.'jpg';
			} elseif ($ext == 'png') {
				$nm_file = $rand.'.'.'png';
			} else {
				$nm_file = $rand.'.'.'jpeg';
			}
			rename(public_path('assets/tesseract/image').'/'.$img, public_path('assets/app/img/incoming_mail').'/'.$nm_file);
			$files[] = $nm_file;
		}

		$surat = new Archieve;
		$surat->id_user = Auth::user()->_id;
		$surat->id_company = Auth::user()->id_company;
		$surat->type = "incoming_mail";
		$surat->from = $r->from;
		$surat->reference_number = $r->reference_number;
		$surat->subject = $r->subject;
		$surat->date = GlobalClass::generateIsoDate($r->date);
		$surat->storage = $r->storage;
		$surat->note = $r->note;
		$surat->fulltext = $r->fulltext;
		$surat->files = $files;
		$surat->save();

		Session::flash('message', "Berhasil menambahkan surat masuk baru");
		return redirect()->route('incoming_mail');
	}

	public function update(Request $r)
	{
		$this->validate($r, [
			'asal'	=> 'required',
			'nomor'	=> 'required',
			'perihal'	=> 'required',
			'tanggal'	=> 'required',
			'penyimpanan'	=> 'required',
		]);

		$surat = Archieve::find($r->_id);
		$surat->id_user = $r->id_user;
		$surat->id_company = $r->id_company;
		$surat->type = $r->type;
		$surat->asal = $r->asal;
		$surat->nomor = $r->nomor;
		$surat->perihal = $r->perihal;
		$surat->tanggal = Carbon::createFromFormat('d/m/Y', $r->tanggal)->format('d/m/Y');
		$surat->penyimpanan = $r->penyimpanan;
		$surat->share = $r->share;
		$surat->keterangan = $r->keterangan;
		$surat->save();

		Session::flash('message', "Berhasil menyimpan pembaruan");
		return redirect()->back();
	}

	public function delete(Request $r)
	{
		$archieve = Archieve::where('_id', $r->id)->delete();

		return redirect()->back();
	}
}
