<?php

namespace App\Http\Controllers\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;
use App\Tracker;

class HelpController extends Controller
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

	public function incoming_mail()
	{
		return view('app.help.incoming_mail');
	}

	public function incoming_mail_create()
	{
		return view('app.help.incoming_mail_create');
	}

	public function incoming_mail_delete()
	{
		return view('app.help.incoming_mail_delete');
	}

	public function incoming_mail_disposition()
	{
		return view('app.help.incoming_mail_disposition');
	}

	public function outgoing_mail()
	{
		return view('app.help.outgoing_mail');
	}

	public function outgoing_mail_create()
	{
		return view('app.help.outgoing_mail_create');
	}

	public function outgoing_mail_delete()
	{
		return view('app.help.outgoing_mail_delete');
	}

	public function archives()
	{
		return view('app.help.archives');
	}

	public function search()
	{
		return view('app.help.search');
	}

	public function share()
	{
		return view('app.help.share');
	}

	public function folder()
	{
		return view('app.help.folder');
	}

	public function file()
	{
		return view('app.help.file');
	}
}
