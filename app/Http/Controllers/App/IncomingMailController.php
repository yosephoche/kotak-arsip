<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\StorageSub, App\User, App\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
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
				),
				array(
					'$lookup' => array(
						'from' => 'storage_sub',
						'localField' => 'storagesub',
						'foreignField' =>  '_id',
						'as' => 'storagesub'
					)
				),
				array(
					'$lookup' => array(
						'from' => 'storage',
						'localField' => 'storage',
						'foreignField' =>  '_id',
						'as' => 'storage'
					)
				),
				array(
					'$project' => array(
						'fulltext' => 0,
						'note' => 0,
						'updated_at' => 0,
						'created_at' => 0
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
				),
				array(
					'$lookup' => array(
						'from' => 'storage_sub',
						'localField' => 'storagesub',
						'foreignField' =>  '_id',
						'as' => 'storagesub'
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
		//Delete All file in the Directory
		$file = public_path('assets/tesseract'.'/'.Auth::user()->_id); 
		if (file_exists($file)) {
			array_map('unlink', glob("$file/*.*"));
		}

		$image = $r->file('image');
		$ext = $image->getClientOriginalExtension();
		$nm_file = "0.".$ext;
		$destination = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$upload = $image->move($destination, $nm_file);

		return redirect()->route('incoming_mail_create');
	}

	public function uploadAjax(Request $r)
	{
		// Image
		$dir = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$files = scandir($dir, 1);

		$string = $files[1];
		$firstCharacter = $string[0];

		//String To Integer
		$strtoint = (int)$firstCharacter + 1;

		$countfiles = count($r->file);

		$file = $r->file('file');

		//Make Name File
		$filename = [];
		for ($i= $strtoint; $i < $strtoint + $countfiles ; $i++) { 
			$nm_file = $i;
			$filename[] = $nm_file;
		}

		//Move File
		for ($i=0; $i < $countfiles ; $i++) { 
			// Image Upload Process
			$ext = $file[$i]->getClientOriginalExtension();
			$nm_file = $filename[$i].".".$ext;
			$destination = public_path('assets/tesseract'.'/'.Auth::user()->_id);
			$upload = $file[$i]->move($destination, $nm_file);
		}
	}

	public function removeAjax(Request $r)
	{
		unlink(public_path('assets/tesseract').'/'.Auth::user()->_id.'/'.$r->image);
	}

	public function dropdownAjax()
	{
    	$storage_id = Input::get('storage_id');

    	$kabupaten = StorageSub::where('id_storage', '=', GlobalClass::generateMongoObjectId($storage_id))->orderBy('name')->get();
        return response()->json($kabupaten);
	}

	public function create()
	{
		// Image
		$dir = public_path('assets/tesseract'.'/'.Auth::user()->_id);
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
		$image = public_path('assets/tesseract'.'/'.Auth::user()->_id.'/'.$images[0]);
		$result = public_path('assets/tesseract'.'/'.Auth::user()->_id.'/'.'out');
		$open = public_path('assets/tesseract'.'/'.Auth::user()->_id.'/'.'out.txt');

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

		//Storage
		$data['storage'] = Storage::where('id_company', Auth::user()->id_company)->orderBy('name')->get();

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

		$surat = new Archieve;
		$surat->id_user = Auth::user()->_id;
		$surat->id_company = Auth::user()->id_company;
		$surat->type = "incoming_mail";
		$surat->from = $r->from;
		$surat->reference_number = $r->reference_number;
		$surat->subject = $r->subject;
		$surat->date = GlobalClass::generateIsoDate($r->date);
		$surat->storage = GlobalClass::generateMongoObjectId($r->storage);
		if ($r->storagesub != '') {
			$surat->storagesub = GlobalClass::generateMongoObjectId($r->storagesub);
		}
		$surat->note = $r->note;
		$surat->fulltext = $r->fulltext;

		//Check Image From Tesseract
		$dir = public_path('assets/tesseract'.'/'.Auth::user()->_id);
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
			rename(public_path('assets/tesseract').'/'.Auth::user()->_id.'/'.$img, public_path('assets/app/img/incoming_mail').'/'.$nm_file);
			$files[] = $nm_file;
		}

		$surat->files = $files;
		$surat->save();

		Session::flash('message', "Berhasil menambahkan surat masuk baru");
		return redirect()->route('incoming_mail');
	}

	public function edit($id)
	{	
		$data['archieve'] = Archieve::find($id);

		return view('app.incoming_mail.edit', $data);
	}

	public function update($id, Request $r)
	{
		$this->validate($r, [
			'from'				=> 'required',
			'reference_number'	=> 'required',
			'subject'			=> 'required',
			'date'				=> 'required',
			'storage'			=> 'required',
		]);

		$surat = Archieve::find($id);
		$surat->id_user = Auth::user()->_id;
		$surat->id_company = Auth::user()->id_company;
		$surat->type = "incoming_mail";
		$surat->from = $r->from;
		$surat->reference_number = $r->reference_number;
		$surat->subject = $r->subject;
		$surat->date = GlobalClass::generateIsoDate($r->date);
		$surat->storage = $r->storage;
		$surat->note = $r->note;
		$surat->save();

		Session::flash('message', "Berhasil menyimpan pembaruan");
		return redirect()->route('incoming_mail');
	}

	public function delete(Request $r)
	{
		$archieve = Archieve::where('_id', $r->id)->delete();

		return redirect()->back();
	}
}
