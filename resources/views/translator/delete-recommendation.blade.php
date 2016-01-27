@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">
					{{ trans('common.delete_recommendation') }}
				</h3>

				<div class="translation-group">
					<h3 class="lang-name navbar-left">{{ trans('common.english') }}</h3>

					<h3 class="title">{{ urldecode(str_replace('_', ' ', $topic->topic)) }}</h3>
					<div>
						<p id="en-abstract">
							{{ $topic->abstract }}
						</p>
					</div>
				</div>

				<br />
				{!! $site_message !!}
				<div class="translation-group">
					<h3 class="lang-name navbar-left">{{ trans('common.delete_recommendation_message') }}</h3>
					<form action="" method="post" id="translation-form">
						{!! csrf_field() !!}
						<div class="form-toolbar float-right">
							<button type="submit" name="delete" value="1" class="btn btn-success">
								{{ trans('common.delete_recommendation_submit') }}
							</button>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<textarea class="form-control abstract-trans register-keystroke"
							name="delete_recommendation_reason" rows="4" style="height:auto !important;" ></textarea>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection