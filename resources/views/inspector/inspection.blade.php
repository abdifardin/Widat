@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.inspection') }}</h3>
				<div class="translation-group">
					<h3 class="lang-name navbar-left">{{ trans('common.english') }}</h3>
					<h3 class="title">{{ str_replace('_', ' ', $topic->topic) }}</h3>
					<div class="abstract-wrapper">
						<p id="en-abstract">
							{{ $topic->abstract }}
						</p>
					</div>
				</div>
				<br />
				<div class="translation-group">
					<h3 class="lang-name navbar-left">{{ trans('common.kurdish') }}</h3>
					<h3 class="title rtl-text">
						&nbsp; {{ str_replace('_', ' ', $ku_trans->topic) }}
					</h3>
					<div class="abstract-wrapper">
						<p id="en-abstract" class="rtl-text">
							{{ $ku_trans->abstract }}
						</p>
					</div>
				</div>
				<br />
				<form action="" method="post" id="translation-form">
					<button type="submit" name="accept" value="1" class="btn btn-success pull-right">
						{{ trans('common.inspector_accept') }}
					</button>
					<button type="submit" name="deny" value="1" class="btn btn-danger pull-right" style="margin-right:6px;">
						{{ trans('common.inspector_deny') }}
					</button>
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				</form>
			</div>
		</div>
	</div>

@endsection