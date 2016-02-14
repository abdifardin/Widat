@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.stats') }}
					<small>{{ $translator->name . ' ' . $translator->surname }}</small></h3>
				<div class="row">
					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">
								{{ trans('common.score') }}
								<small>
									<a href="{{ route('translator.score_history', ['user_id' =>
									$translator->id]) }}">
										{{ trans('common.view_history') }}
									</a>
								</small>
							</h3>
							@if($last_score >= $translator->score)
								<p class="count text-center text-danger">
							@elseif($last_score < $translator->score)
								<p class="count text-center text-success">
							@endif
								@if($last_score > $translator->score)
									<span class="fa fa-caret-down"></span>
								@elseif($last_score < $translator->score)
									<span class="fa fa-caret-up"></span>
								@endif
								{{ number_format($translator->score) }}
							</p>
						</div>
					</div>

					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">
								{{ trans('common.score') }}
								<small>
									( {{ trans('common.this_month') }} )
								</small>
							</h3>
							<p class="count text-center">
								{{ number_format($this_month_score) }}
							</p>
						</div>
					</div>

					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">
								{{ trans('common.translated') }}
							</h3>
							<p class="count text-center">
								{{ number_format($translated->count()) }}
							</p>
						</div>
					</div>

					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">
								{{ trans('common.last_activity') }}
							</h3>
							<p class="text-center">
								@if(strtotime($translator->last_activity) < 0)
									NEVER
								@else
									{!! nl2br(date("l, d F Y \n H:i:s e", strtotime($translator->last_activity))) !!}
								@endif
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<h3 class="text-center">{{ trans('common.translated_topics') }}</h3>

						<div class="row">
							@foreach($translated as $t)
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
								@if($t->finished == 1 AND $t->inspection_result == 1)
								<a href="javascript:;" style="background-color:#DCEDC8;" class="btn btn-block btn-default peek-link">
								@elseif($t->finished == 1 AND $t->inspection_result == -1)
								<a href="javascript:;" style="background-color:#ffcdd2;" class="btn btn-block btn-default peek-link">
								@elseif($t->finished == 1 AND $t->inspection_result == 0)
								<a href="javascript:;" style="background-color:#FFF9C4;" class="btn btn-block btn-default peek-link">
								@else
								<a href="javascript:;" style="background-color:#FAFAFA;" class="btn btn-block btn-default peek-link">
								@endif
									<span class="peek_topic_title_box">{{ urldecode($t->topic) }}</span>
									@if($t->edited_at)
									<br>
									{{ date('n/j/Y - H:i', $t->edited_at) }}
									@endif
								</a>
								<br />
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection