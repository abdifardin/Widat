<?php
/**
 * Created by PhpStorm.
 * User: mnvoh
 * Date: 11/9/15
 * Time: 4:39 PM
 */

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
	public function index(Request $request)
	{
		if(Auth::check()) {

		}
		else {
			return redirect()->route('auth.login');
		}
	}
}