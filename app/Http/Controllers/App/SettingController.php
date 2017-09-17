<?php

namespace App\Http\Controllers\App;
use App\User, App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, GlobalClass;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
    	$data['user'] = User::where('_id', Auth::user()->_id)->first();
    	$data['company'] = Company::where('_id', Auth::user()->id_company)->first();

    	return view('app.setting.index', $data);
    }

    public function updateuser(Request $r)
    {	
    	$user = User::find($r->id);
    	if ($r->name != '') {
    		$user->name = $r->name;
    	}

    	if ($r->email != '') {
            $this->validate($r, ['email' => 'required|email|unique:users,email,'.$user->_id.',_id']);
    		$user->email = $r->email;
    	}

    	if ($r->phone != '') {
    		$user->phone = $r->phone;
    	}

    	if ($r->position != '') {
    		$user->position = $r->position;
    	}

    	// Upload Image
        if ($r->hasFile('photo')) 
        {
            // Remove Old photo
            $old = User::where('_id', $r->id)->first();
            @unlink(public_path('assets/app/img/users').'/'.$old->photo);

            // Upload Image
            $destination = public_path('assets/app/img/users');
            $photo_arr = GlobalClass::Upload($r->file('photo'), $destination, 200);
            $photo = implode(',',$photo_arr);
            
            // Save to DB
            $user->photo = $photo;
        }

    	$user->save();

        $r->session()->flash('success', 'Berhasil menyimpan pembaruan');

    	return redirect(route('setting', ['tab' => 'account']));
    }

    public function updatecompany(Request $r)
    {
    	$user = Company::find($r->id);
    	if ($r->name != '') {
    		$user->name = $r->name;
    	}

    	if ($r->address != '') {
    		$user->address = $r->address;
    	}

    	if ($r->phone != '') {
    		$user->phone = $r->phone;
    	}

    	if ($r->email != '') {
    		$user->email = $r->email;
    	}
    	$user->save();

        $r->session()->flash('success', 'Berhasil menyimpan pembaruan data perusahaan');

    	return redirect(route('setting', ['tab' => 'company']));
    }
}
