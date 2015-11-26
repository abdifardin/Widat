@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.inspection') }}</h3>
				@if(isset($translators))
					<div class="row">
						@foreach($translators as $translator)
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
								<a href="{{ route('admin.inspection', ['user_id' => $translator->id]) }}"
								   class="btn btn-primary btn-block btn-lg">
									<span id="online-status-{{ $translator->id }}" class="online-status"></span>

									{{ $translator->name . ' ' . $translator->surname }}


									<span id="typing-status-{{ $translator->id }}"
											class="fa fa-keyboard-o hidden"></span>
								</a>
								<br />
							</div>
						@endforeach
					</div>
				@elseif($topic == null)
					<h3 class="text-center text-danger">No topics have been translated by this user.</h3>
				@else
					<div class="translation-group">
						<h3 class="lang-name navbar-left">{{ trans('common.english') }}</h3>

						<h3 class="title">{{ str_replace('_', ' ', $topic->topic) }}</h3>

						<div class="abstract-wrapper">
							<p id="en-abstract">
								{{ $topic->abstract }}
							</p>
						</div>
					</div>

					<div class="translation-group">
						<h3 class="lang-name navbar-left">{{ trans('common.kurdish') }}</h3>

						<h3 class="title rtl-text">
							&nbsp; {{ str_replace('_', ' ', $ku_trans_title) }}
						</h3>

						<div class="abstract-wrapper">
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