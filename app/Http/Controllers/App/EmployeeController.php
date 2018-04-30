<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\User, App\Share, App\Notifications, App\Storage, App\StorageSub, App\Tracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;
use Carbon\Carbon, URL;

class EmployeeController extends Controller
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
		return view('app.employee.index');
	}

	public function getData()
	{
		$member = User::where('id_company', Auth::user()->id_company)->get();

		return response()->json(['users' => $member]);
	}

	public function files($id)
	{
		// Get Data User
		$data['user'] = User::where('_id', GlobalClass::generateMongoObjectId($id))->first();

		//Storage
		$data['storage'] = Storage::where('id_company', Auth::user()->id_company)->orderBy('name')->get();

		return view('app.employee.files', $data);
	}

	public function getDataEmployee($id)
	{
		$employee = Archieve::raw(function($collection){
			return $collection->aggregate(array(
				array(
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
					'$project' => array(
						'name' => 1,
						'date' => 1,
						'desc' => 1,
						'files' => 1,
						'type' => 1,
						'id_user' => 1,
						'id_employee' => 1,
						'id_company' => 1,
						'storage.name' => 1,
						'storagesub.name' => 1,
						'deleted_at' => 1
					)
				),
				array(
					'$group' => array(
						'_id' => '$_id',
						'id_user' => array(
							'$first' => '$id_user'
						),
						'id_employee' => array(
							'$first' => '$id_employee'
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
						'files' => array(
							'$first' => '$files'
						),
						'deleted_at' => array(
							'$first' => '$deleted_at'
						),
						'storage' => array(
							'$first' => '$storage'
						),
						'storagesub' => array(
							'$first' => '$storagesub'
						)
					)
				)
			));
		})->where('id_employee', GlobalClass::generateMongoObjectId($id))->where('deleted_at', null);

		return response()->json(['employee' => $employee]);
	}

	public function dropdownAjax()
	{
		$storage_id = Input::get('storage_id');

		$storage = StorageSub::where('id_storage', '=', GlobalClass::generateMongoObjectId($storage_id))->orderBy('name')->get();
		return response()->json($storage);
	}

	public function detail($id)
	{
		$data['archieve'] = Archieve::findOrFail($id);
		
		//Storage
		$data['storage'] = Storage::where('id_company', Auth::user()->id_company)->orderBy('name')->get();

		return view('app.employee.detail', $data);
	}

	public function getDetail($id)
	{
		$archieve = Archieve::raw(function($collection){
			return $collection->aggregate(array(
				array(
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
					'$project' => array(
						'name' => 1,
						'date' => 1,
						'desc' => 1,
						'files' => 1,
						'type' => 1,
						'id_user' => 1,
						'id_company' => 1,
						'storage.name' => 1,
						'storagesub.name' => 1,
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
						'files' => array(
							'$first' => '$files'
						),
						'storage' => array(
							'$first' => '$storage'
						),
						'storagesub' => array(
							'$first' => '$storagesub'
						),
						'deleted_at' => array(
							'$first' => '$deleted_at'
						)
					)
				)
			));
		})->where('_id', $id);

		return response()->json([
			'files'  =>  $archieve
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
		$file->type = "employee";
		$file->id_employee = GlobalClass::generateMongoObjectId($r->id_employee);
		$file->name = $r->name;
		$file->search = $r->name;
		$file->desc = $r->desc;
		$file->fulltext = $r->name;
		$file->date = GlobalClass::generateIsoDate($date);
		$file->storage = GlobalClass::generateMongoObjectId($r->storage);
		if ($r->storagesub != '') {
			$file->storagesub = GlobalClass::generateMongoObjectId($r->storagesub);
		}
		$file->folder = $r->folder;

		// Upload Image
		@mkdir(public_path('files').'/'.$id_company.'/employee/'.$r->id_employee, 0777, true);
		$destination = public_path('files/'.$id_company.'/employee/'.$r->id_employee);
		$file_arr = GlobalClass::UploadFile($r->file('file'), $destination);
		$files = implode(',',$file_arr);
		$files = array($files);

		$file->files = $files;
		$file->save();

		$r->session()->flash('success', 'Berkas baru berhasil ditambahkan');

		return redirect()->route('employee_files', ['id' => $r->id_employee]);
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
		$file->storage = GlobalClass::generateMongoObjectId($r->storage);
		if ($r->storagesub != '') {
			$file->storagesub = GlobalClass::generateMongoObjectId($r->storagesub);
		}
		$file->save();

		$r->session()->flash('success', 'Berhasil menyimpan pembaruan');

		return redirect()->route('employee');
	}

	public function delete(Request $r)
	{
		// Get data
		$user = Archieve::where('_id', $r->id)->select('id_employee', 'id_company', 'type', 'files')->first();
		
		// Remove data
		$archieve = Archieve::where('_id', $r->id)->forceDelete();
		
		// remove image
		unlink(public_path('files').'/'.$user->id_company.'/'.$user->type.'/'.$user->id_employee.'/'.$user->files[0]);

		$r->session()->flash('success', 'Berkas berhasil dihapus');

		return redirect()->route('employee_files', ['id' => (string)$user->id_employee]);
	}
}
