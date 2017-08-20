<?php

namespace App\Http\Controllers\App;
use App\ArchieveTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ArchieveTypesController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function register()
    {
    	if (Auth::user()->status == 'member') {
    		return redirect('/');
    	}
    	return view('app.archieve_types.register');
    }

    public function store(Request $r)
    {
    	$archieve = new ArchieveTypes;
    	$archieve->id_company = Auth::user()->id_company;
    	foreach ($r->archieve as $arsip) {
    		$archieve->$arsip = 1;
    	}
    	$archieve->save();

    	return redirect(route('storage_register'));
    }
}
