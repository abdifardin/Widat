@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.stats') }}</h3>
				<div class="row">
					@foreach($score_history as $month=>$sh)
					<div class="col-sm-12 col-md-3">
						<div class="stat-item">
							<h3 class="text-center">
								{{ $month }}
							</h3>
							<p class="count text-center">
								{{ number_format($sh) }}
							</p>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>

@endsection