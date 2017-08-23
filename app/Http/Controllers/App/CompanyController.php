<?php

namespace App\Http\Controllers\App;
use App\Company, App\User, App\CompanyService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['company'] = Company::where('_id', Auth::user()->id_company)->first();

        return view('app.company.index', $data);
    }

    public function update($id, Request $r)
    {
        $company = Company::find($id);
        $company->name = $r->name;
        $company->address = $r->address;
        $company->phone = $r->phone;
        $company->email = $r->email;
        $company->save();

        return redirect()->back();
    }

    public function register()
    {
        if (Auth::user()->id_company != null) {
            return redirect('/');
        }
    	return view('app.company.register');
    }

    public function store(Request $r)
    {
        $this->validate($r, [
            'name' => 'required'
        ]);

        //Company
    	$company = new Company;
    	$company->name = $r->name;
    	$company->id_user = $r->id_user;
    	$company->code = $this->code_random(6);
    	$company->save();

        //Update User
    	$id_company = Company::where('id_user', $r->id_user)->first();
    	$user = User::find($r->id_user);
    	$user->status = $r->status;
    	$user->id_company = $id_company->_id;
    	$user->save();

        //Company Srvice
        $service = new CompanyService;
        $service->id_company = $id_company->_id;
        $service->service = 'free';
        $service->size = 200;
        $service->size_used = 0;
        $service->registered = date('Y-m-d');
        $service->save();

    	return redirect(route('archieve_type_register'));
    }

    public function code(Request $r)
    {
        $this->validate($r, [
            'code' => 'required|max:6'
        ]);

        $code = Company::where('code', $r->code)->first();
        if ($code) {
            $user = User::find(Auth::user()->_id);
            $user->id_company = $code->_id;
            $user->status = 'member';
            $user->save();

            return redirect('/');
        } else {
            // Failed Message  
            $r->session()->flash('failed', 'Company code not found');
            return redirect()->back();
        }

    }

    public function code_random($length)
	{
	    $data = 'ABCDEFGHIJKLMNOPQRSTU1234567890';
	    $string = '';
	    for($i = 0; $i < $length; $i++) {
	        $pos = rand(0, strlen($data)-1);
	        $string .= $data{$pos};
	    }
	    return $string;
	}
}
