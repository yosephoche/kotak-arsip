<?php

namespace App\Http\Controllers\App;
use App\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class StorageController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function index()
	{
		return view('app.storage.index');
	}

	public function getData()
	{
		$storage = Storage::raw(function($collection){

			return $collection->aggregate(array(
				array(
					'$lookup' => array(
						'from'=>'archieve',
						'localField'=>'_id',
						'foreignField'=>'storage',
						'as'=>'count'
					)
				),
				array(
					'$unwind' => array(
						'path' => '$count',
						'preserveNullAndEmptyArrays' => true
					)
				),
				array(
					'$match' => array(
						'id_company' => Auth::user()->id_company,
						'count.deleted_at' => null
					)
				),
				array(
					'$group' => array(
						'_id' => '$_id',
						'name' => array(
							'$first' => '$name'
						),
						'type' => array(
							'$first' => '$type'
						),
						'count' => array(
							'$push' => array(
								'_id' => '$count._id',
							)
						)
					)
				),
				array(
					'$project' => array(
						'_id' => 1,
						'name' => 1,
						'type' => 1,
						'count' => array(
							'$size' => '$count._id'
						),
					)
				)
			));
		});

		return response()->json(['storage' => $storage]);
	}

	public function register()
	{
		return view('app.storage.register');
	}

	public function success()
	{
		return view('app.storage.success');
	}

	public function store(Request $r)
	{
		$this->validate($r, [
			'name' => 'required',
			'type' => 'required'
		]);

		//Storage Store
		$storage = new Storage;
		$storage->name = $r->name;
		$storage->type = $r->type;
		$storage->id_company = Auth::user()->id_company;
		$storage->save();

		$r->session()->flash('success', 'Penyimpanan arsip baru berhasil didisposisi');

		return redirect()->back();
	}

	public function edit($id)
	{
		$data['storage'] = Storage::find($id);

		return view('app.storage.edit', $data);
	}

	public function update(Request $r)
	{
		$this->validate($r, [
			'name' => 'required',
			'type' => 'required'
		]);

		//Storage Update
		$storage = Storage::find($r->id);
		$storage->name = $r->name;
		$storage->type = $r->type;
		$storage->id_company = Auth::user()->id_company;
		$storage->save();

		$r->session()->flash('success', 'Berhasil menyimpan pembaruan');

		return redirect()->back();
	}

	public function delete(Request $r)
	{
		Storage::where('_id', $r->id)->delete();

		$r->session()->flash('success', 'Penyimpanan arsip berhasil dihapus');

		return redirect()->back();
	}
}
