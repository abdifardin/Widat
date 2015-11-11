<?php
/**
 * Created by PhpStorm.
 * User: mnvoh
 * Date: 11/9/15
 * Time: 6:54 PM
 */

namespace App\Http\Controllers;


use App\KuTranslation;
use App\ScoreHistory;
use App\Topic;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

		$no_can_dos = $translator->nocandos->count();

		$translated = Topic::where('id', $user_id)->count();

		return view('translator.stats', [
			'translator' => $translator,
			'last_score' => ($last_score) ? $last_score->score : 0,
			'this_month_score' => $this_month_score,
			'no_can_dos' => $no_can_dos,
			'translated' => $translated,
		]);

//		return view('translator.stats', [
//			'translator' => $translator,
//			'last_score' => 0,
//			'this_month_score' => 0,
//			'no_can_dos' => 0,
//			'translated' => 0,
//		]);
	}

	public function scoreHistory($user_id)
	{
		$score_history = array();
		$history_count = 12;

		for($i = 1; $i <= $history_count; $i++) {
			$start_date = date('Y-m-1', strtotime("-" . ($history_count - $i) . " months"));
			$end_date = date('Y-m-1', strtotime("-" . ($history_count - $i - 1) . " months"));
			$sh = ScoreHistory::whereBetween('created_at', [ $start_date, $end_date ])
				->orderBy('created_at', 'DESC')
				->first();
			$score_history[date("F Y", strtotime('-' . ( $history_count - $i ) . ' month'))] = isset($sh->score) ?
				$sh->score : 0;
		}

		return view('translator.score-history', [
			'score_history' => $score_history,
		]);
	}

	public function topics()
	{
		$topics = Topic::where('topics.user_id', null)
			->leftJoin('nocandos', 'nocandos.topic_id', '=', 'topics.id')
			->where('reason', null)
			->paginate($this->topics_per_page);

		return view('translator.topics', [
			'topics' => $topics,
		]);
	}

	public function translate($topic_id)
	{
		$topic = Topic::where('id', $topic_id)
			->firstOrFail();

		return view('translator.translate', [
			'topic' => $topic,
		]);
	}
}