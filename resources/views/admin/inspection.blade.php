@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.inspection') }}</h3>
				@if(isset($translators))
					@foreach($translators as $translator)
						<a href="{{ route('admin.inspection', ['user_id' => $translator->id]) }}"
							class="btn btn-primary">
							{{ $translator->name . ' ' . $translator->surname }}
						</a>
					@endforeach
				@else
					<div class="translation-group">
						<h3 class="lang-name navbar-left">{{ trans('common.english') }}</h3>

						<h3 class="title">{{ str_replace('_', ' ', $topic->topic) }}</h3>

						<div>
							<p id="en-abstract">
								{{ $topic->abstract }}
							</p>
						</div>
					</div>

					<div class="translation-group">
						<h3 class="lang-name navbar-left">{{ trans('common.kurdish') }}</h3>

						<h3 class="title rtl-text">
							{{ str_replace('_', ' ', $ku_trans_title) }}
							<div class="clearfix"></div>
						</h3>
						<div>
							<p id="en-abstract" class="rtl-text">
								{{ $ku_trans_abstract }}
							</p>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>

@endsection