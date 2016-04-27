@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.inspection') }}</h3>
				<div class="translation-group col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left">
					<h3 class="title" id="en-title">{{ str_replace('_', ' ', $topic->topic) }}</h3>
					<div class="abstract-wrapper">
						<p id="inspec-en-abstract">
							{!! nl2br(preg_replace('(\[[0-9]*\])', '', $topic->abstract)) !!}
						</p>
					</div>
				</div>
				<div class="translation-group col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left">
					<h3 class="title rtl-text inspection_viewtranslate" id="ku-title">
						&nbsp; {{ str_replace('_', ' ', $ku_trans->topic) }}
					</h3>
					<div class="abstract-wrapper inspection_viewtranslate" id="inspec-ku-abstract">
						<div class="rtl-text">
							{!! ($ku_trans->abstract) !!}
						</div>
					</div>
					
			<form action="" method="post" id="translation-form">
				<div class="inspection_edittranslate" style="display:none;">
					<h3 class="title" id="edit-ku-title">
						<div class="form-group">
							<input type="text" name="inspection_ku_trans_title" class="form-control rtl-text register-keystroke" value="{{ str_replace('_', ' ', $ku_trans->topic) }}" autofocus/>
						</div>
					</h3>
					<div class="form-group">
						<textarea class="form-control abstract-trans rtl-text register-keystroke" id="inspection_ku_trans_abstract" name="inspection_ku_trans_abstract">
							{{$ku_trans->abstract }}
						</textarea>
					</div>
				</div>
					
				</div>
				<div class="inspection_edittranslate" style="display:none;">
					<div class="form-group">	
						<textarea class="form-control" name="editpage_reject_reason" id="editpage_reject_reason" rows="3" placeholder="Enter a message here to reject translation"></textarea>
					</div>
					<button type="submit" name="save_accept" id="save_accept" value="1" class="btn btn-success pull-left">
						{{ trans('common.inspector_accept_save') }}
					</button>
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				</div>	
			</form>
				<span class="clearfix"></span>
				<br /> 
				<div class="form-group">
				<form action="" method="post" id="translation-form" class="inspection_viewtranslate">
					<div class="form-group">
						<textarea class="form-control" name="reject_reason" id="reject_reason" rows="3" placeholder="Enter a message here to reject translation"></textarea>
					</div>
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
					<button type="submit" name="accept" id="accept" value="1" class="btn btn-success pull-left" style="margin-right:6px;">
						{{ trans('common.inspector_accept') }}
					</button>
					<a href="" id="inspection_edit_key" class="btn btn-info pull-left">Edit</a>
				</form>
				</div>
			</div>
		</div>
	</div>

@endsection