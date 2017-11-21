<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\Storage, App\StorageSub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use GlobalClass, Auth;

class SearchController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		if (!isset($_GET['q'])) {
			return redirect()->back();
		}

		//Storage
		$data['storage'] = Storage::where('id_company', Auth::user()->id_company)->orderBy('name')->get();

		if (@$_GET['storage'] != null && @$_GET['storage'] != '') {
			$data['storage_name'] = Storage::where('_id', GlobalClass::generateMongoObjectId($_GET['storage']))->select('name')->first();
		}

		// Archieve
		$limit = 25; // change in index too
		$data['archieve'] = Archieve::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->whereNull('deleted_at')->paginate($limit);

		return view('app.search.index', $data);
	}

	public function dropdownAjax()
	{
		$storage_id = Input::get('storage_id');

		$storage = StorageSub::where('id_storage', '=', GlobalClass::generateMongoObjectId($storage_id))->orderBy('name')->get();
		return response()->json($storage);
	}

	public function getData()
	{
		// Type Archieve
		$type = @$_GET['type'];
		
		// Range Date
		$start_date = @$_GET['start'];
		$start_date = str_replace('/', '-', $start_date);
		$start = strval(strtotime($start_date) * 1000);

		$end_date = @$_GET['end'];
		$end_date = str_replace('/', '-', $end_date);
		$end = strval(strtotime($end_date) * 1000);

		$archieve = Archieve::raw(function($collection){
		
			$q = @$_GET['q'];

			// Fulltext
			$fulltext = '';
			if (@$_GET['fulltext'] != '' OR @$_GET['fulltext'] != null) {
				$fulltext = @$_GET['fulltext'];
			}

			// Storage
			$storage = array(
				'$ne' => '@'
			);
			if (@$_GET['storage'] != '' OR @$_GET['storage'] != null) {
				$storage = GlobalClass::generateMongoObjectId(@$_GET['storage']);
			}
			$storagesub = @$_GET['storagesub'];

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
						'fulltext' => array(
							'$regex' => $fulltext,
							'$options' => 'i'
						),
						'id_user' => GlobalClass::generateMongoObjectId(Auth::user()->_id),
						'id_company' => Auth::user()->id_company,
						'deleted_at' => null,
						'storage._id' => $storage
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

		if ($type != null) {
			$archieve = $archieve->where('type', $type);
		}

		if (@$_GET['start'] != null && @$_GET['end'] != null) {
			$archieve = $archieve->where('date', '>=', $start)->where('date', '<=', $end);
		}

		return response()->json([
			'search'  =>  $archieve,
		]);

	}

	public function getDataAutocomplete()
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
					'$group' => array(
						'_id' => '$_id',
						'id_user' => array(
							'$first' => '$id_user'
						),
						'id_company' => array(
							'$first' => '$id_company'
						),
						'search' => array(
							'$first' => '$search'
						),
						'deleted_at' => array(
							'$first' => '$deleted_at'
						),
					)
				),
				array(
					'$match' => array(
						'id_user' => GlobalClass::generateMongoObjectId(Auth::user()->_id),
						'id_company' => Auth::user()->id_company,
						'deleted_at' => null
					)
				)
			));
		});

		return response()->json($archieve);
	}

	public function delete(Request $r)
	{
		$archieve = Archieve::where('_id', $r->id)->delete();

		$r->session()->flash('success', 'Arsip berhasil dihapus');

		return redirect()->back();
	}
}
