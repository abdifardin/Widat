@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.topics') }}</h3>

				<div class="row">
					@foreach($topics as $t)
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<a href="{{ route('translator.translate', ['topic_id' => $t->id]) }}"
							   class="btn btn-block btn-default">
								{{ str_replace("_", " ", $t->topic) }}
							</a>
							<br />
						</div>
					@endforeach
				</div>

				<br />

				{!! $topics->render() !!}
			</div>
		</div>
	</div>

@endsection