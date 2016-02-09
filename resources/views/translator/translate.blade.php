@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">
					{{ trans('common.translate') }}
				</h3>

				<div class="translation-group">
					<h3 class="lang-name navbar-left">{{ trans('common.english') }}</h3>

					<h3 class="title">{{ urldecode(str_replace('_', ' ', $topic->topic)) }}</h3>

					<div>
						<a href="javascript:;" class="beg-sentence btn btn-warning">
							<span class="fa fa-fast-backward"></span>
							{{ trans('common.beg_sent') }}
						</a>
						<a href="javascript:;" class="rewind-sentence btn btn-default">
							<span class="fa fa-step-backward"></span>
							{{ trans('common.prev_sent') }}
						</a>
						<a href="javascript:;" class="forward-sentence btn btn-success">
							<span class="fa fa-step-forward"></span>
							{{ trans('common.next_sent') }}
						</a>

						<br /><br />

						<p id="en-abstract">
							{{ $topic->abstract }}
						</p>
					</div>
					<textarea id="hidden-en-abstract" class="hidden">{{ $topic->abstract }}</textarea>
				</div>

				<br />
				{!! $msg !!}
				@if($is_translated)
						@if($translation_status == 'accepted')
							<div class="alert alert-warning" role="alert">{{ trans('common.accepted_translation_message') }}</div>
						@elseif($translation_status == 'denied')
							<div class="alert alert-warning" role="alert">{{ trans('common.rejected_translation_message') }}</div>
						@elseif($translation_status == 'wait')
							<div class="alert alert-warning" role="alert">{{ trans('common.wait_translation_message') }}</div>
						@endif
				@endif
				<div class="translation-group">
					<h3 class="lang-name navbar-left">{{ trans('common.kurdish') }}</h3>
					<form action="" method="post" id="translation-form">
						{!! csrf_field() !!}
						<div class="form-toolbar float-right">
							@if($is_owner)
								<button type="submit" name="unreserve" value="1" class="btn btn-default">
									{{ trans('common.un_reserve_topic') }}
									<span class="fa fa-circle-o-notch fa-spin hidden"></span>
								</button>
								@if($is_translated AND $translation_status != 'wait' AND $translation_status != 'accepted')
								<button type="submit" name="inspection" value="1" class="btn btn-info">
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
									<span class="badge">+5</span>
								</button>
							@endif
						</div>

						<div class="clearfix"></div>
						
						<div class="form-group">
							<input type="text" name="ku_trans_title" id="ku_trans_title" class="form-control rtl-text register-keystroke"
								   value="{{$ku_translation_title }}" placeholder="سەردێڕ"
									@if(!$is_owner) disabled @endif
									/>
						</div>
						<div class="col-sm-12" id="save_changes_label" style="display:none;">Saving changes to drafts...</div>
						<div class="col-sm-12" id="retrieve_drafts_label" @if(!$draft_available)style="display:none;"@endif><a href="">Retrieve Drafts</a></div>
						<div class="clearfix"></div>
						<div class="form-group">
							<textarea class="form-control abstract-trans rtl-text register-keystroke" id="ku_trans_abstract"
									  name="ku_trans_abstract" placeholder="ئەبستراکت" @if(!$is_owner) disabled @endif>{{
									  $ku_translation_abstract }}</textarea>
							<input type="hidden" id="current_score" value="{{ $current_score }}" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection