<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\User, App\Share, App\Notifications, App\Emails;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;
use Carbon\Carbon, URL;

class FileController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function index()
	{
		$limit = 25; // change in index too
		$data['archieve'] = Archieve::where('type', 'file')->where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->whereNull('deleted_at')->paginate($limit);

		//Folder
		$data['folder'] = Archieve::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->select('folder')->groupBy('folder')->orderBy('folder')->get();

		return view('app.file.index', $data);
	}

	public function getData()
	{
		$archieve = Archieve::raw(function($collection){
			
			// Sort By
			$sortKey = '_id';
			if (@$_GET['sort'] == 'name') {
				$sortKey = 'name';
			} else if (@$_GET['sort'] == 'date') {
				$sortKey = 'date';
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
						'name' => 1,
						'date' => 1,
						'id_original' => 1,
						'id_owner' => 1,
						'owner.name' => 1,
						'desc' => 1,
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
						'name' => array(
							'$first' => '$name'
						),
						'desc' => array(
							'$first' => '$desc'
						),
						'date' => array(
							'$first' => '$date'
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
						'type' => 'file',
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
			'files'  =>  $archieve,
			'users' => $users
		]);
	}

	public function detail($id)
	{
		try {
			$data['archieve'] = Archieve::findOrFail($id);

			//Folder
			$data['folder'] = Archieve::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->select('folder')->groupBy('folder')->orderBy('folder')->get();

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
					
					$notif = Notifications::where('link', '/berkas/detail/'.$id)->where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->first();
					$notif->read = 1;
					$notif->save();
				}
			}

			return view('app.file.detail', $data);
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
						'name' => 1,
						'date' => 1,
						'id_original' => 1,
						'id_owner' => 1,
						'desc' => 1,
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
						'name' => array(
							'$first' => '$name'
						),
						'desc' => array(
							'$first' => '$desc'
						),
						'date' => array(
							'$first' => '$date'
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
						)
					)
				)
			));
		})->where('_id', $id);

		$users = User::select('name', 'position', 'photo')->where('id_company', Auth::user()->id_company)->get();

		return response()->json([
			'files'  =>  $archieve,
			'users' => $users
		]);
	}

	public function store(Request $r)
	{
		$this->validate($r, [
			'name'				=> 'required',
			'file'				=> 'required'
		]);

		// Get id Company
		$id_company = Auth::user()->id_company;

		$date = Carbon::now();

		$file = new Archieve;
		$file->id_user = GlobalClass::generateMongoObjectId(Auth::user()->_id);
		$file->id_company = Auth::user()->id_company;
		$file->type = "file";
		$file->name = $r->name;
		$file->search = $r->name;
		$file->desc = $r->desc;
		$file->fulltext = $r->name;
		$file->date = GlobalClass::generateIsoDate($date);
		$file->folder = $r->folder;

		// Upload Image
		$destination = public_path('files/'.$id_company.'/file');
		$file_arr = GlobalClass::UploadFile($r->file('file'), $destination);
		$files = implode(',',$file_arr);
		$files = array($files);

		$file->files = $files;
		$file->save();

		$r->session()->flash('success', 'Berkas baru berhasil ditambahkan');

		return redirect()->route('file');
	}

	public function update(Request $r)
	{
		$this->validate($r, [
			'name'	=> 'required',
		]);

		$file = Archieve::find($r->id);
		$file->name = $r->name;
		$file->search = $r->name;
		$file->desc = $r->desc;
		$file->fulltext = $r->name;
		$file->folder = $r->folder;
		$file->save();

		// Shared update
		$getIDs = Archieve::where('id_original', GlobalClass::generateMongoObjectId($r->id))->get();
		foreach ($getIDs as $share) {
			$shared = Archieve::find($share->id);
			$shared->name = $r->name;
			$shared->search = $r->name;
			$shared->desc = $r->desc;
			$shared->fulltext = $r->name;
			$shared->folder = $r->folder;
			$shared->save();
		}

		$r->session()->flash('success', 'Berhasil menyimpan pembaruan');

		return redirect()->route('file');
	}

	public function shared(Request $r)
	{
		$disposition = Archieve::find($r->id);

		@$key = array_keys($r->share);
		for ($i=0; $i < count($r->share) ; $i++) {
			$date = Carbon::createFromFormat('d/m/Y', $r->date[$key[$i]]);
			
			$file = new Archieve;
			$file->id_user = GlobalClass::generateMongoObjectId($r->share[$key[$i]]);
			if ($disposition->id_original === null) {
				$file->id_original = GlobalClass::generateMongoObjectId($disposition->_id);
				$file->id_owner = GlobalClass::generateMongoObjectId($disposition->id_user);
			} else {
				$file->id_original = GlobalClass::generateMongoObjectId($disposition->id_original);
				$file->id_owner = GlobalClass::generateMongoObjectId($disposition->id_owner);
			}
			$file->id_company = Auth::user()->id_company;
			$file->type = "file";
			$file->name = $disposition->name;
			$file->search = $disposition->name;
			$file->desc = $disposition->desc;
			$file->fulltext = $disposition->name;
			$file->date = GlobalClass::generateIsoDate($date);
			$file->folder = $disposition->folder;
			if ($disposition->storagesub != '') {
				$surat->storagesub = $disposition->storagesub;
			}
			$file->files = $disposition->files;
			$file->save();
			
			$share = new Share;
			if ($disposition->id_original === null) {
				$share->id_archieve = GlobalClass::generateMongoObjectId($disposition->_id);
			} else {
				$share->id_archieve = GlobalClass::generateMongoObjectId($disposition->id_original);

				// Notification to owner
				$user_name = User::find(GlobalClass::generateMongoObjectId($r->share[$key[$i]]));
				GlobalClass::notif(
					GlobalClass::generateMongoObjectId($disposition->id_owner),
					Auth::user()->name.' membagikan berkas <b>'.$disposition->to.'</b> kepada <b>'.$user_name->name.'</b>',
					URL::route('file_detail', array('id' => $disposition->id_original), false)
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
				Auth::user()->name.' membagikan berkas <b>'.$disposition->to.'</b> kepada Anda',
				URL::route('file_detail', array('id' => $file->getKey()), false)
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
			$emails->save();
		}

		$r->session()->flash('success', 'Berkas berhasil dibagikan');

		return redirect()->route('file');
	}

	public function sharedHistory($id)
	{
		$data['archieve'] = Archieve::findOrFail($id);

		return view('app.file.shared', $data);
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
			'files'  =>  $archieve
		]);
	}

	public function delete(Request $r)
	{
		$archieve = Archieve::where('_id', $r->id)->delete();

		$r->session()->flash('success', 'Berkas berhasil dihapus');

		return redirect()->route('file');
	}
}
