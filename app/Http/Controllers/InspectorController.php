<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\KuTranslation;
use App\Topic;
use App\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

class InspectorController extends Controller
{
    public function __construct()
	{
		if(!Auth::check() || Auth::user()->user_type != "inspector") {
			abort(401, "Unauthorized!");
		}

		if(Auth::check()) {
			$user = Auth::user();
			$user->last_activity = date('Y-m-d H:i:s');
			$user->save();
		}
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
				$ku_trans->finished = 0;
				$ku_trans->inspection_result = 1;
				$ku_trans->inspector_id = $user_id;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
			}
			if($request->has('deny')) {
				$ku_trans->finished = 0;
				$ku_trans->inspection_result = 0;
				$ku_trans->inspector_id = $user_id;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
			}
			if($request->has('save_accept')) {
				$ku_trans->topic = $request->get('inspection_ku_trans_title');
				$ku_trans->abstract = $request->get('inspection_ku_trans_abstract');
				$ku_trans->finished = 0;
				$ku_trans->inspection_result = 1;
				$ku_trans->inspector_id = $user_id;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
			}
			if($request->has('save_deny')) {
				$ku_trans->topic = $request->get('inspection_ku_trans_title');
				$ku_trans->abstract = $request->get('inspection_ku_trans_abstract');
				$ku_trans->finished = 0;
				$ku_trans->inspection_result = 0;
				$ku_trans->inspector_id = $user_id;
				$ku_trans->save();
				
				return redirect()->route('inspector.inspection');
			}
			
			return view('inspector.inspection', [
				'topic' => $topic,
				'ku_trans' => $ku_trans,
			]);
		}
	}	
}
