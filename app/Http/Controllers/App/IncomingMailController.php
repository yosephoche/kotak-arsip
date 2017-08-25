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
		})->where('type', 'incoming_mail')->where('id_company', Auth::user()->id_company);

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
		$nm_file = "image.".$ext;
		$destination = public_path('assets/tesseract/image');
		$upload = $image->move($destination, $nm_file);

		return redirect()->route('incoming_mail_create');
	}

	public function uploadAjax(Request $r)
	{
		if ( $r->hasFile('files') ) {
			 // Upload Image
			$destination = public_path('assets/tesseract/image');
			$files = GlobalClass::Upload($r->file('files'), $destination, 200);
		}
		return redirect()->back();
	}

	public function create()
	{
		$image = '"E:\WEB Developer\MediaSakti\KotakArsip_MongoDB\public\assets\tesseract\image\image.jpg"';
		$result = '"E:\WEB Developer\MediaSakti\KotakArsip_MongoDB\public\assets\tesseract\out"';
		$open = "E:/WEB Developer/MediaSakti/KotakArsip_MongoDB/public/assets/tesseract/out.txt";

		$output = exec("tesseract $image $result -l ind+eng");

		//From
		$searchfrom = array("LEMBAGA", "KERUKUNAN", "PT");
		$myfile = fopen($open, "r") or die("Unable to open file!");
		while(!feof($myfile)) 
		{
			$buffer =  fgets($myfile);
			for ($i=0; $i < count($searchfrom) ; $i++) { 
				if(strpos($buffer, $searchfrom[$i]) !== FALSE) {
					$from = $buffer;
				}
			}
		}
		fclose($myfile);
		$data['from'] = $from;

		//Refrence_Number
		$searchnumber = array("Nomor :", "No. Surat", "No Surat");
		$myfile = fopen($open, "r") or die("Unable to open file!");
		while(!feof($myfile)) 
		{
			$buffer =  fgets($myfile);
			for ($i=0; $i < count($searchnumber) ; $i++) { 
				if(strpos($buffer, $searchnumber[$i]) !== FALSE)
					$reference_number = $buffer;
			}
		}
		fclose($myfile);
		$data['reference_number'] = explode(':',$reference_number, 2);

		//Subject
		$searchsubject = array("Perihal :", "Hal :");
		$myfile = fopen($open, "r") or die("Unable to open file!");
		while(!feof($myfile)) 
		{
			$buffer =  fgets($myfile);
			for ($i=0; $i < count($searchsubject) ; $i++) { 
				if(strpos($buffer, $searchsubject[$i]) !== FALSE) {
					$subject = $buffer;
				}
			}
		}
		fclose($myfile);
		$data['subject'] = explode(':',$subject, 2);

		$data['fulltext'] = file_get_contents($open);

		// Image
		$dir = public_path('assets/tesseract/image');
		$files = scandir($dir);

		$image = [];
		for ($i=0; $i < count($files); $i++) { 
			// Conditions for find image
			$ext = substr($files[$i], -3);
			if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png') {
				array_push($image, $files[$i]);
			}
		}

		$data['image'] = $image; 

		return view('app.incoming_mail.create', $data);
	}

	public function store(Request $r)
	{
		$this->validate($r, [
			'from'				=> 'required',
			'reference_number'	=> 'required',
			'subject'			=> 'required',
			'date'				=> 'required',
			'storage'			=> 'required'
		]);

		if ( $r->hasFile('files') ) {
			 // Upload Image
			$destination = public_path('assets/app/img/incoming_mail');
			$files = GlobalClass::Upload($r->file('files'), $destination, 200);
		}

		//Make ObjectId
		$share = GlobalClass::arrayObjectId($r->share);

		$surat = new Archieve;
		$surat->id_user = $r->id_user;
		$surat->id_company = $r->id_company;
		$surat->type = $r->type;
		$surat->from = $r->from;
		$surat->reference_number = $r->reference_number;
		$surat->subject = $r->subject;
		$surat->date = Carbon::createFromFormat('d/m/Y', $r->date)->format('d/m/Y');
		$surat->storage = $r->storage;
		$surat->share = $share;
		$surat->note = $r->note;
		$surat->files = $files;
		$surat->save();

		Session::flash('message', "Berhasil menambahkan surat masuk baru");
		return redirect()->back();
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

	public function delete($id)
	{
		Archieve::where('_id', $id)->delete();
		return redirect()->back();
	}

	public function restore(Request $r, $id)
	{
		Archieve::withTrashed()->where('_id', $id)->restore();
		return redirect()->back();
	}
}
