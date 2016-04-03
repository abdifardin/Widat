@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">
					{{ trans('common.translate') }}
				</h3>
				<br />
				{!! $msg !!}
				@if($is_translated)
						@if($translation_status == 'accepted')
							<div class="alert alert-warning" role="alert">{{ trans('common.accepted_translation_message') }}</div>
						@elseif($translation_status == 'denied')
							<div class="alert alert-warning" role="alert">{{ trans('common.rejected_translation_message') }}
								<br><br>The proofreader sent you the following message:<br><strong>{{ $inspector_message }}</strong>
							</div>
						@elseif($translation_status == 'wait')
							<div class="alert alert-warning" role="alert">{{ trans('common.wait_translation_message') }}</div>
						@endif
				@endif
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right">
					<div class="col-sm-12" id="save_changes_label" style="display:none;">Saving changes to draft...</div>
					<div class="col-sm-12" id="retrieve_drafts_label" @if(!$draft_available)style="display:none;"@endif><a href="">Retrieve draft @if($draft_available){{ date('n/j/Y : H:i', $draft_time) }}@endif hrs to save</a></div>
					<input type="hidden" id="have_draft" value="{{ $draft_available }}" />
				</div>
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-default" id="inc_text_size"><span class="fa fa-plus"></span></button>
					<button type="button" class="btn btn-default" id="reset_tex_size"><span class="fa fa-repeat"></span></button>
					<button type="button" class="btn btn-default" id="dec_text_size"><span class="fa fa-minus"></span></button>
				</div>
				<br><br>
				<div class="translation-group col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left">
					<h3 class="lang-name navbar-left" style="width: 100%;">{{ trans('common.english') }}</h3>
					<div class="col-sm-12">
						<a href="javascript:;" class="beg-sentence btn btn-warning">
							{{ trans('common.beg_sent') }}
						</a>
						<a href="javascript:;" class="rewind-sentence btn btn-default">
							{{ trans('common.prev_sent') }}
						</a>
						<a href="javascript:;" class="forward-sentence btn btn-success">
							{{ trans('common.next_sent') }}
						</a>
						<br><br>
						<h3 class="title">{{ urldecode(str_replace('_', ' ', $topic->topic)) }}</h3>
						<br /><br /><br />
						<div class="clearfix"></div>
						<p id="en-abstract">
							{{ nl2br(preg_replace('(\[[0-9]*\])', '', $topic->abstract)) }}
						</p>
						<textarea id="hidden-en-abstract" class="hidden">{{ nl2br(preg_replace('(\[[0-9]*\])', '', $topic->abstract)) }}</textarea>
					</div>
				</div>
				<div class="translation-group col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right">
					<h3 class="lang-name navbar-left" style="width: 100%;">{{ trans('common.kurdish') }}</h3>
					<div class="col-sm-12">
					<form action="" method="post" id="translation-form">
						{!! csrf_field() !!}
						<div class="form-toolbar float-right" style="margin-top:0">
							@if($is_owner)
								<button type="submit" name="unreserve" id="unreserve" value="1" class="btn btn-default">
									{{ trans('common.un_reserve_topic') }}
									<span class="fa fa-circle-o-notch fa-spin hidden"></span>
								</button>
								@if($is_translated AND $translation_status != 'wait' AND $translation_status != 'accepted')
								<button type="submit" name="inspection" id="inspection" value="1" class="btn btn-info">
									{{ trans('common.submit_inspection') }}
								</button>
								@endif
								<button type="submit" name="save" value="1" class="btn btn-success">
									<span class="fa fa-floppy-o"></span>
									{{ trans('common.save_changes') }}
									<span class="badge">0</span>
									<span class="fa fa-circle-o-notch fa-spin hidden"></span>
								</button>
							@else
								@if($topic->user_id == NULL AND $topic->delete_recommended == 0)
								<a href="{{ route('translator.delete_recommendation', ['topic_id' => $topic->id]) }}" class="deletion-rec btn btn-warning" style="margin-left: 4px;">{{ trans('common.delete_recommendation_submit') }}</a>
								@endif
								<button type="submit" name="reserve" value="1" class="btn btn-primary reserve-topic">
									<span class="fa fa-flag"></span>
									{{ trans('common.reserve_topic') }}
								</button>
							@endif
						</div>

						<div class="clearfix"></div>
						
						<div class="form-group">
							<input type="text" name="ku_trans_title" id="ku_trans_title" class="form-control rtl-text register-keystroke"
								   value="{{$ku_translation_title }}" placeholder="سەردێڕ"
									@if(!$is_owner) disabled @endif autofocus
									/>
						</div>
						
						<div class="clearfix"></div>
						<div class="form-group">
							<button type="button" class="btn btn-default editor_action" id="ol"><span class="fa fa-list-ol"></span></button>
							<button type="button" class="btn btn-default editor_action" id="ul"><span class="fa fa-list-ul"></span></button>
							<button type="button" class="btn btn-default editor_action" id="item"><span class="fa fa-sticky-note"></span></button>
							<button type="button" class="btn btn-default editor_action" id="sup"><span class="fa fa-superscript"></span></button>
							<button type="button" class="btn btn-default editor_action" id="sub"><span class="fa fa-subscript"></span></button>
							<textarea class="form-control abstract-trans rtl-text register-keystroke" id="ku_trans_abstract"
									  name="ku_trans_abstract" placeholder="ئەبستراکت">{{
									  $ku_translation_abstract }}</textarea>
							<input type="hidden" id="current_score" value="{{ $current_score }}" />
						</div>
						@if(!$is_owner)
							<style type="text/css">
							.note-editable {
								display:none;
							}
							</style>
						@endif
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection