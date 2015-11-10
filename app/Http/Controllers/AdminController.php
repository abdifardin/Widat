<?php
/**
 * Created by PhpStorm.
 * User: mnvoh
 * Date: 11/9/15
 * Time: 6:54 PM
 */

namespace App\Http\Controllers;


use App\KuTranslation;
use App\Topic;
use App\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
	public function __construct()
	{
		if(!Auth::check() || Auth::user()->user_type != "admin") {
			abort(401, "Unauthorized!");
		}
	}

	public function home()
	{
		$admins_count = User::where('user_type', 'admin')->count();
		$translators_count = User::where('user_type', 'translator')->count();
		$topics_count = Topic::count();
		$ku_translations_count = KuTranslation::count();
		return view('admin.home', [
			'admins_count' => $admins_count,
			'translators_count' => $translators_count,
			'topics_count' => $topics_count,
			'ku_translations_count' => $ku_translations_count,
		]);
	}
}