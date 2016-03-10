<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="{{ trans('main.site_description') }}">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>
			@if(isset($page_title))
				{{ $page_title }}
				&middot;
			@endif

			{{ trans('common.site_name') }}
		</title>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}" />
		<link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}" />
		<link rel="stylesheet" href="{{ url('css/font-awesome.css') }}" />
		<link rel="stylesheet" href="{{ url('css/style.css') }}" />
		<link rel="stylesheet" href="{{ url('css/chosen.min.css') }}" />

		<!-- Favicons -->
		<link rel="apple-touch-icon" href="/apple-touch-icon.png"> <!-- 180 -->
		<link rel="icon" href="/favicon.ico"> <!-- 32 -->
	</head>
	<body>


		<div class="container">
			@include('navbar')

			@section('content')
			@show
			{{ trans('common.site_name') }} Application {{ $app_version }}
		</div>

		@include('footer')

		<div class="modal fade topic-peek-modal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">{{ trans('common.peek') }}</h4>
					</div>
					<div class="modal-body">
						<div class="text-center loader hidden"><span class="fa fa-cog fa-4x fa-spin"></span></div>
						<h4 class="text-center text-danger topic-not-found hidden">
							{{ trans('common.topic_not_found') }}
						</h4>

						<div class="translation-group en hidden">
							<h3 class="lang-name navbar-left">{{ trans('common.english') }}</h3>
							<h3 id="peek-en-title" class="title"></h3>
							<div><p id="peek-en-abstract"></p></div>
						</div>
						<p id="peek-no-ku-trans" class="text-center text-danger no-trans hidden">
							<br />
							{{ trans('common.no_trans') }}
							<br />
							<a href="" class="translate-now btn btn-primary">
								Translate Now
							</a>
							<span id="deletion-rec-box"></span>
							{{-- "Recommend for Deletion" Key place here by script.js --}}
						</p>
						<div class="translation-group ku hidden">
							<h3 class="lang-name navbar-left">{{ trans('common.kurdish') }}</h3>
							<h3 id="peek-ku-title" class="title rtl-text"></h3>
							<div>
								<p id="peek-ku-abstract" class="rtl-text"></p>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<a href="" class="refering-to-topic btn btn-primary" style="display:none;">
							{{ trans('common.refering_to_topic') }}
						</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							{{ trans('common.close') }}
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<script src="{{ url('js/jquery.js') }}"></script>
		<script src="{{ url('js/jquery-ui.js') }}"></script>
		<script src="{{ url('js/bootstrap.min.js') }}"></script>
		<script src="{{ url('js/script.js') }}"></script>
		<script src="{{ url('js/autosave.js') }}"></script>
		<script src="{{ url('js/chosen.jquery.min.js') }}"></script>
		<input type="hidden" id="reg-keystroke-url" value="{{ route('translator.register_keystroke') }}" />
		<input type="hidden" id="reg-activity-url" value="{{ route('translator.register_activity') }}" />
		<input type="hidden" id="get-statuses-url" value="{{ route('translator.get_statuses') }}" />
	</body>
</html>