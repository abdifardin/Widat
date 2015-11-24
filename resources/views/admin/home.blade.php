@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.overview') }}</h3>
				<div class="row">
					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">{{ trans('common.admins') }}</h3>
							<p class="count text-center">
								{{ number_format($admins_count) }}
							</p>
						</div>
					</div>
					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">{{ trans('common.translators') }}</h3>
							<p class="count text-center">
								{{ number_format($translators_count) }}
							</p>
						</div>
					</div>
					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">{{ trans('common.topics') }}</h3>
							<p class="count text-center">
								{{ number_format($topics_count) }}
							</p>
						</div>
					</div>
					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">{{ trans('common.ku_translations') }}</h3>
							<p class="count text-center">
								{{ number_format($ku_translations_count) }}
							</p>
						</div>
					</div>

					<div class="col-sm-12">
						<h3 class="text-center">Today's Scores</h3>
					</div>

					@foreach($translators_stats as $ts)
						<div class="col-sm-12 col-md-3">
							<div class="stat-item">
								<h3 class="text-center">
									<a href="{{ route('translator.stats', ['user_id' => $ts['id']]) }}">
										{{ $ts['name'] }}
									</a>
								</h3>
								<p class="count text-center">
									{{ number_format($ts['score_today']) }}
								</p>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>

@endsection