<?php

namespace App\Http\Controllers\Auth;

use App\UserLoginCode;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth, Mail;

class LoginController extends Controller
{

	use AuthenticatesUsers;

	// protected $redirectTo = '/surat/masuk';
	protected function redirectTo() {
		if (Auth::user()->twostepauth == 1) {
			// User Agent
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			
			$check = UserLoginCode::where('email', Auth::user()->email)->where('user_agent', $useragent)->where('status', 1)->count();

			if ($check == 0) {
				// Random code
				$randomcode = rand(1111, 9999);

				// Generate new code for verification
				$code = new UserLoginCode;
				$code->email = Auth::user()->email;
				$code->code = $randomcode;
				$code->user_agent = $useragent;
				$code->status = 0;
				$code->save();

				// Send Mail
				$data = [
					'fullname' => Auth::user()->name,
					'code' => $randomcode,
				];
				Mail::send('mail.getcodelogin', $data, function ($mail) use ($data)
				{
					$mail->to(Auth::user()->email);
					$mail->subject('KotakArsip - 2 Langkah Verifikasi');
				});
				
				return route('twostepauth');
			}
		}

		return route('incoming_mail');
	}

	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}
}
