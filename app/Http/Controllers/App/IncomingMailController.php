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
		$data['archieve'] = Archieve::where('type', 'incoming_mail')->where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->whereNull('deleted_at')->paginate(25);
		return view('app.incoming_mail.index', $data);
	}

	public function getData()
	{
		$archieve = Archieve::raw(function($collection){
			
			// Sort By
			$sortKey = 'created_at';
			if (@$_GET['sort'] == 'from') {
				$sortKey = 'from';
			} else if (@$_GET['sort'] == 'subject') {
				$sortKey = 'subject';
			}

			// Ascending or Descending
			$asc = -1;
			if (@$_GET['asc'] == 'true') {
				$asc = 1;
			}

			$page  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
			$limit = 25; // change in index too
			$skip  = ($page - 1) * $limit;

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
						'updated_at' => 0,
						'storage.created_at' => 0,
						'storage.updated_at' => 0,
						'storage.type' => 0,
						'storage.id_company' => 0,
						'storagesub.created_at' => 0,
						'storagesub.updated_at' => 0,
						'storagesub.id_storage' => 0,
						'share.position' => 0,
						'share.phone' => 0,
						'share.email_status' => 0,
						'share.status' => 0,
						'share.id_company' => 0,
						'share.remember_token' => 0,
						'share.password' => 0,
						'share.created_at' => 0,
						'share.updated_at' => 0
					)
				),
				array(
					'$sort' => array(
						$sortKey => $asc
					)
				),
				array(
					'$match' => array(
						'type' => 'incoming_mail',
						'id_user' => GlobalClass::generateMongoObjectId(Auth::user()->_id),
						'id_company' => Auth::user()->id_company,
						'deleted_at' => null,
						// 'share' => array(
						// 	'$elemMatch' => array(
						// 		'email' => Auth::user()->email
						// 	)
						// )
					)
				),
				array(
					'$skip' => $skip
				),
				array(
					'$limit' => $limit
				)
			));
		});

		$users = User::where('id_company', Auth::user()->id_company)->get();

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
					'$unwind' => '$share'
				),
				array(
					'$lookup' => array(
						'from'=>'users',
						'localField'=>'share._id',
						'foreignField'=>'_id',
						'as'=>'share.user'
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
						'from' => 1,
						'reference_number' => 1,
						'date' => 1,
						'subject' => 1,
						'share' => 1,
						'storagesub._id' => 1,
						'storagesub.name' => 1,
						'storage._id' => 1,
						'storage.name' => 1,
						'files' => 1,
					)
				)
			));
		})->where('_id', $id);

		$users = User::where('id_company', Auth::user()->id_company)->get();

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

		// Find First Image
		$dir = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$files = scandir($dir);

		$check = substr($files[2], -3);
		if ($check == 'pdf') {
			return redirect()->route('incoming_mail_create');
		} else {
			$images = [];
			for ($i=0; $i < count($files); $i++) { 
				// Conditions for find images
				$ext = substr($files[$i], -3);
				if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png') {
					array_push($images, $files[$i]);
				}
			}

			// --- OCR Code ---
			// Path Variables For Run OCR
			$image = public_path('assets/tesseract'.'/'.Auth::user()->_id.'/'.$images[0]);
			$result = public_path('assets/tesseract'.'/'.Auth::user()->_id.'/'.'out');

			// OCR Execution By Tesseract
			// For Windows
			$output = exec('tesseract "'.$image.'" "'.$result.'" -l ind+eng');

			// For Mac
			$output = exec('/usr/local/bin/tesseract "'.$image.'" "'.$result.'" -l ind+eng');

			return redirect()->route('incoming_mail_create');
		}
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

	public function replaceAjax(Request $r)
	{
		$file = $r->file('file');

		// Find First Image
		$dir = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$files = scandir($dir);

		//Delete First Image
		unlink(public_path('assets/tesseract'.'/'.Auth::user()->_id.'/'.$files[2]));

		// Image Upload Process
		$ext = $file->getClientOriginalExtension();
		$nm_file = "0.".$ext;
		$destination = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$upload = $file->move($destination, $nm_file);

		$check = substr($files[2], -3);

		if ($check != 'pdf') {
			$image = public_path('assets/tesseract'.'/'.Auth::user()->_id.'/'.$files[2]);
			$result = public_path('assets/tesseract'.'/'.Auth::user()->_id.'/'.'out');

			// OCR Execution By Tesseract
			// For Windows
			$output = exec('tesseract "'.$image.'" "'.$result.'" -l ind+eng');

			// For Mac
			$output = exec('/usr/local/bin/tesseract "'.$image.'" "'.$result.'" -l ind+eng');
		}
		
		return redirect()->back();
	}

	public function replaceEdit(Request $r)
	{
		$file = $r->file('file');

		// Image Upload Process
		$ext = $file->getClientOriginalExtension();
		$nm_file = "0.".$ext;
		$destination = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$upload = $file->move($destination, $nm_file);

		return redirect()->back();
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
			if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png' || $ext == 'pdf') {
				array_push($images, $files[$i]);
			}
		}
		$data['image'] = $images; 

		//Check file
		$check = substr($files[2], -3);

		if ($check != 'pdf') {
			// Path Variables For OCR
			$image = public_path('assets/tesseract'.'/'.Auth::user()->_id.'/'.$images[0]);
			$result = public_path('assets/tesseract'.'/'.Auth::user()->_id.'/'.'out');
			$open = public_path('assets/tesseract'.'/'.Auth::user()->_id.'/'.'out.txt');

			// OCR From
			$data['from'] = GlobalClass::OCRKey($image, $result, $open, 'from');

			// OCR Refrence_Number
			$data['reference_number'] = GlobalClass::OCRKey($image, $result, $open, 'reference_number');

			// OCR Subject
			$data['subject'] = GlobalClass::OCRKey($image, $result, $open, 'subject');

			// OCR Fulltext
			$data['fulltext'] = GlobalClass::OCRKey($image, $result, $open, 'fulltext');

			// --- END OCR Code ---
		} else {
			$data['fulltext'] = "";
		}	

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

		$date = Carbon::createFromFormat('d/m/Y', $r->date);

		$surat = new Archieve;
		$surat->id_user = GlobalClass::generateMongoObjectId(Auth::user()->_id);
		$surat->id_company = Auth::user()->id_company;
		$surat->type = "incoming_mail";
		$surat->from = $r->from;
		$surat->reference_number = $r->reference_number;
		$surat->subject = $r->subject;
		$surat->date = GlobalClass::generateIsoDate($date);
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
			if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png' || $ext == 'pdf') {
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
			} elseif ($ext == 'pdf'){
				$nm_file = $rand.'.'.'pdf';
			} else {
				$nm_file = $rand.'.'.'jpeg';
			}
			rename(public_path('assets/tesseract').'/'.Auth::user()->_id.'/'.$img, public_path('assets/app/img/incoming_mail').'/'.$nm_file);
			$files[] = $nm_file;
		}

		$surat->files = $files;
		$surat->save();

		$r->session()->flash('success', 'Surat masuk baru berhasil ditambahkan');

		return redirect()->route('incoming_mail');
	}

	public function move($id)
	{
		//Incoming Mail
		$archieve = Archieve::find($id);
		$data['archieve'] = $archieve;

		//Delete All file in the Directory
		$file = public_path('assets/tesseract'.'/'.Auth::user()->_id); 
		if (file_exists($file)) {
			array_map('unlink', glob("$file/*.*"));
		} else {
			mkdir(public_path('assets/tesseract'.'/'.Auth::user()->_id));
		}

		$files = $archieve->files;

		//Make Name File
		$filename = [];
		for ($i=0; $i < count($files); $i++) { 
			$nm_file = $i;
			$filename[] = $nm_file;
		}

		//Get Extension
		$ext = [];
		for ($i=0; $i < count($files) ; $i++) { 
			$extension = (explode(".", $files[$i]));
			$ext[] = $extension[1];
		}

		//Copy File to tesseract
		for ($i=0; $i < count($files) ; $i++) { 
			copy(public_path('assets/app/img/incoming_mail').'/'.$files[$i], public_path('assets/tesseract').'/'.Auth::user()->_id.'/'.$filename[$i].'.'.$ext[$i]);
		}

		return redirect()->route('incoming_mail_edit', ['id' => $id]);
	}

	public function edit($id)
	{	
		//Incoming Mail
		$archieve = Archieve::find($id);

		//Check Id Archieve
		if ($archieve == false) {
			return redirect()->route('incoming_mail');
		}

		//Search For id_user Equation With id login
		if ($archieve->id_user != GlobalClass::generateMongoObjectId(Auth::user()->_id)) {
			return redirect()->route('incoming_mail');
		}

		$data['archieve'] = $archieve;

		// Image
		$dir = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$files = scandir($dir);

		$images = [];
		for ($i=0; $i < count($files); $i++) { 
			// Conditions for find images
			$ext = substr($files[$i], -3);
			if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png' || $ext == 'pdf') {
				array_push($images, $files[$i]);
			}
		}
		$data['image'] = $images; 
		

		//Storage
		$data['storage'] = Storage::where('id_company', Auth::user()->id_company)->orderBy('name')->get();

		//Sub Storage
		$data['storagesub'] = StorageSub::orderBy('name')->get();

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

		$date = Carbon::createFromFormat('d/m/Y', $r->date);

		$surat = Archieve::find($id);
		$surat->id_user = GlobalClass::generateMongoObjectId(Auth::user()->_id);
		$surat->id_company = Auth::user()->id_company;
		$surat->type = "incoming_mail";
		$surat->from = $r->from;
		$surat->reference_number = $r->reference_number;
		$surat->subject = $r->subject;
		$surat->date = GlobalClass::generateIsoDate($date);
		$surat->storage = GlobalClass::generateMongoObjectId($r->storage);
		if ($r->storagesub != '') {
			$surat->storagesub = GlobalClass::generateMongoObjectId($r->storagesub);
		}
		$surat->note = $r->note;

		//Delete Old File
		$old = $surat->files;
		for ($i=0; $i < count($old) ; $i++) { 
			unlink(public_path('assets/app/img/incoming_mail').'/'.$old[$i]);
		}

		//Check Image From Tesseract
		$dir = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$file = scandir($dir);
		$images = [];
		for ($i=0; $i < count($file); $i++) { 
			// Conditions for find images
			$ext = substr($file[$i], -3);
			if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png' || $ext == 'pdf') {
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
			} elseif ($ext == 'pdf') {
				$nm_file = $rand.'.'.'pdf';
			} else {
				$nm_file = $rand.'.'.'jpeg';
			}
			rename(public_path('assets/tesseract').'/'.Auth::user()->_id.'/'.$img, public_path('assets/app/img/incoming_mail').'/'.$nm_file);
			$files[] = $nm_file;
		}

		$surat->files = $files;
		$surat->save();

		$r->session()->flash('success', 'Berhasil menyimpan pembaruan');

		return redirect()->route('incoming_mail');
	}

	public function disposition(Request $r)
	{
		$surat = Archieve::find($r->id);

		// $date = [];
		// for ($i=0; $i < count($r->date); $i++) { 
		// 	$date[$i] = Carbon::createFromFormat('d/m/Y', $r->date[$i]);
		// }

		$share = [];
		for ($i=0; $i < count($r->share) ; $i++) { 
			$share[] = [
				'_id' => GlobalClass::generateMongoObjectId($r->share[$i]),
				'message' => $r->message,
				'date' => '12345678'
			];
		}

		if ($r->share != null) {
			$surat->share = $share;
		} else {
			$surat->share = '';
		}
		$surat->save();

		$r->session()->flash('success', 'Surat masuk berhasil didisposisi');

		return redirect()->route('incoming_mail');
	}

	public function delete(Request $r)
	{
		$archieve = Archieve::where('_id', $r->id)->delete();

		$r->session()->flash('success', 'Surat masuk berhasil dihapus');

		return redirect()->route('incoming_mail');
	}
}
