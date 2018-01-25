<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;
use Carbon\Carbon;

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
			$sortKey = 'created_at';
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
					'$project' => array(
						'name' => 1,
						'date' => 1,
						'desc' => 1,
						'share.user._id' => 1,
						'share.user.name' => 1,
						'share.user.position' => 1,
						'share.user.photo' => 1,
						'share.date' => 1,
						'share.message' => 1,
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
		$data['archieve'] = Archieve::find($id);

		//Folder
		$data['folder'] = Archieve::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->select('folder')->groupBy('folder')->orderBy('folder')->get();

		return view('app.file.detail', $data);
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
					'$project' => array(
						'name' => 1,
						'date' => 1,
						'desc' => 1,
						'share.user._id' => 1,
						'share.user.name' => 1,
						'share.user.position' => 1,
						'share.user.photo' => 1,
						'share.date' => 1,
						'share.message' => 1,
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
		$destination = public_path('assets/app/img/files');
		$file_arr = GlobalClass::UploadFile($r->file('file'), $destination);
		$files = implode(',',$file_arr);

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

		$r->session()->flash('success', 'Berhasil menyimpan pembaruan');

		return redirect()->route('file');
	}

	public function shared(Request $r)
	{
		$file = Archieve::find($r->id);

		$share = [];
		@$key = array_keys($r->share);
		for ($i=0; $i < count($r->share) ; $i++) {
			$date = Carbon::createFromFormat('d/m/Y', $r->date[$key[$i]]);
			$share[] = [
				'_id' => GlobalClass::generateMongoObjectId($r->share[$key[$i]]),
				'date' => GlobalClass::generateIsoDate($date),
				'message' => $r->message[$key[$i]]
			];

			$url = route('shared_file_detail', ['_id' => $file->_id]);
			$parts = explode("/",$url);
			array_shift($parts);array_shift($parts);array_shift($parts);
			$newurl = implode("/",$parts);
			
			// Notification
			GlobalClass::notif(
				GlobalClass::generateMongoObjectId($r->share[$key[$i]]),
				Auth::user()->name.' membagikan berkas <b>'.$file->name.'</b> kepada Anda',
				'/'.$newurl
			);
		}


		if ($r->share != null) {
			$file->share = $share;
		} else {
			$file->share = '';
		}
		$file->save();

		$r->session()->flash('success', 'Surat keluar berhasil dibagikan');

		return redirect()->route('file');
	}

	public function sharedHistory($id)
	{
		$data['archieve'] = Archieve::find($id);

		return view('app.file.shared', $data);
	}

	public function delete(Request $r)
	{
		$archieve = Archieve::where('_id', $r->id)->delete();

		$r->session()->flash('success', 'Berkas berhasil dihapus');

		return redirect()->route('file');
	}
}
