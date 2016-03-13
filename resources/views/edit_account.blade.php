@extends('master')

@section('content')
	@if(isset($action_result) && $action_result)
		@if($action_error)
		<div class="content-wrapper ambient-key-shadows action-result action-error">
			<span class="fa fa-times-circle fa-2x"></span>
		@else
		<div class="content-wrapper ambient-key-shadows action-result action-success">
			<span class="fa fa-bell fa-2x"></span>
		@endif
			{{ $action_result }}
		</div>
	@endif

	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h2 class="text-center">
					<span class="fa fa-pencil"></span>
					{{ trans('common.edit_account') }}
				</h2>
				<form action="" method="post">
					{!! csrf_field() !!}
					<div class="row">
						@if(\Illuminate\Support\Facades\Auth::user()->user_type=='admin')
							<div class="col-xs-12 col-sm-12 col-md-6 col-md-6 col-lg-6">
								<div class="input-group">
									<label for="name" class="input-group-addon">
										{{ trans('common.name') }}
									</label>
									<input type="text" class="form-control" name="name" id="name"
											value="{{ $user->name }}" autofocus/>
								</div>
								<br />
							</div>

							<div class="col-xs-12 col-sm-12 col-md-6 col-md-6 col-lg-6">
								<div class="input-group">
									<label for="surname" class="input-group-addon">
										{{ trans('common.surname') }}
									</label>
									<input type="text" class="form-control" name="surname" id="surname"
										   value="{{ $user->surname }}"/>
								</div>
								<br/>
							</div>
							
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<div class="input-group">
									<label for="email" class="input-group-addon">
										{{ trans('common.email') }}
									</label>
									<input type="email" class="form-control" name="email" id="email"
										   value="{{ old('email') }}"/>
								</div>
								<br />
							</div>
						@endif
						<div class="col-xs-12 col-sm-12 col-md-6 col-md-6 col-lg-6">
							<div class="input-group">
								<label for="password" class="input-group-addon">
									{{ trans('common.password') }}
								</label>
								<input type="password" class="form-control" name="password"
									   id="password" value="{{ old('password') }}" autofocus/>
								<div class="input-group-addon toggle-password">
									<span class="fa fa-eye-slash"></span>
								</div>
							</div>
							<br />
						</div>

						<div class="col-xs-12 col-sm-12 col-md-6 col-md-6 col-lg-6 pull-right">
							<div class="input-group">
								<label for="password" class="input-group-addon">
									{{ trans('common.password_confirm') }}
								</label>
								<input type="password" class="form-control" name="cpassword"
									   id="cpassword" />
								<div class="input-group-addon toggle-password">
									<span class="fa fa-eye-slash"></span>
								</div>
							</div>
							<br />
						</div>

						<div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4">
							<button name="save" value="1" class="btn btn-success btn-block">
								<span class="fa fa-floppy-o"></span>
								<span class="text-uppercase">
									{{ trans('common.save_changes') }}
								</span>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection