<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\StorageSub, App\User, App\Storage, App\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;
use Carbon\Carbon;
use MongoDB\Collection;

class SharedController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		return view('app.shared.index');
	}

	public function getDataIncomingMail()
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
					'$unwind' => array(
						'path' => '$share',
						'preserveNullAndEmptyArrays' => true
					)
				),
				array(
					'$lookup' => array(
						'from'=>'users',
						'localField'=>'id_user',
						'foreignField'=>'_id',
						'as'=>'userDetail'
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
						'userDetail' => 1,
						'from' => 1,
						'reference_number' => 1,
						'date' => 1,
						'subject' => 1,
						'share.user._id' => 1,
						'share.user.name' => 1,
						'share.user.email' => 1,
						'share.user.position' => 1,
						'share.user.photo' => 1,
						'share.date' => 1,
						'share.message' => 1,
						'share.deleted_at' => 1,
						'storagesub._id' => 1,
						'storagesub.name' => 1,
						'storage._id' => 1,
						'storage.name' => 1,
						'files' => 1,
						'type' => 1,
						'id_user' => 1,
						'id_company' => 1,
						'deleted_at' => 1
					)
				),
				array(
					'$group' => array(
						'_id' => '$_id',
						'userDetail' => array(
							'$first' => '$userDetail.name'
						),
						'id_user' => array(
							'$first' => '$id_user'
						),
						'id_company' => array(
							'$first' => '$id_company'
						),
						'type' => array(
							'$first' => '$type'
						),
						'from' => array(
							'$first' => '$from'
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
						'share' => array(
							'$push' => array(
								'user' => '$share.user',
								'date' => '$share.date',
								'message' => '$share.message',
								'deleted_at' => '$share.deleted_at'
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
						'type' => 'incoming_mail',
						'id_company' => Auth::user()->id_company,
						'deleted_at' => null,
						'share.user' => array(
							'$elemMatch' => array(
								'email' => Auth::user()->email
							)
						),
						// 'share.deleted_at' => array(
						// 	'$exists' => false
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

		$users = User::select('name', 'position', 'photo')->where('id_company', Auth::user()->id_company)->get();

		return response()->json([
			'incomingMail'  =>  $archieve,
			'users' => $users
		]);
	}

	public function getDataOutgoingMail()
	{
		$archieve = Archieve::raw(function($collection){
			
			// Sort By
			$sortKey = 'created_at';
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
						'localField'=>'id_user',
						'foreignField'=>'_id',
						'as'=>'userDetail'
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
						'userDetail' => 1,
						'to' => 1,
						'reference_number' => 1,
						'date' => 1,
						'subject' => 1,
						'share.user._id' => 1,
						'share.user.name' => 1,
						'share.user.email' => 1,
						'share.user.position' => 1,
						'share.user.photo' => 1,
						'share.date' => 1,
						'share.message' => 1,
						'share.deleted_at' => 1,
						'storagesub._id' => 1,
						'storagesub.name' => 1,
						'storage._id' => 1,
						'storage.name' => 1,
						'files' => 1,
						'type' => 1,
						'id_user' => 1,
						'id_company' => 1,
						'deleted_at' => 1
					)
				),
				array(
					'$group' => array(
						'_id' => '$_id',
						'userDetail' => array(
							'$first' => '$userDetail.name'
						),
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
								'message' => '$share.message',
								'deleted_at' => '$share.deleted_at'
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
						'id_company' => Auth::user()->id_company,
						'deleted_at' => null,
						'share.user' => array(
							'$elemMatch' => array(
								'email' => Auth::user()->email
							)
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

		return view('app.shared.detail', $data);
	}

	public function getDetailIncomingMail($id)
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
						'from' => 1,
						'reference_number' => 1,
						'date' => 1,
						'subject' => 1,
						'share.user._id' => 1,
						'share.user.name' => 1,
						'share.date' => 1,
						'share.message' => 1,
						'share.deleted_at' => 1,
						'storagesub.name' => 1,
						'storage._id' => 1,
						'storage.name' => 1,
						'files' => 1,
						'type' => 1,
						'id_user' => 1,
						'id_company' => 1,
						'deleted_at' => 1
					)
				),
				array(
					'$group' => array(
						'_id' => '$_id',
						'from' => array(
							'$first' => '$from'
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
			'incomingMail'  =>  $archieve,
			'users' => $users
		]);
	}

	public function getDetailOutgoingMail($id)
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
						'share.user.name' => 1,
						'share.date' => 1,
						'share.message' => 1,
						'share.deleted_at' => 1,
						'storagesub._id' => 1,
						'storagesub.name' => 1,
						'storage._id' => 1,
						'storage.name' => 1,
						'files' => 1,
						'type' => 1,
						'id_user' => 1,
						'id_company' => 1,
						'deleted_at' => 1
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

	public function delete(Request $r)
	{
		// $archieve = Archieve::where('_id', $r->id)->pull('share', array('_id' => GlobalClass::generateMongoObjectId(Auth::user()->_id)));

		Archieve::where('_id', $r->id)->where('share', 'elemMatch', array('_id' => GlobalClass::generateMongoObjectId(Auth::user()->_id)))->push('share.$.deleted_at', 1);
		
		$r->session()->flash('success', 'Akses berhasil dihapus');
		
		return redirect()->back();
	}
}
