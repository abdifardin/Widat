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
							@if($last_score > $translator->score)
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
								{{ trans('common.nocandos') }}
							</h3>
							<p class="count text-center text-danger">
								{{ number_format($no_can_dos) }}
							</p>
						</div>
					</div>

					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">
								{{ trans('common.translated') }}
							</h3>
							<p class="count text-center">
								{{ number_format($translated) }}
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection