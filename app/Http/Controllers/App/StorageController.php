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
        $data['storage'] = Storage::where('id_company', Auth::user()->id_company)->get();
        return view('app.storage.index', $data);
    }

    public function register()
    {
        return view('app.storage.register');
    }

    public function registerstore(Request $r)
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

        return redirect(route('storage_sub_register'));
    }

    public function create()
    {
        return view('app.storage.create');
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

        return redirect(route('storage'));
    }

    public function edit($id)
    {
        $data['storage'] = Storage::find($id);

        return view('app.storage.edit', $data);
    }

    public function update(Request $r, $id)
    {
        $this->validate($r, [
            'name' => 'required',
            'type' => 'required'
        ]);

        //Storage Update
        $storage = Storage::find($id);
        $storage->name = $r->name;
        $storage->type = $r->type;
        $storage->id_company = Auth::user()->id_company;
        $storage->save();

        return redirect()->route('storage');
    }

    public function delete(Request $r)
    {
        Storage::where('_id', $r->id)->delete();

        return redirect()->back();
    }
}
