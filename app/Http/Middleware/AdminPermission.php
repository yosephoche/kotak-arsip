<?php

namespace App\Http\Middleware;
use Closure;
use App\User;
use Hash, Auth;

class AdminPermission
{
	public function handle($request, Closure $next)
	{
		// Get status from session auth
        $status = Auth::user()->status;
		
		// Statement
		if ($status != 'admin') {
			abort(404);
		} else {
        	return $next($request);
		}
	}
}
