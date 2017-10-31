<?php

namespace App\Http\Controllers;
use App\Activation;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    public function index()
    {
    	return view('activation.index');
    }

    public function store(Request $r)
    {
    	$serial = new Activation;
    	$serial->serial = bcrypt($r->serial);
    	$serial->save();

    	return redirect()->route('incoming_mail');
    }
}
