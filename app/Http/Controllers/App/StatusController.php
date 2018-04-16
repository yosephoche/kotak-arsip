<?php

namespace App\Http\Controllers\App;
use Illuminate\Http\Request;
use App\User, App\UserLoginCode, App\CompanyService;
use App\Http\Controllers\Controller;
use Auth, GlobalClass, File;

class StatusController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function capacity()
	{
		// Total Capacity
		$capacity = CompanyService::where('id_company', GlobalClass::generateMongoObjectId(Auth::user()->id_company))->select('size')->first();
		$data['capacity'] = $capacity;

		// Get Size
		$type = ['incoming_mail', 'outgoing_mail', 'file'];
		$files = 0;
		for ($i=0; $i < count($type); $i++) { 
			$data['bytes_'.$type[$i]] = GlobalClass::dirSize('files/'.Auth::user()->id_company.'/'.$type[$i]);
			$data['size_'.$type[$i]] = GlobalClass::formatBytes($data['bytes_'.$type[$i]]);
			$files += GlobalClass::dirSize('files/'.Auth::user()->id_company.'/'.$type[$i]);
		}
		$file_size = GlobalClass::formatBytes($files);

		// Employee
		$employee = 0;
		foreach( File::allFiles('files/'.Auth::user()->id_company.'/employee/') as $file)
		{
			$employee += $file->getSize();
		}
		$data['size_employee'] = GlobalClass::formatBytes($employee);

		// Total Size
		$files = $files + $employee;
		$file_size = GlobalClass::formatBytes($files);
		$data['size'] = $file_size;

		// Get percentage
		@$data['percentage'] = 100 / ($capacity->size*pow(1024,3) / $files);
		@$data['percentage_employee'] = 100 / ($files / $employee);
		for ($i=0; $i < count($type); $i++) {
			@$data['percentage_'.$type[$i]] = 100 / ($files / $data['bytes_'.$type[$i]]);
		}

		return view('app.status.capacity', $data);
	}

	public function twostepauth()
	{
		// User Agent
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		// Redirect back if already confirmed
		$check = UserLoginCode::where('email', Auth::user()->email)->where('user_agent', $useragent)->where('status', 1)->first();

		if (count($check) > 0) {
			return redirect()->back();
		}

		return view('app.status.twostepauth');
	}

	public function twostepauth_update(Request $r)
	{
		// User Agent
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		$code = UserLoginCode::where('email', Auth::user()->email)->where('user_agent', $useragent)->where('code', (integer)$r->code)->where('status', 0)->first();
		if (count($code) > 0) {
			$code->status = 1;
			$code->save();

			// Remove Duplicate
			UserLoginCode::where('email', Auth::user()->email)->where('user_agent', $useragent)->where('status', 0)->delete();

			return redirect()->route('incoming_mail');
		} else {
			$r->session()->flash('error', 'Kode yang Anda masukkan salah');
			return redirect()->route('twostepauth');
		}
	
		return redirect()->route('twostepauth');
	}

	public function twostepauth_active(Request $r)
	{
		// User status twostepauth
		$user = User::find(Auth::user()->_id);
		$user->twostepauth = 1;
		$user->save();

		// User Agent
		$useragent = $r->useragent;

        // Random code
        $randomcode = rand(1111, 9999);

        // Generate new code for verification
        $count = UserLoginCode::where('user_agent', $useragent)->where('email', Auth::user()->email)->count();
        if ($count == 0) {
	        $code = new UserLoginCode;
	        $code->email = Auth::user()->email;
	        $code->code = $randomcode;
	        $code->user_agent = $useragent;
	        $code->status = 1;
	        $code->save();
        }
	
		return redirect(route('setting') . '?tab=security');
	}

	public function twostepauth_remove()
	{
		// Change status twostepauth
		$user = User::find(Auth::user()->_id);
		$user->twostepauth = 0;
		$user->save();

		// Remove all device
		$code = UserLoginCode::where('email', Auth::user()->email)->delete();
		
		return redirect(route('setting') . '?tab=security');
	}

	public function device_delete(Request $r)
	{
		UserLoginCode::where('_id', $r->id)->delete();

		$r->session()->flash('success', 'Perangkat berhasil dihapus');

		return redirect(route('setting') . '?tab=security');
	}
}
