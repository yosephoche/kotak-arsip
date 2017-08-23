<?php

namespace App\Http\Controllers\App;
use App\Archieve, App\StorageSub, App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;
use Carbon\Carbon;

class IncomingMailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	return view('app.incoming_mail.index');
    }

    public function getData()
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
    			)
    		));
    	})->where('type', 'incoming_mail')->where('id_company', Auth::user()->id_company);

    	$users = User::all();

    	return response()->json([
            'incomingMail'  =>  $archieve,
            'users' => $users
        ]);
    }

    public function detail($id)
    {
    	$data['archieve'] = Archieve::find($id);

    	return view('app.incoming_mail.detail', $data);
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
    			)
    		));
    	})->where('_id', $id);

    	$users = User::all();

    	return response()->json([
            'incomingMail'  =>  $archieve,
            'users' => $users
        ]);
    }

    public function create()
    {
    	$data['user'] = User::where('id_company', Auth::user()->id_company)->orderBy('name')->get();

    	return view('app.incoming_mail.create', $data);
    }

    public function store(Request $r)
    {
    	$this->validate($r, [
			'from'				=> 'required',
			'reference_number'	=> 'required',
			'subject'			=> 'required',
			'date'				=> 'required',
			'storage'			=> 'required'
		]);

    	if ( $r->hasFile('files') ) {
			 // Upload Image
			$destination = public_path('assets/app/img/incoming_mail');
			$files = GlobalClass::Upload($r->file('files'), $destination, 200);
		}

		//Make ObjectId
		$share = GlobalClass::arrayObjectId($r->share);

		$surat = new Archieve;
		$surat->id_user = $r->id_user;
		$surat->id_company = $r->id_company;
		$surat->type = $r->type;
		$surat->from = $r->from;
		$surat->reference_number = $r->reference_number;
		$surat->subject = $r->subject;
		$surat->date = Carbon::createFromFormat('d/m/Y', $r->date)->format('d/m/Y');
		$surat->storage = $r->storage;
		$surat->share = $share;
		$surat->information = $r->information;
		$surat->files = $files;
		$surat->save();

		Session::flash('message', "Berhasil menambahkan surat masuk baru");
		return redirect()->back();
    }

    public function update(Request $r)
    {
    	$this->validate($r, [
			'asal'	=> 'required',
			'nomor'	=> 'required',
			'perihal'	=> 'required',
			'tanggal'	=> 'required',
			'penyimpanan'	=> 'required',
		]);

		$surat = Archieve::find($r->_id);
		$surat->id_user = $r->id_user;
		$surat->id_company = $r->id_company;
		$surat->type = $r->type;
		$surat->asal = $r->asal;
		$surat->nomor = $r->nomor;
		$surat->perihal = $r->perihal;
		$surat->tanggal = Carbon::createFromFormat('d/m/Y', $r->tanggal)->format('d/m/Y');
		$surat->penyimpanan = $r->penyimpanan;
		$surat->share = $r->share;
		$surat->keterangan = $r->keterangan;
		$surat->save();

		Session::flash('message', "Berhasil menyimpan pembaruan");
		return redirect()->back();
    }

    public function delete($id)
    {
    	Archieve::where('_id', $id)->delete();
    	return redirect()->back();
    }

    public function restore(Request $r, $id)
    {
    	Archieve::withTrashed()->where('_id', $id)->restore();
    	return redirect()->back();
    }
}
