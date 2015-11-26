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

				<div class="translation-group">
					<h3 class="lang-name navbar-left">{{ trans('common.kurdish') }}</h3>
					<form action="" method="post">
						{!! csrf_field() !!}

						<div class="form-toolbar float-right">
							@if($is_owner)
							<button type="submit" name="save" value="1" class="btn btn-success">
								<span class="fa fa-floppy-o"></span>
								{{ trans('common.save_changes') }}
								<span class="badge">0</span>
								<span class="fa fa-circle-o-notch fa-spin hidden"></span>
							</button>
							@else
							<a class="btn btn-danger nocando-topic" href="{{ route('translator.nocando',
								['topic_id' => $topic->id]) }}">
								<span class="fa fa-life-ring"></span>
								{{ trans('common.nocando') }}
								<span class="badge">-5</span>
							</a>
							<button type="submit" name="reserve" value="1" class="btn btn-primary reserve-topic">
								<span class="fa fa-flag"></span>
								{{ trans('common.reserve_topic') }}
								<span class="badge">+5</span>
							</button>
							@endif
						</div>

						<div class="clearfix"></div>


						<div class="form-group">
							<input type="text" name="ku_trans_title" class="form-control rtl-text register-keystroke"
								   value="{{
							$ku_translation_title }}" />
						</div>
						<div class="form-group">
							<textarea class="form-control abstract-trans rtl-text register-keystroke" id="ku_trans_abstract"
									  name="ku_trans_abstract">{{ $ku_translation_abstract }}</textarea>
							<input type="hidden" id="current_score" value="{{ $current_score }}" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection