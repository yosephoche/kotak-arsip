<?php

namespace App\Http\Middleware;
use App\Activation;
use Closure;
use Hash;

class CheckSerial
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        // For Mac
        ob_start();
        system("ifconfig en1 | awk '/ether/{print $2}'");
        $mac = substr(ob_get_contents(), 0, 17);
        ob_clean();

		// For Windows
		// $string = exec('getmac');
		// $mac = substr($string, 0, 17);
        
		//Check amount data
		$check = Activation::all();
		if (count($check) == 0) {
			return redirect()->route('activation');
		} else {
			//Get Serial Number
			$serial = Activation::orderBy('created_at', 'desc')->first();
		}

		if(!Hash::check($mac, $serial->serial)){
			return redirect()->route('activation');
		}

		return $next($request);
	}
}
