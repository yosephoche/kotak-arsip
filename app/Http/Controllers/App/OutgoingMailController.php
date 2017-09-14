<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OutgoingMailController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
    public function index()
    {
    	return view('app.outgoing_mail.index');
    }
}
