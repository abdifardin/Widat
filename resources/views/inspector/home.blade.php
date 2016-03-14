@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<h2 class="text-center">
						{{ trans('common.inspection_list') }}
					</h2>
					@if(count($inspections) > 0)
						@foreach($inspections as $i)
							@if($i->inspector_id == NULL OR $i->inspector_id == $current_user_id)
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
								<a href="{{ route('inspector.inspection', ['topic_id' => $i->topic_id]) }}"
								   class="btn btn-block btn-default">
									{{ urldecode(str_replace("_", " ", $i->topic)) }}
									<br>
									{{ date('n/j/Y - H:i', $i->inspection_time) }}
								</a>
								<br />
							</div>
							@endif
						@endforeach
				</div>
				{!! $inspections->render() !!}
				@else
					<h4 class="text-center text-danger text-capitalize">
						No Items Found..!
					</h4>
				@endif
			</div>
		</div>
	</div>

@endsection