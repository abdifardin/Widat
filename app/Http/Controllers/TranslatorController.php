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
use App\Category;
use App\Categorylinks;
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
		
		$last_month_history = ScoreHistory::whereRaw('MONTH(created_at) = ? AND YEAR(created_at) = ? AND user_id = ?',
			[
				date('m', strtotime('-1 month')),
				date('Y', strtotime('-1 month')),
				$user_id
			])
			->orderBy('id', 'DESC')
			->first();
		
		$this_month_score = $translator->score - ($last_month_history ? $last_month_history->score : 0);
		
		$translated = Topic::where('user_id', $user_id)
			->leftJoin('ku_translations', 'topics.id', '=', 'ku_translations.topic_id')
			->whereNotNull('ku_translations.abstract')
			->select("topics.*", "topics.topic", "ku_translations.finished", "ku_translations.inspection_result","ku_translations.inspector_id")
			->orderBy('edited_at', 'desc')
			//->get();
			->paginate($this->topics_per_page);

		return view('translator.stats', [
			'translator' => $translator,
			'last_score' => ($last_score) ? $last_score->score : 0,
			'this_month_score' => $this_month_score,
			'translated' => $translated,
		]);
	}

	public function scoreHistory(Request $request, $user_id)
	{
		$score_history = array();
		$user_info = User::find($user_id);
		$created_at = date_parse($user_info->created_at);
		$created_at = $created_at['year'].'-'.$created_at['month'].'-'.$created_at['day'];
		$start_date = date('Y-m-1', strtotime($created_at));
		$current_date = date('Y-m-1', time());
		
		$history_count = 12;
		$i = 12;
		$first_flag = 12;
		if($request->input('number')){
			$i = $request->input('number');
			$first_flag = $request->input('number');
			$history_count = $request->input('number');
		}
		
		for($i; $i > 0; $i--) {
			if($i == $first_flag){
				$start_date = date('Y-m-1', time());
				$prev_month = date('Y-m-1', strtotime("-" . ($history_count - $i + 1) . " months"));
				$shp = ScoreHistory::where('user_id', $user_id)
					->whereBetween('created_at', [ $prev_month, $start_date ])
					->orderBy('created_at', 'DESC')
					->first();
					
				if(!isset($shp->score)){
					$shpc = 0;
				}else{
					$shpc = $shp->score;
				}
				
				$score_history[date("F Y", strtotime('-' . ( $history_count - $i ) . ' month'))] = $user_info->score - $shpc . ':' . $user_info->score;
			}else{
				$start_date = date('Y-m-1', strtotime("-" . ($history_count - $i) . " months"));
				$end_date = date('Y-m-1', strtotime("-" . ($history_count - $i - 1) . " months"));
				$prev_month = date('Y-m-1', strtotime("-" . ($history_count - $i + 1) . " months"));
				$sh = ScoreHistory::where('user_id', $user_id)
					->whereBetween('created_at', [ $start_date, $end_date ])
					->orderBy('created_at', 'DESC')
					->first();
				
				$shp = ScoreHistory::where('user_id', $user_id)
					->whereBetween('created_at', [ $prev_month, $start_date ])
					->orderBy('created_at', 'DESC')
					->first();
				
				if(!isset($shp->score)){
					$shpc = 0;
				}else{
					$shpc = $shp->score;
				}
				
				if(isset($sh->score)){
					$score_history[date("F Y", strtotime('-' . ( $history_count - $i ) . ' month'))] = ($sh->score) - $shpc . ':' . $sh->score;
				}else{
					$score_history[date("F Y", strtotime('-' . ( $history_count - $i ) . ' month'))] = 0 . ':' . $shpc;
				}
			}
			
			if(strtotime($start_date) <= strtotime($created_at)){
				break;
			}
		}

		return view('translator.score-history', [
			'score_history' => $score_history,
			'month_num' => $first_flag,
		]);
	}

	public function topics(Request $request, $filter = null)
	{
		$filter_all = false;
		$filter_my = false;
		$filter_untranslated_changed = false;
		$filter_untranslated = false;
		$filter_changed = false;
		
		$topic_keyword = '';
		
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
			if($request->has('search') and strlen(trim($request->input('topic_keyword'))) > 1) {
				$k = trim($request->input('topic_keyword'));
				$topic_keyword = $k;
				
				$partial_keyword = str_replace(' ', '_', $k);
				
				$topics = Topic::where('topics.user_id', Auth::user()->id)
					->leftJoin('ku_translations', 'topics.id', '=', 'ku_translations.topic_id')
					->where('topics.topic', 'LIKE', "%{$partial_keyword}%")
					->orderBy('edited_at', 'desc')
					->select("topics.*", "ku_translations.finished", "ku_translations.inspection_result", "ku_translations.inspector_id")
					->paginate($this->topics_per_page);
			}
			else{
				$topics = Topic::where('topics.user_id', Auth::user()->id)
					->leftJoin('ku_translations', 'topics.id', '=', 'ku_translations.topic_id')
					->orderBy('edited_at', 'desc')
					->select("topics.*", "ku_translations.finished", "ku_translations.inspection_result", "ku_translations.inspector_id")
					->paginate($this->topics_per_page);
			}
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
			'topic_keyword' => $topic_keyword,
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
		$current_score += $this->calculateTranslationScore($ku_translation ? $ku_translation->topic : null);

		if($request->has('reserve') && !$topic->user_id) {
			$topic->user_id = $user->id;
			$topic->save();

			//$user->score = $user->score + Config::get('custom.reservation_score');
			//$user->save();
		}
		else if($request->has('unreserve') && $topic->user_id == $user->id) {
			$topic->user_id = NULL;
			$topic->edited_at = NULL;
			$topic->save();
			
			if($ku_translation) {
				//$user->score = $user->score - Config::get('custom.reservation_score') - $current_score;
				$user->score = $user->score - $current_score;
				$user->save();
				
				$ku_translation->delete();
				$ku_translation = array();
			}
			
			$draft = Draft::where('topic_id', $topic_id)->first();
			if(!empty($draft))
				$draft->delete();
				
			return redirect()->route('translator.topics', ['filter' => 'my']);
		}
		else if($request->has('save')) {
			$ku_trans_title = $request->get('ku_trans_title', '');
			$ku_trans_abstract = $request->get('ku_trans_abstract', '');

			if((mb_strlen($ku_trans_abstract) / mb_strlen($topic->abstract)) > Config::get('custom.translation_length_max')
				OR (mb_strlen($ku_trans_title) / mb_strlen($topic->topic)) > Config::get('custom.translation_length_max')){
				$msg = '<div class="alert alert-danger" role="alert">Kurdish text seems to be too long for the English text.</div>';
			}
			elseif((mb_strlen($ku_trans_abstract) / mb_strlen($topic->abstract)) < Config::get('custom.translation_length_min')
				OR (mb_strlen($ku_trans_title) / mb_strlen($topic->topic)) < Config::get('custom.translation_length_min')){
				$msg = '<div class="alert alert-danger" role="alert">Kurdish text seems to be too short for the English text.</div>';
			}
			else{
				if(!$ku_translation) {
					$ku_translation = new KuTranslation();
					$ku_translation->topic_id = $topic_id;
				}

				$new_score = $this->calculateTranslationScore($ku_trans_abstract) + $this->calculateTranslationScore($ku_trans_title);
				$delta_score = $new_score - $current_score;

				$ku_translation->topic = $ku_trans_title;
				$ku_translation->abstract = $ku_trans_abstract;
				$ku_translation->save();
				
				$topic->edited_at = time();
				$topic->save();

				$current_score = $this->calculateTranslationScore($ku_translation->topic) + $this->calculateTranslationScore($ku_translation->abstract);

				$user->score = $user->score + $delta_score;
				$user->save();
				
				$draft = Draft::where('topic_id', $topic_id)->first();
				if(!empty($draft))
					$draft->delete();
			}
		}
		else if($request->has('inspection')) {
			if($ku_translation){
				$ku_translation->finished = 1;
				//$ku_translation->inspector_id = NULL;
				$ku_translation->inspection_result = 0;
				$ku_translation->inspection_time = time();
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
		$inspector_message = '';
		if($ku_translation){
			$is_translated = TRUE;
			$translation_status = '';
			if($ku_translation->finished == 1 AND $ku_translation->inspection_result == 1)
				$translation_status = 'accepted';
			elseif($ku_translation->finished == 1 AND $ku_translation->inspection_result == -1){
				$translation_status = 'denied';
				$inspector_message = $ku_translation->inspector_message;
			}
			elseif($ku_translation->finished == 1 AND $ku_translation->inspection_result == 0)
				$translation_status = 'wait';
		}
		$draft_available = FALSE;
		$draft_time = '';
		if(!empty($topicdraft = Draft::where('topic_id', $topic_id)->first())){
			$draft_available = TRUE;
			$draft_time = $topicdraft->last_update;
		}
		
			
		return view('translator.translate', [
			'topic' => $topic,
			'is_owner' => $is_owner,
			'is_translated' => $is_translated,
			'translation_status' => $translation_status,
			'inspector_message' => $inspector_message,
			'ku_translation_title' => ($ku_translation && $ku_translation->topic) ? $ku_translation->topic : '',
			'ku_translation_abstract' => ($ku_translation && $ku_translation->abstract) ? $ku_translation->abstract : '',
			'current_score' => $current_score,
			'draft_available' => $draft_available,
			'draft_time' => $draft_time,
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
			if (mb_strlen(trim($words[$i])) > 0) {
				$word_count++;
			}
		}

		return $word_count;
	}
	
	public function categorization(Request $request)
	{
		$data['category_list'] = array();
		$data['topics_list'] = array();
		$data['cat_keyword'] = '';
		if($request->has('search') and strlen(trim($request->input('cat_keyword'))) > 1) {
			$k = $request->input('cat_keyword');
			$data['cat_keyword'] = $k;
			
			if($request->has('firstchar')){
				$first_chat = $request->input('firstchar');
				$data['category_list'] = Categorylinks::where('cl_to', 'like', "$first_chat%")->where('cl_to', 'like', "%$k%")->where('cl_type', '<>', 'file')->groupBy('cl_to')->get();
			}else{
				$data['category_list'] = Categorylinks::where('cl_to', 'like', "%$k%")->where('cl_type', '<>', 'file')->groupBy('cl_to')->get();
			}
		}
		elseif($request->has('search_selected')) {
			foreach($request->input('cats_selected') as $c){
				$data['topics_list'] = Topic::whereNull('user_id')
					->Join('categorylinks', 'topics.id', '=', 'categorylinks.cl_from')
					->where('categorylinks.cl_to', $c)
					->select("*")
					->get();					
			}
		}
		
		return view('translator.categorization', $data);
	}
}