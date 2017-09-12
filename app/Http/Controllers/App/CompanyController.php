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

	public function register()
	{
		if (Auth::user()->id_company != null) {
			return redirect('/');
		}
		return view('app.company.register');
	}

	public function store(Request $r)
	{

		if ($r->company_code != '') {

			$this->validate($r, [
				'company_code' => 'required|max:6'
			]);

			$company_code = Company::where('code', $r->company_code)->first();
			if ($company_code) {
				$user = User::find(Auth::user()->_id);
				$user->id_company = $company_code->_id;
				$user->status = 'member';
				$user->save();

				return redirect()->route('dashboard');
			} else {
				// Failed Message  
				$r->session()->flash('failed', 'Company code not found');
				return redirect()->back();
			}

		} elseif($r->company_name != '') {
			
			$this->validate($r, [
				'company_name' => 'required'
			]);

			//Company
			$company = new Company;
			$company->name = $r->company_name;
			$company->id_user = $r->id_user;
			$company->code = $this->code_random(6);
			$company->save();

			//Update User
			$id_company = Company::where('id_user', $r->id_user)->first();
			$user = User::find($r->id_user);
			$user->status = 'admin';
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

			return redirect()->route('company_register_success');
		}
	}

	public function registerSuccess()
	{
		$data['company'] = Company::where('_id', Auth::user()->id_company)->first();

		return view('app.company.success', $data);
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
