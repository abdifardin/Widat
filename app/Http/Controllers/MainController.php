<?php
/**
 * Created by PhpStorm.
 * User: mnvoh
 * Date: 11/9/15
 * Time: 4:39 PM
 */

namespace App\Http\Controllers;



use App\User;
use Illuminate\Http\Request;
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


	public function editAccount(Request $request, $user_id)
	{
		if(!Auth::check()) {
			abort(401, 'You must be logged in to access this area.');
			return null;
		}

		$current_user = Auth::user();
		$user = User::where('id', $user_id)->first();

		if(!$user_id) {
			return redirect()->route('main.root');
		}

		if(!$user) {
			abort(404, 'User not found!');
			return null;
		}

		$is_current_admin 	= $current_user->user_type == 'admin';
		$is_admin 			= $user->user_type == 'admin';
		$is_editor_owner 	= $user->id == $current_user->id;
		$is_master_admin 	= $current_user->id <= 1;

		$unauthorized = (!$is_current_admin && !$is_editor_owner) ||
						($is_admin && !$is_editor_owner && !$is_master_admin);


		if($unauthorized) {
			abort(401, 'You cannot edit this account.');
			return null;
		}

		$action_error = false;
		$action_result = null;

		if($request->has('save')) {
			$password = $request->get('password', '');

			if(strlen($password) < 8) {
				$action_error = true;
				$action_result = trans('common.password_too_short');
			}
			else {
				$user->name = $request->get('name');
				$user->surname = $request->get('surname');
				$user->email = $request->get('email');
				$user->password = bcrypt($request->get('password'));
				$user->save();
				$action_result = trans('common.account_info_saved');
			}
		}

		return view('edit_account', [
			'user' 			=> $user,
			'action_result' => $action_result,
			'action_error' 	=> $action_error,
		]);
	}
}