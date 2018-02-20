<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\StorageSub, App\User, App\Storage, App\Share, App\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;
use Carbon\Carbon;

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
					'$unwind' => array(
						'path' => '$share',
						'preserveNullAndEmptyArrays' => true
					)
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
						'to' => 1,
						'reference_number' => 1,
						'date' => 1,
						'subject' => 1,
						'share.user._id' => 1,
						'share.user.name' => 1,
						'share.user.position' => 1,
						'share.user.photo' => 1,
						'share.date' => 1,
						'share.message' => 1,
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
							'$push' => array(
								'user' => '$share.user',
								'date' => '$share.date',
								'message' => '$share.message'
							)
						)
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
		$data['archieve'] = Archieve::find($id);
		
		if (isset($_GET['read'])) {
			$notifications = Notifications::find($_GET['read']);
			$notifications->read = 1;
			$notifications->save();
		}

		return view('app.outgoing_mail.detail', $data);
	}

	public function getDetail($id)
	{
		$archieve = Archieve::raw(function($collection){
			return $collection->aggregate(array(
				array(
					'$unwind' => array(
						'path' => '$share',
						'preserveNullAndEmptyArrays' => true
					)
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
						'to' => 1,
						'reference_number' => 1,
						'date' => 1,
						'subject' => 1,
						'share.user._id' => 1,
						'share.user.name' => 1,
						'share.user.position' => 1,
						'share.user.photo' => 1,
						'share.date' => 1,
						'share.message' => 1,
						'storagesub._id' => 1,
						'storagesub.name' => 1,
						'storage._id' => 1,
						'storage.name' => 1,
						'folder' => 1,
						'files' => 1,
					)
				),
				array(
					'$group' => array(
						'_id' => '$_id',
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
						'share' => array(
							'$push' => array(
								'user' => '$share.user',
								'date' => '$share.date',
								'message' => '$share.message'
							)
						)
					)
				)
			));
		})->where('_id', $id);

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

			// OCR To
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
		if ($r->storagesub != '') {
			$surat->storagesub = GlobalClass::generateMongoObjectId($r->storagesub);
		}
		$surat->note = $r->note;
		$surat->folder = $r->folder;
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

		//Move Images from tesseract to outgoing mail
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
		//Outgoing Mail
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
		$id_company = Auth::user()->id_company;
		for ($i=0; $i < count($files) ; $i++) { 
			copy(public_path('files').'/'.$id_company.'/outgoing_mail/'.$files[$i], public_path('assets/tesseract').'/'.Auth::user()->_id.'/'.$filename[$i].'.'.$ext[$i]);
		}

		return redirect()->route('outgoing_mail_edit', ['id' => $id]);
	}

	public function edit($id)
	{	
		//Outgoing Mail
		$archieve = Archieve::find($id);

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
			'date'				=> 'required',
			'storage'			=> 'required',
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
			$ext = substr($file[$i], -3);
			if ($ext == 'jpg' || $ext == 'peg' || $ext == 'png' || $ext == 'pdf') {
				array_push($images, $file[$i]);
			}
		}

		//Move Images from tesseract to outgoing mail
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
			rename(public_path('assets/tesseract').'/'.Auth::user()->_id.'/'.$img, public_path('files').'/'.$id_company.'/outgoing_mail/'.$nm_file);
			$files[] = $nm_file;
		}

		$surat->files = $files;
		$surat->save();

		$r->session()->flash('success', 'Berhasil menyimpan pembaruan');

		return redirect()->route('outgoing_mail');
	}

	public function shared(Request $r)
	{
		$surat = Archieve::find($r->id);

		$share = [];
		@$key = array_keys($r->share);
		for ($i=0; $i < count($r->share) ; $i++) {
			$date = Carbon::createFromFormat('d/m/Y', $r->date[$key[$i]]);
			$share[] = [
				'_id' => GlobalClass::generateMongoObjectId($r->share[$key[$i]]),
				'date' => GlobalClass::generateIsoDate($date),
				'message' => $r->message[$key[$i]]
			];
			
			// Notification
			GlobalClass::notif(
				GlobalClass::generateMongoObjectId($r->share[$key[$i]]),
				Auth::user()->name.' membagikan surat keluar kepada <b>'.$surat->to.'</b> kepada Anda',
				route('shared_outgoing_mail_detail', ['_id' => $surat->_id])
			);
		}


		if ($r->share != null) {
			$surat->share = $share;
		} else {
			$surat->share = '';
		}
		$surat->save();

		$r->session()->flash('success', 'Surat keluar berhasil dibagikan');

		return redirect()->route('outgoing_mail');
	}

	public function sharedHistory($id)
	{
		$data['archieve'] = Archieve::find($id);

		return view('app.outgoing_mail.shared', $data);
	}

	public function delete(Request $r)
	{
		Archieve::where('_id', $r->id)->delete();

		$r->session()->flash('success', 'Surat keluar berhasil dihapus');

		return redirect()->route('outgoing_mail');
	}
}
