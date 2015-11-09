<?php
/**
 * Created by PhpStorm.
 * User: mnvoh
 * Date: 11/9/15
 * Time: 4:39 PM
 */

namespace App\Http\Controllers;



use Illuminate\Support\Facades\Auth;

/**
 * Class MainController
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
	/**
	 * Handles the '/' path. If any user is authenticated redirects to
	 * his/her corresponding home page. Otherwise redirects to the login page.
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function index()
	{
		if(Auth::check()) { // user is authenticated.
			$user = Auth::user();
			if($user->user_type == 'admin') {
				return redirect()->route('admin.home');
			}
			else {
				return redirect()->route('translator.home');
			}
		}
		else { // User is not authenticated
			return redirect()->route('auth.login');
		}
	}
}