<?php

namespace App\Http\Controllers\App;
use App\User, App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class SettingController extends Controller
{
    public function index()
    {
    	$data['user'] = User::where('_id', Auth::user()->_id)->first();
    	$data['company'] = Company::where('_id', Auth::user()->id_company)->first();

    	return view('app.setting.index', $data);
    }
}
