<?php

namespace App\Http\Controllers\App;
use App\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;

class NotificationsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
	}

	public function readAll()
	{
		$notif = Notifications::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->update(['read' => 1]);

		return redirect()->back();
	}
}
