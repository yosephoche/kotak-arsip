<?php namespace App\Http\Controllers\Chatbot;

use App\Archieve, App\StorageSub, App\User, App\Storage, App\Share, App\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;
use Carbon\Carbon, URL;
use thiagoalessio\TesseractOCR\TesseractOCR;
use File;
use App\UserVerification;
use App\Company;
use DB;

class ArsipController extends Controller
{
    public function latestArchieve()
    {
        //get the user
        $username = !empty($_GET['u']) ? $_GET['u'] : '';
        
        //match the username on user verification_table with users table
		$isRegisteredUser = UserVerification::where('user_telegram',$username)->first();
        $data = "";
        if ($isRegisteredUser) {
            //db.archieve.find({"id_user" : ObjectId("5a9e5184af2a4c191d277602")}).sort({$natural:1}).limit(5)
            $latestArchieve = Archieve::where('id_user',$isRegisteredUser->user_id)
                                ->orderBy('_id','DESC')
                                ->take(6)
								->get();
			if (isset($latestArchieve)) {
				$data = [
					'status'    => 'ok', 
					'data'      => $latestArchieve,
					'message'   => 'user is registered'
				];
				return response()->json($data, 200);
			}
			$data = [
				'status'    => 'error', 
				'data'      => '',
				'message'   => 'Data is not available'
			];
			return response()->json($data, 200);
		}
        //user is not registered
        $data = [
            'status'    => 'error',
            'data'      => '',
            'message'   => 'user is not registered'
        ];
        //return response spesific json
        return response()->json($data, 200);
    }

    public function findArchieve()
    {
        if (!isset($_GET['q'])) {
			return "kosong";
		}
		$q = @$_GET['q'];
		$u = @$_GET['u'];
		$isRegisteredUser = UserVerification::where('user_telegram', $u)->first();
		if ($isRegisteredUser) {
			$isUser = User::find($isRegisteredUser->user_id);
	
			if ($isUser) {
				$data['storage'] = Storage::where('id_company', $isUser->id_company)->orderBy('name')->get();
				if (@$_GET['storage'] != null && @$_GET['storage'] != '') {
					$data['storage_name'] = Storage::where('_id', GlobalClass::generateMongoObjectId($_GET['storage']))->select('name')->first();
				}
				// Archieve
				$limit = 25; // change in index too
				$data['archieve'] = Archieve::where('search','regexp','/.*'.$q.'/i')
										->where('id_user', GlobalClass::generateMongoObjectId($isUser->_id))
										->where('id_company',$isUser->id_company)
										->get();
				//return json response
				$data = [
					'status' => 'ok',
					'data'  => $data
				];
				return response()->json($data, 200);
			}
			$data = [
				'status' => 'failed',
				'data'  => ''
			];
			return response()->json($data, 200);
		}
		$data = [
			'status' => 'failed',
			'data'  => ''
		];
		return response()->json($data, 200);
    }

    public function detailArchieve($id)
    {
		//get the user id and id data of the detail
		$username = !empty($_GET['u']) ? $_GET['u'] : '';
        //find on database
		//match the username on user verification_table with users table
		$isRegisteredUser = UserVerification::where('user_telegram',$username)->first();
		$data = "";
		if ($isRegisteredUser) {
			//user is available
			//get five latest of the user
			//db.archieve.find({"id_user" : ObjectId("5a9e5184af2a4c191d277602")}).sort({$natural:1}).limit(5)
			$latestArchieve = Archieve::find($id);
			if ($latestArchieve) {
				$data = [
					'status'    => 'ok', 
					'data'      => $latestArchieve,
					'message'   => 'user is registered'
				];
				return response()->json($data, 200);
			}
			$data = [
				'status'    => 'error',
				'data'      => false,
				'message'   => 'data is not available'
			];
			return response()->json($data, 200);
		}
        //user is not registered
        $data = [
            'status'    => 'error',
            'data'      => false,
            'message'   => 'user is not registered'
        ];
        //return response spesific json
        return response()->json($data, 200);
	}

	public function getDetailInfo($id)
	{
		$detail = DB::table('users_verification')->where('user_id',GlobalClass::generateMongoObjectId($id))->first();
		if ($detail) {
			$data = [
				'status'    => 'ok', 
				'data'      => $detail,
				'message'   => 'user is registered'
			];
			return response()->json($data, 200);
		}
		$data = [
			'status'    => 'error',
			'data'      => false,
			'message'   => 'data is not available'
		];
		return response()->json($data, 200);
	}

	public function removeUserTelegram($id)
	{
		$removeUser = UserVerification::where('user_telegram', $id)->first();
		$data = '';
		if ($removeUser) {
			$removeUser->delete();
			$data = [
				'status'    => 'ok',
				'message' => 'user is succesfully delete'
			];
			return response()->json($data, 200);
		}
		$data = [
			'status'    => 'failed',
			'message' => 'user is failed to delete'
		];
		return response()->json($data, 200);
	}
	
	public function getData()
	{
		if (!isset($_GET['q'])) {
			return "kosong";
		}
		$q = @$_GET['q'];
		$u = @$_GET['u'];
        $isRegisteredUser = UserVerification::where('user_telegram', $u)->get();
		$isUser = User::find($isRegisteredUser[0]->user_id);

		//Storage
		$data['storage'] = Storage::where('id_company', $isUser->id_company)->orderBy('name')->get();

		if (@$_GET['storage'] != null && @$_GET['storage'] != '') {
			$data['storage_name'] = Storage::where('_id', GlobalClass::generateMongoObjectId($_GET['storage']))->select('name')->first();
		}

		// Archieve
		$limit = 25; // change in index too
		$data['archieve'] = Archieve::where('search','regex',new \MongoDB\BSON\Regex($q))
								->where('id_user', GlobalClass::generateMongoObjectId($isUser->_id))
								->where('id_company',$isUser->id_company)
								->get();
	}

	public function searchRegex()
	{
		$archieve = Archieve::raw(function($collection){
			$q = @$_GET['q'];
			$page  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
			$limit = 25; // change in index too
			$skip  = ($page - 1) * $limit;
			return $collection->aggregate(array(
				/* array(
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
						'search' => 1,
						'from' => 1,
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
				), */
				array(
					'$match' => array(
						'search' => array(
							'$regex' => $q,
							'$options' => 'i'
						),
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
		dd($archieve);
	}
}
