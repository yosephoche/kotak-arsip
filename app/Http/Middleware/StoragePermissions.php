<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class StoragePermissions
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
        $status = Auth::user()->status;

        if ($status == 'anggota') {
            return redirect()->back();
        } 

        return $next($request);
    }
}
