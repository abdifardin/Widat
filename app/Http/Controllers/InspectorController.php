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
		$user_id = Auth::user()->id;
		
		if(!$topic_id){			
			$ku_translation = KuTranslation::where('finished', 1)
			->join('topics', 'topics.id', '=', 'ku_translations.topic_id')
			->where('ku_translations.inspection_result', 0)
			->where('topics.user_id', '<>' , $user_id)
			->orderBy('edited_at', 'desc')
			->get();
			
			return view('inspector.home', [
				'inspections' => $ku_translation,
			]);
		}
		
		$ku_trans = KuTranslation::where('topic_id', $topic_id)->first();
		$topic = Topic::where('id', $ku_trans->topic_id)->first();
		
		if($topic->user_id == $user_id){
			abort(403, 'You can not inspect you topic.');
		}
		
		if($ku_trans AND $topic){
			if($request->has('accept')) {
				//$ku_trans->finished = 0;
				$ku_trans->inspection_result = 1;
				$ku_trans->inspector_id = $user_id;
				$ku_trans->auditing_count = $ku_trans->auditing_count+1;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
			}
			if($request->has('deny')) {
				//$ku_trans->finished = 0;
				$ku_trans->inspection_result = -1;
				$ku_trans->inspector_id = $user_id;
				$ku_trans->auditing_count = $ku_trans->auditing_count+1;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
			}
			if($request->has('save_accept')) {
				$ku_trans->topic = $request->get('inspection_ku_trans_title');
				$ku_trans->abstract = $request->get('inspection_ku_trans_abstract');
				//$ku_trans->finished = 0;
				$ku_trans->inspection_result = 1;
				$ku_trans->inspector_id = $user_id;
				$ku_trans->auditing_count = $ku_trans->auditing_count+1;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
			}
			if($request->has('save_deny')) {
				$ku_trans->topic = $request->get('inspection_ku_trans_title');
				$ku_trans->abstract = $request->get('inspection_ku_trans_abstract');
				//$ku_trans->finished = 0;
				$ku_trans->inspection_result = -1;
				$ku_trans->inspector_id = $user_id;
				$ku_trans->auditing_count = $ku_trans->auditing_count+1;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
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
		]);
	}
	
	public function rejected(Request $request)
	{
		$user_id = Auth::user()->id;
		
		$ku_translation = KuTranslation::where('finished', 1)
			->join('topics', 'topics.id', '=', 'ku_translations.topic_id')
			->where('ku_translations.inspection_result', -1)
			->where('ku_translations.inspector_id', '=' , $user_id)
			->orderBy('edited_at', 'desc')
			->get();
			
		return view('inspector.home', [
			'inspections' => $ku_translation,
		]);
	}

	public function stats($user_id)
	{
		$inspector = User::where('id', $user_id)->first();
		$last_score = ScoreHistory::where('user_id', $user_id)->orderBy('id', 'DESC')->first();

		$last_month_history = ScoreHistory::whereRaw('MONTH(created_at) = ?',
			[date('m', strtotime('-1 month'))])
			->orderBy('id', 'DESC')
			->first();

		$this_month_score = $inspector->score - ($last_month_history ? $last_month_history->score : 0);

		$inspected = KuTranslation::where('inspector_id', $user_id)
			->join('topics', 'topics.id', '=', 'ku_translations.topic_id')
			->orderBy('edited_at', 'desc')
			->get();
			
		return view('inspector.stats', [
			'inspector' => $inspector,
			'last_score' => ($last_score) ? $last_score->score : 0,
			'this_month_score' => $this_month_score,
			'inspected' => $inspected,
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
}
