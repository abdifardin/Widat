<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\KuTranslation;
use App\ScoreHistory;
use App\DeleteRecommendation;
use App\Topic;
use App\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

class InspectorController extends Controller
{
    public function __construct()
	{
		if(!Auth::check() || (Auth::user()->user_type != "inspector" && Auth::user()->user_type != "admin")) {
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
	
	public function inspection(Request $request, $topic_id = null)
	{
		$user = Auth::user();
		$user_id = $user->id;
		
		if(!$topic_id){
			$ku_translation = KuTranslation::where('finished', 1)
			->join('topics', 'topics.id', '=', 'ku_translations.topic_id')
			->where('ku_translations.inspection_result', 0)
			->where('topics.user_id', '<>' , $user_id)
			->orderBy('edited_at', 'desc')
			->get();
			
			return view('inspector.home', [
				'inspections' => $ku_translation,
				'current_user_id' => $user_id,
			]);
		}
		
		$ku_trans = KuTranslation::where('topic_id', $topic_id)->first();
		$topic = Topic::where('id', $ku_trans->topic_id)->first();
		
		if($topic->user_id == $user_id){
			abort(403, 'You can not inspect your topic.');
		}
		
		if($ku_trans->inspector_id != NULL AND $ku_trans->inspector_id != $user_id){
			abort(403, 'You can not access this area.');
		}
		
		if($ku_trans->inspection_result == -1){
			abort(403, 'You can not access this area.');
		}
		
		if($ku_trans AND $topic){
			if($request->has('accept')) {
				if($ku_trans->inspector_id == NULL){
					$new_score = $this->calculateTranslationScore($ku_trans->abstract) + $this->calculateTranslationScore($ku_trans->topic);
					$user->score = $user->score + $new_score;
					$user->save();
				}
				
				if($request->get('reject_reason')){
					$ku_trans->inspection_result = -1;
					$ku_trans->inspector_message = $request->get('reject_reason');
				}else{
					$ku_trans->inspection_result = 1;
					$ku_trans->inspector_message = NULL;
				}
				
				$ku_trans->inspector_id = $user_id;
				$ku_trans->auditing_count = $ku_trans->auditing_count+1;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
			}
			if($request->has('deny')) {
				/*
				if($ku_trans->inspector_id == NULL){
					$new_score = $this->calculateTranslationScore($ku_trans->abstract) + $this->calculateTranslationScore($ku_trans->topic);
					$user->score = $user->score + $new_score;
					$user->save();
				}
				
				//$ku_trans->finished = 0;
				$ku_trans->inspection_result = -1;
				$ku_trans->inspector_id = $user_id;
				$ku_trans->auditing_count = $ku_trans->auditing_count+1;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
				*/
			}
			if($request->has('save_accept')) {
				if($ku_trans->inspector_id == NULL){
					$new_score = $this->calculateTranslationScore($ku_trans->abstract) + $this->calculateTranslationScore($ku_trans->topic);
					$user->score = $user->score + $new_score;
					$user->save();
				}
				
				if($request->get('editpage_reject_reason')){
					$ku_trans->inspection_result = -1;
					$ku_trans->inspector_message = $request->get('editpage_reject_reason');
				}else{
					$ku_trans->inspection_result = 1;
					$ku_trans->inspector_message = NULL;
				}
				
				$ku_trans->topic = $request->get('inspection_ku_trans_title');
				$ku_trans->abstract = $request->get('inspection_ku_trans_abstract');
				$ku_trans->inspector_id = $user_id;
				$ku_trans->auditing_count = $ku_trans->auditing_count+1;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
			}
			if($request->has('save_deny')) {
				/*
				if($ku_trans->inspector_id == NULL){
					$new_score = $this->calculateTranslationScore($ku_trans->abstract) + $this->calculateTranslationScore($ku_trans->topic);
					$user->score = $user->score + $new_score;
					$user->save();
				}

				$ku_trans->topic = $request->get('inspection_ku_trans_title');
				$ku_trans->abstract = $request->get('inspection_ku_trans_abstract');
				//$ku_trans->finished = 0;
				$ku_trans->inspection_result = -1;
				$ku_trans->inspector_id = $user_id;
				$ku_trans->auditing_count = $ku_trans->auditing_count+1;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
				*/
			}
			
			return view('inspector.inspection', [
				'topic' => $topic,
				'ku_trans' => $ku_trans,
			]);
		}
	}
	
	public function accepted(Request $request)
	{
		$user_id = Auth::user()->id;
		
		$ku_translation = KuTranslation::where('finished', 1)
			->join('topics', 'topics.id', '=', 'ku_translations.topic_id')
			->where('ku_translations.inspection_result', 1)
			->where('ku_translations.inspector_id', '=' , $user_id)
			->orderBy('edited_at', 'desc')
			->get();
			
		return view('inspector.home', [
			'inspections' => $ku_translation,
			'current_user_id' => $user_id,
		]);
	}

	public function stats($user_id)
	{
		$inspector = User::where('id', $user_id)->first();
		$last_score = ScoreHistory::where('user_id', $user_id)->orderBy('id', 'DESC')->first();
		$current_user = Auth::user();
		$last_month_history = ScoreHistory::whereRaw('MONTH(created_at) = ? AND YEAR(created_at) = ? AND user_id = ?',
			[
				date('m', strtotime('-1 month')),
				date('Y', strtotime('-1 month')),
				$user_id
			])
			->orderBy('id', 'DESC')
			->first();
		
		$this_month_score = $inspector->score - ($last_month_history ? $last_month_history->score : 0);
		
		if($current_user->user_type == 'admin'){
			$inspected = KuTranslation::where('inspector_id', $user_id)
			->join('topics', 'topics.id', '=', 'ku_translations.topic_id')
			->orderBy('edited_at', 'desc')
			->get();
		}
		else{
			$inspected = KuTranslation::where('inspector_id', $user_id)
			->join('topics', 'topics.id', '=', 'ku_translations.topic_id')
			->where('ku_translations.inspection_result', '<>', -1)
			->orderBy('edited_at', 'desc')
			->get();
		}
		
			
		return view('inspector.stats', [
			'inspector' => $inspector,
			'last_score' => ($last_score) ? $last_score->score : 0,
			'this_month_score' => $this_month_score,
			'inspected' => $inspected,
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
}
