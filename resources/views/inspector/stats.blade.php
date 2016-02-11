@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.stats') }}
					<small>{{ $inspector->name . ' ' . $inspector->surname }}</small></h3>
				<div class="row">
					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">
								{{ trans('common.score') }}
								<small>
									<a href="{{ route('inspector.score_history', ['user_id' =>
									$inspector->id]) }}">
										{{ trans('common.view_history') }}
									</a>
								</small>
							</h3>
							@if($last_score >= $inspector->score)
								<p class="count text-center text-danger">
							@elseif($last_score < $inspector->score)
								<p class="count text-center text-success">
							@endif
								@if($last_score > $inspector->score)
									<span class="fa fa-caret-down"></span>
								@elseif($last_score < $inspector->score)
									<span class="fa fa-caret-up"></span>
								@endif
								{{ number_format($inspector->score) }}
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
								{{ trans('common.inspected') }}
							</h3>
							<p class="count text-center">
								{{ number_format($inspected->count()) }}
							</p>
						</div>
					</div>

					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">
								{{ trans('common.last_activity') }}
							</h3>
							<p class="text-center">
								{!! nl2br(date("l, d F Y \n H:i:s e", strtotime($inspector->last_activity))) !!}
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<h3 class="text-center">{{ trans('common.proofrede_topics') }}</h3>

						<div class="row">
							@foreach($inspected as $t)
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
									@if($t->auditing_count > 1 AND $t->inspection_result != 1)
									({{ $t->auditing_count }})
									@endif
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