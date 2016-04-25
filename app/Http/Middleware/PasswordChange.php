<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\PasswordCahnges;
use Illuminate\Routing\Route;

class PasswordChange
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
		$password_changes = PasswordCahnges::where('user_id', Auth::user()->id)->count();
		if($password_changes < 1){
			return redirect()->route('main.edit_account', [
				'user_id' => Auth::user()->id,
			])->with('password_change', '1');;
		}
		
		return $next($request);
    }
}
