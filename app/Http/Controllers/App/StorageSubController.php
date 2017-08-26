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

    public function register(Request $r)
    {
        //Get_Type_Storage_From_URL
        $data['storage'] = $r->storage;

    	return view('app.storage_sub.register', $data);
    }

    public function registerstore(Request $r)
    {
        $this->validate($r, [
            'storage_name' => 'required',
            'type' => 'required',
        ]);

        //Save_Storage
        $storage = new Storage;
        $storage->name = $r->storage_name;
        $storage->type = $r->type;
        $storage->id_company = Auth::user()->id_company;
        $storage->save();

        //Save_Sub_Storage
        $search = Storage::where('id_company', Auth::user()->id_company)->select('_id')->orderBy('_id', 'DESC')->first();
        foreach ($r->name as $name) {
            $sub = new StorageSub;
            $sub->id_storage = GlobalClass::generateMongoObjectId($search->_id);
            $sub->name = $name;
            $sub->save();
        }

        return redirect()->route('storage_register_success');
    }

    public function index($id)
    {
        $data['storage'] = Storage::find($id);
        $data['sub'] = StorageSub::where('id_storage', GlobalClass::generateMongoObjectId($id))->get();

        return view('app.storage_sub.index', $data);
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