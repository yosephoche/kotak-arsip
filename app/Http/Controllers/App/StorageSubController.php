<?php

namespace App\Http\Controllers\App;
use App\StorageSub, App\Storage, App\Tracker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, GlobalClass;

class StorageSubController extends Controller
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
        $data['storage'] = Storage::findOrFail($id);
        return view('app.storage_sub.index', $data);
    }

    public function getData($id)
    {
        $sub = StorageSub::raw(function($collection) use ($id){

            return $collection->aggregate(array(
                array(
                    '$lookup' => array(
                        'from'=>'archieve',
                        'localField'=>'_id',
                        'foreignField'=>'storagesub',
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
                        'id_storage' => GlobalClass::generateMongoObjectId($id),
                        'count.deleted_at' => null
                    )
                ),
                array(
                    '$group' => array(
                        '_id' => '$_id',
                        'id_storage' => array(
                            '$first' => '$id_storage'
                        ),
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
                        'id_storage' => 1,
                        'name' => 1,
                        'type' => 1,
                        'count' => array(
                            '$size' => '$count._id'
                        ),
                    )
                )
            ));
        });

        return response()->json(['subStorage' => $sub]);
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

        $r->session()->flash('success', 'Sub penyimpanan arsip berhasil diperbarui');

        return redirect()->back();
    }

    public function update(Request $r)
    {
        $this->validate($r, [
            'name' => 'required'
        ]);

        $sub = StorageSub::find($r->id);
        $sub->name = $r->name;
        $sub->save();

        $r->session()->flash('success', 'Berhasil menyimpan pembaruan');

        return redirect()->back();
    }

    public function delete(Request $r)
    {
    	StorageSub::where('_id', $r->id)->delete();
        
        $r->session()->flash('success', 'Sub penyimpanan arsip berhasil dihapus');

    	return redirect()->back();
    }
}