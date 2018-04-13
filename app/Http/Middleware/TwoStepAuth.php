<?php

namespace App\Http\Middleware;
use Closure;
use App\User, App\UserLoginCode, App\CompanyService;
use Hash, Auth, GlobalClass;

class TwoStepAuth
{
	public function handle($request, Closure $next)
	{
		// Check Company Service
		$company_service = CompanyService::where('id_company', GlobalClass::generateMongoObjectId(Auth::user()->id_company))->count();
		if ($company_service == 0) {
			return redirect()->route('company_select_package');
		}

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
