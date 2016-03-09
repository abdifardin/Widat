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
				@if(count($recommendations_list) > 0)
				<form action="{{ route('admin.bulk_restore') }}" method="post">
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<th><input type="checkbox" name="bulk_restore_all" id="bulk_restore_all" value=""></th>
								<th>{{ trans('common.id') }}</th>
								<th>{{ trans('common.title') }}</th>
								<th>{{ trans('common.abstract') }}</th>
								<th>{{ trans('common.reason') }}</th>
								<th></th>
							</thead>
							<tbody>
								@foreach($recommendations_list as $r)
								<tr>
									<td><input type="checkbox" name="bulk_restore[]" class="bulk_restore" value="{{ $r->topic_id }}"></td>
									<td>{{ $r->topic_id }}</td>
									<td>
										{{ urldecode(str_replace("_", " ", $r->topic)) }}
									</td>
									<td>
										{{ substr($r->abstract, 0, 50) }}
									</td>
									<td>
										{{ substr($r->reason, 0, 50) }}
									</td>
									<td class="text-right">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<a href="{{ route('admin.restore', ['rec_id' => $r->topic_id]) }}"
										   class="btn btn-primary admin_delete_recomm_action_confirm">
											{{ trans('common.restore') }}
										</a>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<a href="{{ route('admin.delete_recommendation', ['rec_id' => $r->topic_id]) }}"
										   class="btn btn-default">
											{{ trans('common.details') }}
										</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<button type="submit" name="restore_selected" value="1" class="btn btn-primary admin_delete_recomm_action_confirm">
							{{ trans('common.restore_selected') }}
						</button>
						<button type="submit" name="remove_selected" value="1" class="btn btn-danger admin_delete_recomm_action_confirm">
							{{ trans('common.remove_selected') }}
						</button>
						
					</div>
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				</form>
				@endif
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