<?php

namespace App\Http\Controllers\App;
use App\Archieve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;

class FolderController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		$limit = 25; // change in index too
		$data['archieve'] = Archieve::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->select('folder')->groupBy('folder')->paginate($limit);
		return view('app.folder.index', $data);
	}

	public function getData()
	{
		$archieve = Archieve::raw(function($collection){

			return $collection->aggregate(array(
				array(
					'$project' => array(
						'folder' => 1,
						'id_user' => 1,
						'deleted_at' => 1
					)
				),
				array(
					'$group' => array(
						'_id' => array(
							'folder' => '$folder',
							'id' => '$id_user',
							'deleted_at' => '$deleted_at',
						),
						'id_user' => array(
							'$first' => '$id_user'
						),
						'folder' => array(
							'$first' => '$folder'
						),
						'deleted_at' => array(
							'$first' => '$deleted_at'
						),
						'count' => array(
							'$sum' => 1
						)
					)
				),
				array(
					'$sort' => array(
						'folder' => 1
					)
				),
				array(
					'$match' => array(
						'id_user' => GlobalClass::generateMongoObjectId(Auth::user()->_id),
						'deleted_at' => null,
						'folder' => array(
							'$ne' => null
						)
					)
				)
			));
		});

		return response()->json([
			'folder'  =>  $archieve,
		]);
	}

	public function getDataDetail($folder)
	{
		$archieve = Archieve::raw(function($collection) use($folder) {
			$q = str_replace('_', ' ', $folder);

			// Sort By
			$sortKey = 'created_at';
			if (@$_GET['sort'] == 'search') {
				$sortKey = 'search';
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
						'search' => 1,
						'from' => 1,
						'to' => 1,
						'reference_number' => 1,
						'date' => 1,
						'subject' => 1,
						'desc' => 1,
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
						'id_user' => 1,
						'id_company' => 1,
						'fulltext' => 1,
						'folder' => 1,
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
						'search' => array(
							'$first' => '$search'
						),
						'from' => array(
							'$first' => '$from'
						),
						'to' => array(
							'$first' => '$to'
						),
						'subject' => array(
							'$first' => '$subject'
						),
						'desc' => array(
							'$first' => '$desc'
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
						'files' => array(
							'$first' => '$files'
						),
						'deleted_at' => array(
							'$first' => '$deleted_at'
						),
						'fulltext' => array(
							'$first' => '$fulltext'
						),
						'folder' => array(
							'$first' => '$folder'
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
						'folder' => $q,
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

		return response()->json([
			'folder'  =>  $archieve,
		]);
	}

	public function detail($folder)
	{
		$limit = 25; // change in index too
		$data['archieve'] = Archieve::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->whereNull('deleted_at')->paginate($limit);
		$data['folder'] = $folder;
		return view('app.folder.detail', $data);
	}

	public function update(Request $r)
	{
		$this->validate($r, [
			'old_folder' => 'required',
			'folder' => 'required',
		]);

		$folder = Archieve::where('folder', $r->old_folder)
							->where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))
							->update(['folder' => $r->folder]);

		$r->session()->flash('success', 'Berhasil menyimpan pembaruan');

		return redirect()->route('folder');
	}

	public function delete(Request $r)
	{
		$this->validate($r, [
			'folder' => 'required',
		]);

		$archieve = Archieve::where('folder', $r->folder)
								->where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))
								->delete();

		$r->session()->flash('success', 'Folder berhasil dihapus');

		return redirect()->route('folder');
	}
}
