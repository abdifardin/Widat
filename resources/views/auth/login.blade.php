@extends('master')

@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
		<form action="{{ route('auth.login') }}" method="post">
			{!! csrf_field() !!}
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">{{ trans('common.login') }}</h3>
				</div>

				<div class="panel-body">
					<label for="email">{{ trans('common.email') }}</label>
					<input type="email" class="form-control" name="email" id="email"
						   placeholder="{{ trans('common.email') }}" value="{{ old('email') }}" />

					<label for="password">{{ trans('common.password') }}</label>
					<input type="password" class="form-control" name="password" id="password"
						   placeholder="{{ trans('common.password') }}" />

					<br />

					<button type="submit" class="btn btn-success btn-block">
						{{ trans('common.login') }}
					</button>
				</div>

				<div class="panel-footer">
					<input type="checkbox" name="remember" id="remember" checked />
					<label for="remember">{{ trans('common.remember_me') }}</label>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection