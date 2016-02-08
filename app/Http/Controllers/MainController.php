<?php
/**
 * Created by PhpStorm.
 * User: mnvoh
 * Date: 11/9/15
 * Time: 4:39 PM
 */

namespace App\Http\Controllers;



use App\Helpers\Utilities;
use App\KuTranslation;
use App\Topic;
use App\User;
use App\DeleteRecommendation;
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
	 
	public function __construct()
	{
		view()->share('delete_recommendations_num', 
			DeleteRecommendation::where('viewed', 0)
			->select('delete_recommendations.id', 'topics.topic')
			->join('topics', 'delete_recommendations.topic_id', '=', 'topics.id')
			//->whereNull('topics.deleted_at')
			->count()
		);
	}
	
	public function index()
	{
		if(Auth::check()) { // user is authenticated.
			$user = Auth::user();
			if($user->user_type == 'admin') {
				return redirect()->route('admin.home');
			}
			elseif($user->user_type == 'inspector') {
				return redirect()->route('inspector.home');
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

	public function suggestions(Request $request)
	{
		if(!Auth::check()) {
			abort(401, 'You must be logged in to access this area.');
			return null;
		}

		$partial_topic = str_replace(' ', '_', trim($request->get('topic', '')));
		$topics = Topic::where('topic', 'LIKE', "%{$partial_topic}%")
			->take(50)->get();
		$topics_array = [];
		foreach($topics as $topic) {
			$topics_array[] = $topic->topic;
		}

		return response()->json([
			'topics' => $topics_array,
		]);
	}

	public function peek(Request $request)
	{
		if(!Auth::check()) {
			abort(401, 'You must be logged in to access this area.');
			return null;
		}
		$current_user = Auth::user();
		$topic = str_replace(' ', '_', trim($request->get('topic', '')));
		if(!strlen($topic))
			$topic = null;
		Utilities::updateTopicFromWikipedia($topic);
		$topic = Topic::where('topic', $topic)->first();
		
		if(!$topic) {
			return response()->json([
				'error' => true,
			]);
		}

		$ku_trans = KuTranslation::where('topic_id', $topic->id)->first();
		$ku_title = null;
		$ku_abstract = null;
		if($ku_trans) {
			$ku_title = $ku_trans->topic;
			$ku_abstract = $ku_trans->abstract;
		}
		
		$delete_recomend = '';
		if($topic->user_id === NULL AND $topic->delete_recommended == 0){
			$delete_recomend = '<a href="'. route('translator.delete_recommendation', ['topic_id' => $topic->id]). '" class="deletion-rec btn btn-warning" style="margin-left: 4px;">Recommend for Deletion</a>';
		}
		
		$refering_to_topic = '';
		if($topic->user_id === NULL OR $topic->user_id == $current_user->id){
			$refering_to_topic = url('/').'/translate/'.$topic->id;
		}

		return response()->json([
			'error' => false,
			'translate_url' => route('translator.translate', ['topic_id' => $topic->id]),
			'topic' => $topic->topic,
			'abstract' => $topic->abstract,
			'abstract_len' => strlen($topic->abstract),
			'ku_topic' => $ku_title,
			'ku_abstract' => $ku_abstract,
			'delete_recomend' => $delete_recomend,
			'refering_to_topic' => $refering_to_topic,
		]);
	}
}