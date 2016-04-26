@extends('master')

@section('content')

	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h2 class="text-center">
					<span class="fa fa-pencil"></span>
					Change Password
				</h2>
				<div class="alert alert-warning" role="alert">{{ trans('common.change_password_firsttime') }}
				 <a href="{{ route('main.edit_account', ['user_id' => \Illuminate\Support\Facades\Auth::user()->id]) }}">Click here to change your password.</a>
				</div>
			</div>
		</div>
	</div>
@endsection