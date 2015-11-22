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

		$translated = Topic::where('user_id', $user_id)->count();

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

	public function topics(Request $request)
	{
		$filter_all = false;
		$filter_untranslated_changed = false;
		$filter_untranslated = false;
		$filter_changed = false;
		$filter_nocando = false;

		if($request->has('filter')) {
			switch($request->get('filter', null)) {
				case 'all':
					$filter_all = true;
					break;
				case 'untranslated':
					$filter_untranslated = true;
					break;
				case 'changed':
					$filter_changed = true;
					break;
				case 'nocando':
					$filter_nocando = true;
					break;
			}
		}
		else {
			$filter_untranslated_changed = true;
		}


		if($filter_all) {
			$topics = Topic::paginate($this->topics_per_page);
		}
		else if($filter_untranslated) {
			$topics = Topic::where('topics.user_id', null)
				->leftJoin('nocandos', 'nocandos.topic_id', '=', 'topics.id')
				->where('reason', null)
				->paginate($this->topics_per_page);
		}
		else if($filter_changed) {
			$topics = Topic::where('topics.got_updated', 1)
				->leftJoin('nocandos', 'nocandos.topic_id', '=', 'topics.id')
				->where('reason', null)
				->paginate($this->topics_per_page);
		}
		else if($filter_nocando) {
			$topics = Topic::leftJoin('nocandos', 'nocandos.topic_id', '=', 'topics.id')
				->whereNotNull('reason')
				->paginate($this->topics_per_page);
		}
		else {
			$user_id = Auth::user()->id;
			$topics = Topic::where(function($query) use ($user_id) {
				$query->where('topics.user_id', null);
				$query->orWhere('topics.got_updated');
				$query->where('topics.user_id', '<>', $user_id);
			})
			->leftJoin('nocandos', 'nocandos.topic_id', '=', 'topics.id')
			->whereNull('reason')
			->paginate($this->topics_per_page);
		}

		return view('translator.topics', [
			'topics' => $topics,
			'filter_all' => $filter_all,
			'filter_untranslated' => $filter_untranslated,
			'filter_changed' => $filter_changed,
			'filter_nocando' => $filter_nocando,
			'filter_untranslated_changed' => $filter_untranslated_changed,
		]);
	}

	public function translate(Request $request, $topic_id)
	{
		$topic = Topic::where('id', $topic_id)
			->firstOrFail();

		$user = Auth::user();

		if($topic->user_id != null && $topic->user_id != $user->id) {
			abort(403, 'This translation has been reserved by ' . $topic->user->name . ' ' . $topic->user->surname .
				'.');
		}

		if(strlen($topic->abstract) < 50) {
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

			$current_score = $this->calculateTranslationScore($ku_translation->abstract);

			$user->score = $user->score + $delta_score;
			$user->save();
		}

		$is_owner = $topic->user_id == Auth::user()->id;

		return view('translator.translate', [
			'topic' => $topic,
			'is_owner' => $is_owner,
			'ku_translation_title' => ($ku_translation && $ku_translation->topic) ? $ku_translation->topic : '',
			'ku_translation_abstract' => ($ku_translation && $ku_translation->abstract) ? $ku_translation->abstract : '',
			'current_score' => $current_score,
		]);
	}

	public function nocando($topic_id) {
		if(!Auth::check()) {
			abort(401, 'Unauthorized');
		}

		$topic = Topic::where('id', $topic_id)->first();

		$user = Auth::user();

		if($topic->user_id != null) {
			return redirect()->route('translator.topics');
		}

		$nocando = new Nocando;
		$nocando->user_id = $user->id;
		$nocando->topic_id = $topic_id;
		$nocando->reason = '';
		$nocando->save();

		$user->score = $user->score + (int)Config::get('custom.nocando_score');
		$user->save();
		return redirect()->route('translator.topics');
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