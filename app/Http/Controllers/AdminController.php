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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
	public function __construct()
	{
		if(!Auth::check() || Auth::user()->user_type != "admin") {
			abort(401, "Unauthorized!");
		}

		if(Auth::check()) {
			$user = Auth::user();
			$user->last_activity = date('Y-m-d H:i:s');
			$user->save();
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

	public function admins(Request $request)
	{
		$action_error = false;
		$action_result = null;

		if($request->has('delete')) {
			$user_id = $request->get('user_id');
			if($user_id <= 1) {
				$action_error = true;
				$action_result = trans('common.cannot_delete_user');
			}
			else {
				User::where('id', $user_id)->delete();
				$action_result = trans('common.user_deleted');
			}
		}
		else if($request->has('create')) {
			$current_email = User::where('email', $request->get('email'))->first();
			if($current_email) {
				$action_error = true;
				$action_result = trans('common.email_exists');
			}
			else {
				$new_admin = new User;
				$new_admin->email = $request->get('email');
				$new_admin->name = $request->get('name');
				$new_admin->surname = $request->get('surname');
				$new_admin->password = bcrypt($request->get('password'));
				$new_admin->user_type = 'admin';
				$new_admin->save();
				$action_result = trans('common.user_created');
			}
		}

		$admins = User::where('user_type', 'admin')->get();

		return view('admin.admins', [
			'admins' => $admins,
			'action_error' => $action_error,
			'action_result' => $action_result,
		]);
	}

	public function translators(Request $request)
	{
		$action_error = false;
		$action_result = null;

		if($request->has('delete')) {
			$user_id = $request->get('user_id');
			if($user_id <= 1) {
				$action_error = true;
				$action_result = trans('common.cannot_delete_user');
			}
			else {
				User::where('id', $user_id)->delete();
				$action_result = trans('common.user_deleted');
			}
		}
		else if($request->has('create')) {
			$current_email = User::where('email', $request->get('email'))->first();
			if($current_email) {
				$action_error = true;
				$action_result = trans('common.email_exists');
			}
			else {
				$new_admin = new User;
				$new_admin->email = $request->get('email');
				$new_admin->name = $request->get('name');
				$new_admin->surname = $request->get('surname');
				$new_admin->password = bcrypt($request->get('password'));
				$new_admin->user_type = 'translator';
				$new_admin->save();
				$action_result = trans('common.user_created');
			}
		}

		$translators = User::where('user_type', 'translator')->get();

		return view('admin.translators', [
			'translators' => $translators,
			'action_error' => $action_error,
			'action_result' => $action_result,
		]);
	}

	public function inspection(Request $request, $user_id = null)
	{
		if(!$user_id) {
			return view('admin.inspection', [
				'translators' => User::where('user_type', 'translator')->get(),
			]);
		}

		$topics = Topic::where('user_id', $user_id)->get();
		$topic_ids = array();
		foreach($topics as $t) {
			$topic_ids[] = $t->id;
		}

		$topic = Topic::where('id', $topic_ids[array_rand($topic_ids)])->first();
		$ku_trans = KuTranslation::where('topic_id', $topic->id)->first();

		return view('admin.inspection', [
			'topic' => $topic,
			'ku_trans_title' => $ku_trans && $ku_trans->topic ? $ku_trans->topic : '',
			'ku_trans_abstract' => $ku_trans && $ku_trans->abstract ? $ku_trans->abstract : '',
		]);
	}
}