<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $dir }}">
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

			{{ trans('main.site_name') }}
		</title>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}" />
		<link rel="stylesheet" href="{{ url('css/bootstrap.css') }}" />
		<link rel="stylesheet" href="{{ url('css/style.css') }}" />
		<link rel="stylesheet" href="{{ url('css/summernote.css') }}" />
		<link rel="stylesheet" href="{{ url('css/font-awesome.css') }}" />

		@if($dir == 'rtl')
			<link rel="stylesheet" href="{{ url('css/bootstrap-rtl.css') }}" />
			<link rel="stylesheet" href="{{ url('css/style.rtl.css') }}" />
		@endif


		<!-- Favicons -->
		<link rel="apple-touch-icon" href="/apple-touch-icon.png"> <!-- 180 -->
		<link rel="icon" href="/favicon.ico"> <!-- 32 -->
	</head>
	<body>
		@include('navbar')

		<div class="container content-container">
			@section('content')
			@show
		</div>

		@include('footer')

		<script src="{{ url('js/jquery.js') }}"></script>
		<script src="{{ url('js/jquery-ui.js') }}"></script>
		<script src="{{ url('js/bootstrap.js') }}"></script>
		<script src="{{ url('js/script.js') }}"></script>
		<script src="{{ url('js/summernote.js') }}"></script>
	</body>
</html>