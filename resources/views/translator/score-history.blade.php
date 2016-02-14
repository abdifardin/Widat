@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.stats') }}
					<div class="col-sm-5">
						<form method="GET">
							<div class="col-sm-4">
								<input type="number" class="form-control" name="number" id="number" value="{{ $month_num }}">
							</div>
							<div class="col-sm-1">
								<button type="submit" class="btn btn-default">{{ trans('common.scorehistory_number_option_key') }}</button>
							</div>
						</form>
					</div>
				</h3>
				
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