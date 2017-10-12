<?php

namespace App\Http\Controllers\App;
use App\Archieve;
use Illuminate\Http\Request;
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
		$data['archieve'] = Archieve::where('type', 'incoming_mail')->where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->whereNull('deleted_at')->paginate(25);
		return view('app.search.index', $data);
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
					'$match' => array(
						'search' => array(
							'$regex' => $q,
							'$options' => 'i'
						),
						'id_user' => GlobalClass::generateMongoObjectId(Auth::user()->_id),
						'id_company' => Auth::user()->id_company,
						'deleted_at' => null
					)
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
}
