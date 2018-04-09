<?php

namespace App\Http\Middleware;
use Closure;
use App\User, App\UserLoginCode;
use Hash, Auth;

class TwoStepAuth
{
	public function handle($request, Closure $next)
	{
		// User Agent
		if (Auth::user()->twostepauth == 1) {
			$useragent = $_SERVER['HTTP_USER_AGENT'];

			$code = UserLoginCode::where('email', Auth::user()->email)->where('user_agent', $useragent)->first();
			
			if ($code === null) {
				return redirect()->route('logout');
			}

			if ($code->status === 0) {
				return redirect()->route('twostepauth');
			}
		}

		return $next($request);
	}
}
