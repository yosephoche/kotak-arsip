<?php

namespace App\Http\Controllers\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;

class HelpController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		return view('app.help.index');
	}

	public function ocr()
	{
		return view('app.help.ocr');
	}

	public function server()
	{
		return view('app.help.server');
	}

	public function storage()
	{
		return view('app.help.storage');
	}
}
