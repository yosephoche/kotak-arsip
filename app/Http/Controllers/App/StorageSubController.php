<?php

namespace App\Http\Controllers\App;
use App\StorageSub, App\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, GlobalClass;

class StorageSubController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $data['storage'] = Storage::find($id);
        $data['sub'] = StorageSub::where('id_storage', GlobalClass::generateMongoObjectId($id))->get();

        return view('app.storage_sub.index', $data);
    }
    
    public function register()
    {
    	$storage = Storage::where('id_company', Auth::user()->id_company)->first();
    	$data['storage'] = $storage;

    	$data['storage_sub'] = StorageSub::where('id_storage', $storage->_id)->get();
    	return view('app.storage_sub.register', $data);
    }

    public function registerstore(Request $r)
    {
    	$this->validate($r, [
            'name' => 'required',
            'id_storage' => 'required'
        ]);

        $sub = new StorageSub;
        $sub->id_storage = $r->id_storage;
        $sub->name = $r->name;
        $sub->save();

        return redirect()->back();
    }

    public function create($storage)
    {
        $data['storage'] = Storage::find($storage);

        return view('app.storage_sub.create', $data);
    }

    public function store(Request $r)
    {
        $this->validate($r, [
            'name' => 'required',
            'id_storage' => 'required'
        ]);

        $sub = new StorageSub;
        $sub->id_storage = GlobalClass::generateMongoObjectId($r->id_storage);
        $sub->name = $r->name;
        $sub->save();

        return redirect()->route('storage_sub', ['id' => $r->id_storage]);
    }

    public function edit($id)
    {
        $data['sub'] = StorageSub::find($id);

        return view('app.storage_sub.edit', $data);
    }

    public function update($id, Request $r)
    {
        $this->validate($r, [
            'name' => 'required'
        ]);

        $sub = StorageSub::find($id);
        $sub->name = $r->name;
        $sub->save();

        return redirect()->route('storage_sub', ['id' => $sub->id_storage]);
    }

    public function delete(Request $r)
    {
    	StorageSub::where('_id', $r->id)->delete();

    	return redirect()->back();
    }
}