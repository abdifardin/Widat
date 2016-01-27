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
					<span class="fa fa-trash"></span>
					{{ trans('common.delete_recommendations') }}
				</h2>

				@foreach($recommendations_list as $r)
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
						<a href="{{ route('admin.delete_recommendation', ['rec_id' => $r->id]) }}"
						   class="btn btn-block btn-default">
							{{ urldecode(str_replace("_", " ", $r->topic)) }}
						</a>
						<br />
					</div>
				@endforeach
				@if(!count($recommendations_list))
					<h4 class="text-center text-danger text-capitalize">
						No Items Found..!
					</h4>
				@endif
			</div>
		</div>
	</div>

	<div class="modal fade delete-user-modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">{{ trans('common.delete') }}?</h4>
				</div>
				<div class="modal-body">
					<p>{{ trans('common.confirm_deletion') }}</p>
				</div>
				<div class="modal-footer">
					<form class="form-inline" action="" method="post">
						{!!csrf_field() !!}
						<input type="hidden" name="user_id" />
						<button type="button" class="btn btn-default" data-dismiss="modal">
							<span class="fa fa-smile-o"></span>
							{{ trans('common.cancel') }}
						</button>
						<button type="submit" class="btn btn-danger" name="delete" value="1">
							<span class="fa fa-trash"></span>
							{{ trans('common.delete') }}
						</button>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
@endsection