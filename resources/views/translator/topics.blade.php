@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.topics') }}</h3>

				<div class="text-center">
					<a href="{{ route('translator.topics') }}?filter=all" class="btn btn-info">
						@if(isset($filter_all) && $filter_all)
							<span class="fa fa-cog fa-spin"></span>
						@endif
						{{ trans('common.all') }}
					</a>

					&nbsp;&nbsp;&nbsp;&nbsp;

					<a href="{{ route('translator.topics') }}" class="btn btn-info">
						@if(isset($filter_untranslated_changed) && $filter_untranslated_changed)
							<span class="fa fa-cog fa-spin"></span>
						@endif
						{{ trans('common.untranslated_changed') }}
					</a>

					&nbsp;&nbsp;&nbsp;&nbsp;

					<a href="{{ route('translator.topics') }}?filter=untranslated" class="btn btn-info">
						@if(isset($filter_untranslated) && $filter_untranslated)
							<span class="fa fa-cog fa-spin"></span>
						@endif
						{{ trans('common.untranslated') }}
					</a>

					&nbsp;&nbsp;&nbsp;&nbsp;

					<a href="{{ route('translator.topics') }}?filter=changed" class="btn btn-info">
						@if(isset($filter_changed) && $filter_changed)
							<span class="fa fa-cog fa-spin"></span>
						@endif
						{{ trans('common.changed') }}
					</a>

					&nbsp;&nbsp;&nbsp;&nbsp;

					<a href="{{ route('translator.topics') }}?filter=nocando" class="btn btn-info">
						@if(isset($filter_nocando) && $filter_nocando)
							<span class="fa fa-cog fa-spin"></span>
						@endif
						{{ trans('common.nocandos') }}
					</a>
				</div>

				<br />

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