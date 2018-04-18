<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\StorageSub, App\User, App\Storage, App\Share, App\Notifications, App\Emails;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;
use Carbon\Carbon, URL;

class OutgoingMailController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		$limit = 25; // change in index too
		$data['archieve'] = Archieve::where('type', 'outgoing_mail')->where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->whereNull('deleted_at')->paginate($limit);
		return view('app.outgoing_mail.index', $data);
	}

	public function getData()
	{
		$archieve = Archieve::raw(function($collection){
			
			// Sort By
			$sortKey = '_id';
			if (@$_GET['sort'] == 'to') {
				$sortKey = 'to';
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
						'from' => 'storage_sub',
						'localField' => 'storagesub',
						'foreignField' => '_id',
						'as' => 'storagesub'
					)
				),
				array(
					'$lookup' => array(
						'from' => 'storage',
						'localField' => 'storage',
						'foreignField' => '_id',
						'as' => 'storage'
					)
				),
				array(
					'$lookup' => array(
						'from' => 'share',
						'localField' => '_id',
						'foreignField' => 'id_archieve',
						'as' => 'share_info'
					)
				),
				array(
					'$lookup' => array(
						'from' => 'share',
						'localField' => 'id_original',
						'foreignField' => 'id_archieve',
						'as' => 'share_info_shared'
					)
				),
				array(
					'$unwind' => array(
						'path' => '$share',
						'preserveNullAndEmptyArrays' => true
					)
				),
				array(
					'$lookup' => array(
						'from' => 'users',
						'localField' => 'share_info.share_to',
						'foreignField' => '_id',
						'as' => 'share'
					)
				),
				array(
					'$lookup' => array(
						'from' => 'users',
						'localField' => 'share_info_shared.share_to',
						'foreignField' => '_id',
						'as' => 'shared'
					)
				),
				array(
					'$lookup' => array(
						'from' => 'users',
						'localField' => 'share_info_shared.share_from',
						'foreignField' => '_id',
						'as' => 'owner'
					)
				),
				array(
					'$project' => array(
						'to' => 1,
						'reference_number' => 1,
						'id_original' => 1,
						'id_owner' => 1,
						'owner.name' => 1,
						'date' => 1,
						'subject' => 1,
						'share_info' => 1,
						'share_info_shared' => 1,
						'share._id' => 1,
						'share.name' => 1,
						'share.position' => 1,
						'share.photo' => 1,
						'share.date' => 1,
						'share.message' => 1,
						'share.read' => 1,
						'shared._id' => 1,
						'shared.name' => 1,
						'shared.position' => 1,
						'shared.photo' => 1,
						'shared.date' => 1,
						'shared.message' => 1,
						'shared.read' => 1,
						'storagesub._id' => 1,
						'storagesub.name' => 1,
						'storage._id' => 1,
						'storage.name' => 1,
						'files' => 1,
						'type' => 1,
						'folder' => 1,
						'id_user' => 1,
						'id_company' => 1,
						'deleted_at' => 1
					)
				),
				array(
					'$group' => array(
						'_id' => '$_id',
						'id_user' => array(
							'$first' => '$id_user'
						),
						'id_company' => array(
							'$first' => '$id_company'
						),
						'id_original' => array(
							'$first' => '$id_original'
						),
						'id_owner' => array(
							'$first' => '$id_owner'
						),
						'owner' => array(
							'$first' => '$owner'
						),
						'type' => array(
							'$first' => '$type'
						),
						'to' => array(
							'$first' => '$to'
						),
						'subject' => array(
							'$first' => '$subject'
						),
						'reference_number' => array(
							'$first' => '$reference_number'
						),
						'date' => array(
							'$first' => '$date'
						),
						'reference_number' => array(
							'$first' => '$reference_number'
						),
						'storagesub' => array(
							'$first' => '$storagesub'
						),
						'storage' => array(
							'$first' => '$storage'
						),
						'folder' => array(
							'$first' => '$folder'
						),
						'files' => array(
							'$first' => '$files'
						),
						'deleted_at' => array(
							'$first' => '$deleted_at'
						),
						'share' => array(
							'$first' => '$share'
						),
						'shared' => array(
							'$first' => '$shared'
						),
						'share_info' => array(
							'$first' => '$share_info'
						),
						'share_info_shared' => array(
							'$first' => '$share_info_shared'
						),
					)
				),
				array(
					'$sort' => array(
						$sortKey => $asc
					)
				),
				array(
					'$match' => array(
						'type' => 'outgoing_mail',
						'id_user' => GlobalClass::generateMongoObjectId(Auth::user()->_id),
						'id_company' => Auth::user()->id_company,
						'deleted_at' => null
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

		$users = User::select('name', 'position', 'photo')->where('id_company', Auth::user()->id_company)->get();

		return response()->json([
			'outgoingMail'  =>  $archieve,
			'users' => $users
		]);
	}

	public function detail($id)
	{
		try {
			$data['archieve'] = Archieve::findOrFail($id);

			if (isset($_GET['read'])) {
				$notifications = Notifications::find($_GET['read']);
				$notifications->read = 1;
				$notifications->save();
			}

			if (isset($_GET['read']) OR isset($_GET['read_direct'])) {
				if ($data['archieve']->id_original != null) {
					$share = Share::where('id_archieve', GlobalClass::generateMongoObjectId($data['archieve']->id_original))->where('share_to', GlobalClass::generateMongoObjectId(Auth::user()->_id))->first();
					$share->read = 1;
					$share->save();
					
					$notif = Notifications::where('link', '/surat/keluar/detail/'.$id)->where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->first();
					$notif->read = 1;
					$notif->save();
				}
			}

			return view('app.outgoing_mail.detail', $data);
		}
		catch(ModelNotFoundException $e)
		{
			return view('errors.detail-not-found');
		}
	}

	public function getDetail($id)
	{
		$archieve = Archieve::raw(function($collection){
			return $collection->aggregate(array(
				array(
					'$lookup' => array(
						'from' => 'storage_sub',
						'localField' => 'storagesub',
						'foreignField' => '_id',
						'as' => 'storagesub'
					)
				),
				array(
					'$lookup' => array(
						'from' => 'storage',
						'localField' => 'storage',
						'foreignField' => '_id',
						'as' => 'storage'
					)
				),
				array(
					'$lookup' => array(
						'from' => 'share',
						'localField' => '_id',
						'foreignField' => 'id_archieve',
						'as' => 'share_info'
					)
				),
				array(
					'$lookup' => array(
						'from' => 'share',
						'localField' => 'id_original',
						'foreignField' => 'id_archieve',
						'as' => 'share_info_shared'
					)
				),
				array(
					'$unwind' => array(
						'path' => '$share',
						'preserveNullAndEmptyArrays' => true
					)
				),
				array(
					'$lookup' => array(
						'from' => 'users',
						'localField' => 'share_info.share_to',
						'foreignField' => '_id',
						'as' => 'share'
					)
				),
				array(
					'$lookup' => array(
						'from' => 'users',
						'localField' => 'share_info_shared.share_to',
						'foreignField' => '_id',
						'as' => 'shared'
					)
				),
				array(
					'$project' => array(
						'to' => 1,
						'reference_number' => 1,
						'id_original' => 1,
						'id_owner' => 1,
						'date' => 1,
						'subject' => 1,
						'share_info' => 1,
						'share_info_shared' => 1,
						'share._id' => 1,
						'share.name' => 1,
						'share.position' => 1,
						'share.photo' => 1,
						'share.date' => 1,
						'share.message' => 1,
						'shared._id' => 1,
						'shared.name' => 1,
						'shared.position' => 1,
						'shared.photo' => 1,
						'shared.date' => 1,
						'shared.message' => 1,
						'storagesub._id' => 1,
						'storagesub.name' => 1,
						'storage._id' => 1,
						'storage.name' => 1,
						'files' => 1,
						'type' => 1,
						'folder' => 1,
						'id_user' => 1,
						'id_company' => 1,
						'deleted_at' => 1
					)
				),
				array(
					'$group' => array(
						'_id' => '$_id',
						'id_user' => array(
							'$first' => '$id_user'
						),
						'id_company' => array(
							'$first' => '$id_company'
						),
						'id_original' => array(
							'$first' => '$id_original'
						),
						'id_owner' => array(
							'$first' => '$id_owner'
						),
						'type' => array(
							'$first' => '$type'
						),
						'to' => array(
							'$first' => '$to'
						),
						'subject' => array(
							'$first' => '$subject'
						),
						'reference_number' => array(
							'$first' => '$reference_number'
						),
						'date' => array(
							'$first' => '$date'
						),
						'reference_number' => array(
							'$first' => '$reference_number'
						),
						'storagesub' => array(
							'$first' => '$storagesub'
						),
						'storage' => array(
							'$first' => '$storage'
						),
						'folder' => array(
							'$first' => '$folder'
						),
						'files' => array(
							'$first' => '$files'
						),
						'deleted_at' => array(
							'$first' => '$deleted_at'
						),
						'share' => array(
							'$first' => '$share'
						),
						'shared' => array(
							'$first' => '$shared'
						),
						'share_info' => array(
							'$first' => '$share_info'
						),
						'share_info_shared' => array(
							'$first' => '$share_info_shared'
						),
					)
				)
			));
		})->where('_id', $id)->where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id));

		$users = User::select('name', 'position', 'photo')->where('id_company', Auth::user()->id_company)->get();

		return response()->json([
			'outgoingMail'  =>  $archieve,
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
			return redirect()->route('outgoing_mail_create');
		} else {
			$images = [];
			for ($i=0; $i < count($files); $i++) { 
				// Conditions for find images
				$ext = strtolower(substr($files[$i], -3));
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

			return redirect()->route('outgoing_mail_create');
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
			$nm_file = $i + 1;
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

		return redirect()->back();
	}

	public function removeAjax(Request $r)
	{
		unlink(public_path('assets/tesseract').'/'.Auth::user()->_id.'/'.$r->image);
	}

	public function dropdownAjax()
	{
		$storage_id = Input::get('storage_id');

		$storage = StorageSub::where('id_storage', '=', GlobalClass::generateMongoObjectId($storage_id))->orderBy('name')->get();
		return response()->json($storage);
	}

	public function create()
	{
		// Image
		$dir = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$files = scandir($dir);

		$images = [];
		for ($i=0; $i < count($files); $i++) { 
			// Conditions for find images
			$ext = strtolower(substr($files[$i], -3));
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
			$data['to'] = GlobalClass::OCRKey($image, $result, $open, 'to');

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

		//Folder
		$data['folder'] = Archieve::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->select('folder')->groupBy('folder')->orderBy('folder')->get();

		return view('app.outgoing_mail.create', $data);
	}

	public function store(Request $r)
	{
		$this->validate($r, [
			'to'				=> 'required',
			'reference_number'	=> 'required',
			'subject'			=> 'required',
			'date'				=> 'required'
		]);

		// Get id Company
		$id_company = Auth::user()->id_company;

		$date = Carbon::createFromFormat('d/m/Y', $r->date);

		$surat = new Archieve;
		$surat->id_user = GlobalClass::generateMongoObjectId(Auth::user()->_id);
		$surat->id_company = Auth::user()->id_company;
		$surat->type = "outgoing_mail";
		$surat->to = $r->to;
		$surat->search = $r->to;
		$surat->reference_number = $r->reference_number;
		$surat->subject = $r->subject;
		$surat->date = GlobalClass::generateIsoDate($date);
		$surat->storage = GlobalClass::generateMongoObjectId($r->storage);
		$surat->folder = $r->folder;
		if ($r->storagesub != '') {
			$surat->storagesub = GlobalClass::generateMongoObjectId($r->storagesub);
		}
		$surat->note = $r->note;
		$surat->fulltext = $r->fulltext == null ? $r->to : $r->fulltext;

		//Check Image From Tesseract
		$dir = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$file = scandir($dir);
		$images = [];
		for ($i=0; $i < count($file); $i++) { 
			// Conditions for find images
			$ext = strtolower(substr($file[$i], -3));
			if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png' || $ext == 'pdf') {
				array_push($images, $file[$i]);
			}
		}

		//Move Images from tesseract to outgoing mail
		$files = [];
		foreach ($images as $img) {
			$rand = rand(111111,999999);
			$ext = strtolower(substr($img, -3));
			if ($ext == 'jpg') {
				$nm_file = $rand.'.'.'jpg';
			} elseif ($ext == 'png') {
				$nm_file = $rand.'.'.'png';
			} elseif ($ext == 'pdf'){
				$nm_file = $rand.'.'.'pdf';
			} else {
				$nm_file = $rand.'.'.'jpeg';
			}
			rename(public_path('assets/tesseract').'/'.Auth::user()->_id.'/'.$img, public_path('files').'/'.$id_company.'/outgoing_mail/'.$nm_file);
			$files[] = $nm_file;
		}

		$surat->files = $files;
		$surat->save();

		$r->session()->flash('success', 'Surat keluar baru berhasil ditambahkan');

		return redirect()->route('outgoing_mail');
	}

	public function move($id)
	{
		//outgoing Mail
		$archieve = Archieve::find($id);
		$data['archieve'] = $archieve;

		//Delete All file in the Directory
		$file = public_path('assets/tesseract'.'/'.Auth::user()->_id); 
		if (file_exists($file)) {
			array_map('unlink', glob("$file/*.*"));
		} else {
			mkdir(public_path('assets/tesseract'.'/'.Auth::user()->_id), 0777, true);
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
		$id_company = Auth::user()->id_company;
		for ($i=0; $i < count($files) ; $i++) { 
			copy(public_path('files').'/'.$id_company.'/outgoing_mail/'.$files[$i], public_path('assets/tesseract').'/'.Auth::user()->_id.'/'.$filename[$i].'.'.$ext[$i]);
		}

		return redirect()->route('outgoing_mail_edit', ['id' => $id]);
	}

	public function edit($id)
	{	
		//outgoing Mail
		$archieve = Archieve::findOrFail($id);

		//Check Id Archieve
		if ($archieve == false) {
			return redirect()->route('outgoing_mail');
		}

		//Search For id_user Equation With id login
		if ($archieve->id_user != GlobalClass::generateMongoObjectId(Auth::user()->_id)) {
			return redirect()->route('outgoing_mail');
		}

		$data['archieve'] = $archieve;

		// Image
		$dir = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$files = scandir($dir);

		$images = [];
		for ($i=0; $i < count($files); $i++) { 
			// Conditions for find images
			$ext = strtolower(substr($files[$i], -3));
			if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png' || $ext == 'pdf') {
				array_push($images, $files[$i]);
			}
		}
		$data['image'] = $images; 
		

		//Storage
		$data['storage'] = Storage::where('id_company', Auth::user()->id_company)->orderBy('name')->get();

		//Sub Storage
		$data['storagesub'] = StorageSub::orderBy('name')->get();

		//Folder
		$data['folder'] = Archieve::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->select('folder')->groupBy('folder')->orderBy('folder')->get();

		return view('app.outgoing_mail.edit', $data);
	}

	public function update($id, Request $r)
	{
		$this->validate($r, [
			'to'				=> 'required',
			'reference_number'	=> 'required',
			'subject'			=> 'required',
			'date'				=> 'required'
		]);

		// Get id Company
		$id_company = Auth::user()->id_company;

		$date = Carbon::createFromFormat('d/m/Y', $r->date);

		$surat = Archieve::find($id);
		$surat->id_user = GlobalClass::generateMongoObjectId(Auth::user()->_id);
		$surat->id_company = Auth::user()->id_company;
		$surat->type = "outgoing_mail";
		$surat->to = $r->to;
		$surat->search = $r->to;
		$surat->reference_number = $r->reference_number;
		$surat->subject = $r->subject;
		$surat->date = GlobalClass::generateIsoDate($date);
		$surat->storage = GlobalClass::generateMongoObjectId($r->storage);
		if ($r->storagesub != '') {
			$surat->storagesub = GlobalClass::generateMongoObjectId($r->storagesub);
		}
		$surat->folder = $r->folder;
		$surat->note = $r->note;

		//Delete Old File
		$old = $surat->files;
		for ($i=0; $i < count($old) ; $i++) { 
			unlink(public_path('files').'/'.$id_company.'/outgoing_mail/'.$old[$i]);
		}

		//Check Image From Tesseract
		$dir = public_path('assets/tesseract'.'/'.Auth::user()->_id);
		$file = scandir($dir);
		$images = [];
		for ($i=0; $i < count($file); $i++) { 
			// Conditions for find images
			$ext = strtolower(substr($file[$i], -3));
			if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png' || $ext == 'pdf') {
				array_push($images, $file[$i]);
			}
		}

		//Move Images from tesseract to outgoing mail
		$files = [];
		foreach ($images as $img) {
			$rand = rand(111111,999999);
			$ext = strtolower(substr($img, -3));
			if ($ext == 'jpg') {
				$nm_file = $rand.'.'.'jpg';
			} elseif ($ext == 'png') {
				$nm_file = $rand.'.'.'png';
			} elseif ($ext == 'pdf') {
				$nm_file = $rand.'.'.'pdf';
			} else {
				$nm_file = $rand.'.'.'jpeg';
			}
			rename(public_path('assets/tesseract').'/'.Auth::user()->_id.'/'.$img, public_path('files').'/'.$id_company.'/outgoing_mail/'.$nm_file);
			$files[] = $nm_file;
		}

		$surat->files = $files;
		$surat->save();

		// Shared update
		$getIDs = Archieve::where('id_original', GlobalClass::generateMongoObjectId($id))->get();
		foreach ($getIDs as $share) {
			$shared = Archieve::find($share->id);
			$shared->to = $r->to;
			$shared->search = $r->to;
			$shared->reference_number = $r->reference_number;
			$shared->subject = $r->subject;
			$shared->date = GlobalClass::generateIsoDate($date);
			$shared->storage = GlobalClass::generateMongoObjectId($r->storage);
			if ($r->storagesub != '') {
				$shared->storagesub = GlobalClass::generateMongoObjectId($r->storagesub);
			}
			$shared->folder = $r->folder;
			$shared->note = $r->note;
			$shared->files = $files;
			$shared->save();
		}


		$r->session()->flash('success', 'Berhasil menyimpan pembaruan');

		return redirect()->route('outgoing_mail');
	}

	public function shared(Request $r)
	{
		$disposition = Archieve::find($r->id);

		@$key = array_keys($r->share);
		for ($i=0; $i < count($r->share) ; $i++) {
			$date = Carbon::createFromFormat('d/m/Y', $r->date[$key[$i]]);
			
			$surat = new Archieve;
			$surat->id_user = GlobalClass::generateMongoObjectId($r->share[$key[$i]]);
			if ($disposition->id_original === null) {
				$surat->id_original = GlobalClass::generateMongoObjectId($disposition->_id);
				$surat->id_owner = GlobalClass::generateMongoObjectId($disposition->id_user);
			} else {
				$surat->id_original = GlobalClass::generateMongoObjectId($disposition->id_original);
				$surat->id_owner = GlobalClass::generateMongoObjectId($disposition->id_owner);
			}
			$surat->id_company = Auth::user()->id_company;
			$surat->type = "outgoing_mail";
			$surat->to = $disposition->to;
			$surat->search = $disposition->to;
			$surat->reference_number = $disposition->reference_number;
			$surat->subject = $disposition->subject;
			$surat->date = $disposition->date;
			$surat->storage = $disposition->storage;
			$surat->folder = $disposition->folder;
			if ($disposition->storagesub != '') {
				$surat->storagesub = $disposition->storagesub;
			}
			$surat->note = $disposition->note;
			$surat->fulltext = $disposition->fulltext;
			$surat->files = $disposition->files;
			$surat->save();
			
			$share = new Share;
			if ($disposition->id_original === null) {
				$share->id_archieve = GlobalClass::generateMongoObjectId($disposition->_id);
			} else {
				$share->id_archieve = GlobalClass::generateMongoObjectId($disposition->id_original);

				// Notification to owner
				$user_name = User::find(GlobalClass::generateMongoObjectId($r->share[$key[$i]]));
				GlobalClass::notif(
					GlobalClass::generateMongoObjectId($disposition->id_owner),
					Auth::user()->name.' membagikan surat keluar dari <b>'.$disposition->to.'</b> kepada <b>'.$user_name->name.'</b>',
					URL::route('outgoing_mail_detail', array('id' => $disposition->id_original), false)
				);
			}
			$share->share_from = $disposition->id_user;
			$share->share_to = GlobalClass::generateMongoObjectId($r->share[$key[$i]]);
			$share->date = $date;
			$share->message = $r->message[$key[$i]];
			$share->read = 0;
			$share->save();
			
			// Notification
			GlobalClass::notif(
				GlobalClass::generateMongoObjectId($r->share[$key[$i]]),
				Auth::user()->name.' membagikan surat keluar dari <b>'.$disposition->to.'</b> kepada Anda',
				URL::route('outgoing_mail_detail', array('id' => $surat->getKey()), false)
			);

			// Emails
			$emails = new Emails;
			$emails->id_user = GlobalClass::generateMongoObjectId($r->share[$key[$i]]);
			$emails->id_user_from = GlobalClass::generateMongoObjectId(Auth::user()->_id);
			$emails->type = 'disposition';
			$emails->status = 0;
			if ($disposition->id_original === null) {
				$emails->id_archieve = GlobalClass::generateMongoObjectId($disposition->_id);
			} else {
				$emails->id_archieve = GlobalClass::generateMongoObjectId($disposition->id_original);
			}
			$emails->link = $surat->id;
			$emails->save();
		}

		$r->session()->flash('success', 'Surat keluar berhasil didisposisi');

		return redirect()->route('outgoing_mail');
	}

	public function sharedHistory($id)
	{
		$data['archieve'] = Archieve::findOrFail($id);

		return view('app.outgoing_mail.disposition', $data);
	}

	public function sharedDelete($id, $id_user, $id_archieve)
	{
		Archieve::where('id_user', GlobalClass::generateMongoObjectId($id_user))->where('id_original', GlobalClass::generateMongoObjectId($id_archieve))->forceDelete();
		Share::find($id)->delete();

		return redirect()->back();
	}

	public function getDetailShared($id)
	{
		$archieve = Share::raw(function($collection) use($id){

			return $collection->aggregate(array(
				array(
					'$lookup' => array(
						'from' => 'users',
						'localField' => 'share_to',
						'foreignField' => '_id',
						'as' => 'user'
					)
				),
				array(
					'$project' => array(
						'id_archieve' => 1,
						'date.date' => 1,
						'message' => 1,
						'user._id' => 1,
						'user.name' => 1,
					)
				),
				array(
					'$match' => array(
						'id_archieve' => GlobalClass::generateMongoObjectId($id),
					)
				),
				array(
					'$sort' => array(
						'date' => -1
					)
				)
			));
		});

		return response()->json([
			'outgoingMail'  =>  $archieve
		]);
	}

	public function delete(Request $r)
	{
		Archieve::where('_id', $r->id)->delete();

		$r->session()->flash('success', 'Surat keluar berhasil dihapus');

		return redirect()->route('outgoing_mail');
	}
}
