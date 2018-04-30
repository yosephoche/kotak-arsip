<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\Tracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;

class TrashController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');

		// Tracker User
		$this->middleware(function ($request, $next) {
			Tracker::hit(Auth::user()->email, Auth::user()->id_company);
			return $next($request);
		});
	}

	public function index()
	{
		$limit = 25; // change in index too
		$data['archieve'] = Archieve::onlyTrashed()->where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->paginate($limit);
		return view('app.trash.index', $data);
	}

	public function getData()
	{
		$fulltext = @$_GET['fulltext'];
		$type = @$_GET['type'];
		$start = @$_GET['start'];
		$end = @$_GET['end'];
		$storage = @$_GET['storage'];
		$substorage = @$_GET['substorage'];
		
		$archieve = Archieve::raw(function($collection){

			$q = @$_GET['q'];

			// Sort By
			$sortKey = '_id';
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
						'id_original' => 1,
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
						'search' => array(
							'$regex' => $q,
							'$options' => 'i'
						),
						'id_user' => GlobalClass::generateMongoObjectId(Auth::user()->_id),
						'id_company' => Auth::user()->id_company,
						'deleted_at' => array(
							'$ne' => null
						)
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

		if ($fulltext != null) {
			$archieve = $archieve->where('fulltext', $fulltext);
		}

		if ($type != null) {
			$archieve = $archieve->where('type', $type);
		}

		if ($start != null) {
			$archieve = $archieve->where('start', $start);
		}

		if ($end != null) {
			$archieve = $archieve->where('end', $end);
		}

		if ($storage != null) {
			$archieve = $archieve->where('storage', $storage);
		}

		if ($substorage != null) {
			$archieve = $archieve->where('substorage', $substorage);
		}

		return response()->json([
			'search'  =>  $archieve,
		]);
	}

	public function restore(Request $r)
	{
		$archieve = Archieve::where('_id', $r->id)->unset('deleted_at');

		$r->session()->flash('success', 'Arsip berhasil dipulihkan');

		return redirect()->back();
	}

	public function delete(Request $r)
	{
		$old_files = Archieve::withTrashed()->find($r->id);

		// Get id Company
		$id_company = Auth::user()->id_company;

		//Delete Old File
		$old = $old_files->files;
		for ($i=0; $i < count($old) ; $i++) {
			unlink(public_path('files').'/'.$id_company.'/'.$old_files->type.'/'.$old[$i]);
		}

		Archieve::where('_id', $r->id)->forceDelete();
		Archieve::where('id_original', GlobalClass::generateMongoObjectId($r->id))->forceDelete();

		$r->session()->flash('success', 'Arsip berhasil dihapus');

		return redirect()->back();
	}

	public function deleteKeepFiles(Request $r)
	{
		Archieve::where('_id', $r->id)->forceDelete();

		$r->session()->flash('success', 'Arsip berhasil dihapus');

		return redirect()->back();
	}
}
