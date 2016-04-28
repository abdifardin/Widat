@extends('master')

@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
		<form action="" method="post">
			{!! csrf_field() !!}
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Set password</h3>
				</div>

				@if ($action_error)
					<div class="alert alert-danger">
						{{ $action_result }}
					</div>
				@endif

				<div class="panel-body">
					<label for="password">{{ trans('common.password') }}</label>
					<input type="password" class="form-control" name="password"
						   placeholder="{{ trans('common.password') }}" />
						   
					<label for="cpassword">{{ trans('common.password_confirm') }}</label>
					<input type="password" class="form-control" name="cpassword"
						   placeholder="{{ trans('common.password_confirm') }}" />

					<br />

					<button type="submit" name="save" value="1" class="btn btn-success btn-block">
						{{ trans('common.set_password') }}
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection