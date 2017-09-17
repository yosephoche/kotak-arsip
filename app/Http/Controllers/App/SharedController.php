<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\StorageSub, App\User, App\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;
use Carbon\Carbon;

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

	public function getData()
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

			return $collection->aggregate(array(
				array(
					'$lookup' => array(
						'from'=>'users',
						'localField'=>'share',
						'foreignField'=>'_id',
						'as'=>'share'
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
						'fulltext' => 0,
						'updated_at' => 0,
						'storage.created_at' => 0,
						'storage.updated_at' => 0,
						'storage.type' => 0,
						'storage.id_company' => 0,
						'storagesub.created_at' => 0,
						'storagesub.updated_at' => 0,
						'storagesub.id_storage' => 0,
						'share.position' => 0,
						'share.phone' => 0,
						'share.email_status' => 0,
						'share.status' => 0,
						'share.id_company' => 0,
						'share.remember_token' => 0,
						'share.password' => 0,
						'share.created_at' => 0,
						'share.updated_at' => 0
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
						'share' => array(
							'$elemMatch' => array(
								'email' => Auth::user()->email
							)
						)
					)
				),
				array(
					'$skip' => 0
				),
				array(
					'$limit' => 10
				)
			));
		});

		$users = User::where('id_company', Auth::user()->id_company)->get();

		return response()->json([
			'incomingMail'  =>  $archieve,
			'users' => $users
		]);
	}

	public function detail($id)
	{
		$data['archieve'] = Archieve::find($id);

		return view('app.shared.detail', $data);
	}

	public function getDetail($id)
	{
		$archieve = Archieve::raw(function($collection){
			return $collection->aggregate(array(
				array(
					'$lookup' => array(
						'from'=>'users',
						'localField'=>'share',
						'foreignField'=>'_id',
						'as'=>'share'
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
						'share' => 1,
						'storagesub._id' => 1,
						'storagesub.name' => 1,
						'storage._id' => 1,
						'storage.name' => 1,
						'files' => 1,
					)
				)
			));
		})->where('_id', $id);

		$users = User::where('id_company', Auth::user()->id_company)->get();

		return response()->json([
			'incomingMail'  =>  $archieve,
			'users' => $users
		]);
	}

	public function delete(Request $r)
	{
		// $archieve = Archieve::where('_id', $r->id)->delete();

		$r->session()->flash('success', 'Akses berhasil dihapus');

		return redirect()->route('shared_incoming_mail');
	}
}
