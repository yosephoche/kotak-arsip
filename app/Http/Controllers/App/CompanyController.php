<?php

namespace App\Http\Controllers\App;
use App\Company, App\User, App\CompanyService, App\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Auth, Session, GlobalClass, Mail;

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
				$user->status = 'anggota';
				$user->save();

				return redirect()->route('incoming_mail');
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

			// Create folder file
			mkdir(public_path('files').'/'.$id_company->_id, 0777, true);
			mkdir(public_path('files').'/'.$id_company->_id.'/incoming_mail', 0777, true);
			mkdir(public_path('files').'/'.$id_company->_id.'/outgoing_mail', 0777, true);
			mkdir(public_path('files').'/'.$id_company->_id.'/files', 0777, true);
			mkdir(public_path('files').'/'.$id_company->_id.'/employee', 0777, true);

			return redirect()->route('company_select_package');
		}
	}

	public function selectPackage()
	{
		$company_service = CompanyService::where('id_company', GlobalClass::generateMongoObjectId(Auth::user()->id_company))->count();
		if ($company_service == 0) {
			return view('app.company.select-package');
		} else {
			return redirect()->route('company_payment_confirmation');
		}
	}

	public function selectPackageStore(Request $r)
	{
		$package = explode(" ", $r->package);
		$unique = rand(111,555);

		//Company Service
		$service = new CompanyService;
		$service->id_company = GlobalClass::generateMongoObjectId(Auth::user()->id_company);
		$service->service = $package[0];
		$service->size = $package[1];
		$service->registered = date('Y-m-d');
		if ($package[3] == 'month') {
			$service->expired = date('Y-m-d', strtotime('+1 month'));
		}
		if ($package[3] == 'year') {
			$service->expired = date('Y-m-d', strtotime('+1 year'));
		}
		$service->save();

		// Invoice
		$invoice = new Invoice;
		$invoice->id_company = GlobalClass::generateMongoObjectId(Auth::user()->id_company);
		$invoice->service = $package[0];
		$invoice->size = $package[1];
		$invoice->base_price = (int)$package[2];
		$invoice->price = ($package[2] * 0.10) + $package[2] + $unique;
		$invoice->registered = date('Y-m-d');
		if ($package[3] == 'month') {
			$invoice->expired = date('Y-m-d', strtotime('+1 month'));
		}
		if ($package[3] == 'year') {
			$invoice->expired = date('Y-m-d', strtotime('+1 year'));
		}
		$invoice->save();

		// Mail Invoice
		$company = Company::find(GlobalClass::generateMongoObjectId(Auth::user()->id_company));
		$data = [
			'company' => $company->name,
			'service' => $package[0],
			'size' => $package[1],
			'base_price' => $package[2],
			'price' => ($package[2] * 0.10) + $package[2] + $unique
		];

		// Send Mail
		Mail::send('mail.payment-confirmation', $data, function ($mail) use ($company)
		{
			$mail->to(Auth::user()->email);
			$mail->cc('gifa.eriyanto@gmail.com');
			$mail->subject('Konfirmasi Pembayaran - ' . $company->name);
		});

		return redirect()->route('company_payment_confirmation');
	}

	public function paymentConfirmation()
	{
		// Check if pay
		$done = Invoice::where('id_company', GlobalClass::generateMongoObjectId(Auth::user()->id_company))->where('status', 'done')->orderBy('_id', 'DESC')->first();
		if (count($done) > 0) {
			return redirect()->route('company_register_success');
		}

		// Payment Confirmation
		$company_service = CompanyService::where('id_company', GlobalClass::generateMongoObjectId(Auth::user()->id_company))->get();
		$invoice = Invoice::where('id_company', GlobalClass::generateMongoObjectId(Auth::user()->id_company))->where('status', '!=', 'done')->orderBy('_id', 'DESC')->first();
		if (count($company_service) == 0) {
			return redirect()->route('company_select_package');
		} else {
			$data['company_service'] = $company_service;
			$data['invoice'] = $invoice;
			return view('app.company.payment-confirmation', $data);
		}
	}

	public function paymentInvoiceStore(Request $r)
	{
		$invoice = Invoice::find($r->id);
		$invoice->status = 'unpaid';
		$invoice->save();

		return redirect()->route('company_payment_confirmation');
	}

	public function paymentConfirmationStore()
	{
		//Company Service Update
		$service = CompanyService::where('id_company', Auth::user()->id_company);
		$service->expire = date("Y-m-d", strtotime("+1 month"));
		$service->save();
		
		return redirect()->route('company_register_success');
	}

	public function registerSuccess()
	{
		$company = Company::where('_id', Auth::user()->id_company)->first();
		if ($company->status == null) {
			return redirect()->route('company_select_package');
		}

		$data['company'] = $company;

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
