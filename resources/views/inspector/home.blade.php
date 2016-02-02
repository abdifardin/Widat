@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h2 class="text-center">
					{{ trans('common.inspection_list') }}
				</h2>
				@if(count($inspections) > 0)
					@foreach($inspections as $i)
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<a href="{{ route('inspector.inspection', ['topic_id' => $i->topic_id]) }}"
							   class="btn btn-block btn-default">
								{{ urldecode(str_replace("_", " ", $i->topic)) }}
							</a>
							<br />
						</div>
					@endforeach
				@else
					<h4 class="text-center text-danger text-capitalize">
						No Items Found..!
					</h4>
				@endif
			</div>
		</div>
	</div>

@endsection