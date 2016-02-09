<?php
/**
 * Created by PhpStorm.
 * User: mnvoh
 * Date: 11/9/15
 * Time: 6:54 PM
 */

namespace App\Http\Controllers;


use App\Helpers\Utilities;
use App\KuTranslation;
use App\Nocando;
use App\ScoreHistory;
use App\Topic;
use App\User;
use App\DeleteRecommendation;
use App\Draft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class TranslatorController extends Controller
{
	private $topics_per_page = 24;

	public function __construct()
	{
		if(!Auth::check()) {
			abort(401, "Unauthorized!");
		}

		if(Auth::check()) {
			$user = Auth::user();
			$user->last_activity = date('Y-m-d H:i:s');
			$user->save();
		}
		
		view()->share('delete_recommendations_num', 
			DeleteRecommendation::where('viewed', 0)
			->select('delete_recommendations.topic_id', 'topics.topic')
			->join('topics', 'delete_recommendations.topic_id', '=', 'topics.id')
			//->whereNull('topics.deleted_at')
			->count()
		);
	}

	public function home()
	{
		return redirect()->route('translator.stats', [
			'user_id' => Auth::user()->id,
		]);
	}

	public function stats($user_id)
	{
		$translator = User::where('id', $user_id)->first();
		$last_score = ScoreHistory::where('user_id', $user_id)->orderBy('id', 'DESC')->first();

		$last_month_history = ScoreHistory::whereRaw('MONTH(created_at) = ?',
			[date('m', strtotime('-1 month'))])
			->orderBy('id', 'DESC')
			->first();

		$this_month_score = $translator->score - ($last_month_history ? $last_month_history->score : 0);

		$translated = Topic::where('user_id', $user_id)
			->leftJoin('ku_translations', 'topics.id', '=', 'ku_translations.topic_id')
			->whereNotNull('ku_translations.abstract')
			->select("topics.*", "topics.topic", "ku_translations.finished", "ku_translations.inspection_result","ku_translations.inspector_id")
			->orderBy('edited_at', 'desc')
			->get();

		return view('translator.stats', [
			'translator' => $translator,
			'last_score' => ($last_score) ? $last_score->score : 0,
			'this_month_score' => $this_month_score,
			'translated' => $translated,
		]);
	}

	public function scoreHistory($user_id)
	{
		$score_history = array();
		$history_count = 12;

		for($i = 1; $i <= $history_count; $i++) {
			$start_date = date('Y-m-1', strtotime("-" . ($history_count - $i) . " months"));
			$end_date = date('Y-m-1', strtotime("-" . ($history_count - $i - 1) . " months"));
			$sh = ScoreHistory::where('user_id', $user_id)
				->whereBetween('created_at', [ $start_date, $end_date ])
				->orderBy('created_at', 'DESC')
				->first();
			$score_history[date("F Y", strtotime('-' . ( $history_count - $i ) . ' month'))] = isset($sh->score) ?
				$sh->score : 0;
		}

		return view('translator.score-history', [
			'score_history' => $score_history,
		]);
	}

	public function topics(Request $request, $filter = null)
	{
		$filter_all = false;
		$filter_my = false;
		$filter_untranslated_changed = false;
		$filter_untranslated = false;
		$filter_changed = false;

		switch($filter) {
			case 'all':
				$filter_all = true;
				break;
			case 'my':
				$filter_my = true;
				break;
			case 'untranslated':
				$filter_untranslated = true;
				break;
			case 'changed':
				$filter_changed = true;
				break;
			default:
				$filter_untranslated_changed = true;
				break;
		}

		if($filter_all) {
			$topics = Topic::paginate($this->topics_per_page);
		}
		else if($filter_my) {
			$topics = Topic::where('topics.user_id', Auth::user()->id)
				->leftJoin('ku_translations', 'topics.id', '=', 'ku_translations.topic_id')
				->orderBy('edited_at', 'desc')
				->select("topics.*", "ku_translations.finished", "ku_translations.inspection_result", "ku_translations.inspector_id")
				->paginate($this->topics_per_page);
		}
		else if($filter_untranslated) {
			$topics = Topic::where('topics.user_id', null)
				->paginate($this->topics_per_page);
		}
		else if($filter_changed) {
			$topics = Topic::where('topics.got_updated', 1)
				->paginate($this->topics_per_page);
		}
		else {
			$user_id = Auth::user()->id;
			$topics = Topic::where(function($query) use ($user_id) {
				$query->where('topics.user_id', null);
				$query->orWhere('topics.got_updated');
				$query->where('topics.user_id', '<>', $user_id);
			})
			->paginate($this->topics_per_page);
		}

		return view('translator.topics', [
			'topics' => $topics,
			'filter_all' => $filter_all,
			'filter_my' => $filter_my,
			'filter_untranslated' => $filter_untranslated,
			'filter_changed' => $filter_changed,
			'filter_untranslated_changed' => $filter_untranslated_changed,
		]);
	}

	public function translate(Request $request, $topic_id)
	{
		$msg = '';
		$topic = Topic::where('id', $topic_id)
			->firstOrFail();

		$user = Auth::user();

		if($topic->user_id != null && $topic->user_id != $user->id) {
			abort(403, 'This translation has been reserved by ' . $topic->user->name . ' ' . $topic->user->surname .
				'.');
		}

		if(strlen($topic->abstract) < 200) {
			Utilities::updateTopicFromWikipedia($topic->topic);
			$topic = Topic::where('id', $topic_id)->first();
		}

		$ku_translation = KuTranslation::where('topic_id', $topic_id)->first();
		$current_score = $this->calculateTranslationScore($ku_translation ? $ku_translation->abstract : null);


		if($request->has('reserve') && !$topic->user_id) {
			$topic->user_id = $user->id;
			$topic->save();

			$user->score = $user->score + Config::get('custom.reservation_score');
			$user->save();
		}
		else if($request->has('unreserve') && $topic->user_id == $user->id) {
			$topic->user_id = NULL;
			$topic->save();
			
			if($ku_translation) {
				$user->score = $user->score - Config::get('custom.reservation_score') - $current_score;
				$ku_translation->delete();
				$ku_translation = array();
			}
			else {
				$user->score = $user->score - Config::get('custom.reservation_score');
			}
			$user->save();
		}
		else if($request->has('save')) {
			$ku_trans_title = $request->get('ku_trans_title', '');
			$ku_trans_abstract = $request->get('ku_trans_abstract', '');

			if(!$ku_translation) {
				$ku_translation = new KuTranslation();
				$ku_translation->topic_id = $topic_id;
			}

			$new_score = $this->calculateTranslationScore($ku_trans_abstract);
			$delta_score = $new_score - $current_score;

			$ku_translation->topic = $ku_trans_title;
			$ku_translation->abstract = $ku_trans_abstract;
			$ku_translation->save();
			
			$topic->edited_at = time();
			$topic->save();

			$current_score = $this->calculateTranslationScore($ku_translation->abstract);

			$user->score = $user->score + $delta_score;
			$user->save();
			
			$draft = Draft::where('topic_id', $topic_id)->first();
			if(!empty($draft))
				$draft->delete();
		}
		else if($request->has('inspection')) {
			if($ku_translation){
				$ku_translation->finished = 1;
				$ku_translation->inspector_id = NULL;
				$ku_translation->inspection_result = 0;
				$ku_translation->save();
				
				return redirect()->route('translator.topics', ['filter' => 'my']);
			}
			
			$msg = '<div class="alert alert-danger" role="alert">You must translate topic and save it first.</div>';
		}
		else if($request->has('autosave')) {
			$draft = Draft::where('topic_id', $topic_id)->first();
			if(empty($draft)){
				$newdraft = new Draft();
				$newdraft->topic_id = $topic_id;
				$newdraft->topic = $request->input('ku_trans_topic');
				$newdraft->abstract = $request->input('ku_trans_abstract');
				$newdraft->last_update = time();
				$newdraft->save();
			}else{
				$draft->topic = $request->input('ku_trans_topic');
				$draft->abstract = $request->input('ku_trans_abstract');
				$draft->last_update = time();
				$draft->save();
			}
			
			return response()->json('suc');
		}
		else if($request->has('retrieve')) {
			$draft = Draft::where('topic_id', $topic_id)->first();
			if(empty($draft)){
				return response()->json('empty');
			}else{
				return response()->json(['topic' => $draft->topic, 'abstract' => $draft->abstract]);
			}
		}
		
		$is_owner = $topic->user_id == Auth::user()->id;
		$is_translated = FALSE;
		$translation_status = '';
		if($ku_translation){
			$is_translated = TRUE;
			$translation_status = '';
			if($ku_translation->finished == 1 AND $ku_translation->inspection_result == 1)
				$translation_status = 'accepted';
			elseif($ku_translation->finished == 1 AND $ku_translation->inspection_result == -1)
				$translation_status = 'denied';
			elseif($ku_translation->finished == 1 AND $ku_translation->inspection_result == 0)
				$translation_status = 'wait';
		}
		$draft_available = FALSE;
		if(!empty(Draft::where('topic_id', $topic_id)->first())){
			$draft_available = TRUE;
		}
		
			
		return view('translator.translate', [
			'topic' => $topic,
			'is_owner' => $is_owner,
			'is_translated' => $is_translated,
			'translation_status' => $translation_status,
			'ku_translation_title' => ($ku_translation && $ku_translation->topic) ? $ku_translation->topic : '',
			'ku_translation_abstract' => ($ku_translation && $ku_translation->abstract) ? $ku_translation->abstract : '',
			'current_score' => $current_score,
			'draft_available' => $draft_available,
			'msg' => $msg,
		]);
	}
	
	public function deleteRecommendation(Request $request, $topic_id)
	{
		$topic = Topic::where('id', $topic_id)
			->firstOrFail();

		$user = Auth::user();

		if($topic->user_id !== NULL OR $topic->delete_recommended != 0) {
			abort(403, 'Access Denied');
		}
		
		if($request->has('delete')) {
			$delete_recommendation_reason = $request->get('delete_recommendation_reason', '');
			
			if(strlen($delete_recommendation_reason) < 5) {
				abort(403, 'Enter reason.');
			}
		
			$delete_recommendations = new DeleteRecommendation();
			
			$delete_recommendations->topic_id = $topic_id;
			$delete_recommendations->user_id = $user->id;
			$delete_recommendations->reason = $delete_recommendation_reason;
			$delete_recommendations->save();
			
			$topic->delete_recommended = 1;
			$topic->save();
			$topic->delete(); //Soft delete
						
			return redirect()->route('translator.topics');
		}
		
		$data['topic'] = $topic;
		return view('translator.delete-recommendation', $data);
	}

	public function registerKeystroke()
	{
		$uid = Auth::user()->id;

		User::where('id', $uid)->update([
			'last_activity' => date('Y-m-d H:i:s'),
			'last_keystroke_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function registerActivity()
	{
		$uid = Auth::user()->id;

		User::where('id', $uid)->update([
			'last_activity' => date('Y-m-d H:i:s'),
		]);
	}

	public function getStatuses()
	{
		$translators = User::where('user_type', 'translator')->get();
		$online_status = array();

		foreach($translators as $t) {
			$temp = array();
			$temp['id'] = $t->id;

			if(strtotime($t->last_activity) > strtotime("-10 seconds")) {
				$temp['online'] = 'online';
			}
			else {
				$temp['online'] = 'offline';
			}

			if(strtotime($t->last_keystroke_at) > strtotime("-3 seconds")) {
				$temp['typing'] = 'typing';
			}
			else {
				$temp['typing'] = 'not typing';
			}
			$online_status[] = $temp;
		}

		return response()->json($online_status);
	}

	public function getNewCsrf()
	{
		if(!Auth::check()) {
			return;
		}

		return response()->json(['csrf' => csrf_token()]);
	}

	private function calculateTranslationScore($translation)
	{
		if(!$translation) {
			return 0;
		}
		$words = explode(' ', $translation);
		$word_count = 0;
		for($i = 0; $i < count($words); $i++) {
			if (mb_strlen(trim($words[$i])) > 1) {
				$word_count++;
			}
		}

		return $word_count;
	}
}