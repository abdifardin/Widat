@extends('master')

@section('content')
	@if(isset($action_result) && $action_result)
		@if($action_error)
			<div class="content-wrapper ambient-key-shadows action-result action-error">
		@else
			<div class="content-wrapper ambient-key-shadows action-result action-success">
		@endif
			<span class="fa fa-times-circle fa-2x"></span>
			{{ $action_result }}
		</div>
	@endif

	<div class="content-wrapper ambient-key-shadows">

		<div class="row">
			<div class="col-sm-12">
				<br />
				<form action="" method="get" class="filter-form">
					<input type="text" name="filter" value="{{ $filter }}"
							placeholder="Filter Topics"/>

					<button class="btn btn-primary" type="submit">
						<span class="fa fa-filter fa-2x"></span>
					</button>

					<a href="{{ route('admin.delete') }}" class="btn btn-default">
						<span class="fa fa-ban fa-2x"></span>
					</a>
				</form>
			</div>
		</div>

		<br />

		<div class="row">
			@foreach($topics as $t)
			<div class="col-sm-12 col-md-4 col-lg-3 no-wrap">
				<form class="delete-topic-form no-wrap" action="" method="post">
					{!! csrf_field() !!}

					<button type="submit" name="delete" value="{{ $t->id }}"
							class="btn btn-danger">
						<span class="fa fa-trash"></span>
					</button>
					{{ urldecode(str_replace("_", " ", $t->topic)) }}
				</form>
				<br />
			</div>
			@endforeach
		</div>
	</div>
@endsection