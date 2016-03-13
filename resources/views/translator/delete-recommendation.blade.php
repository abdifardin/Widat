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
							<select class="form-control" name="delete_recommendation_reason" autofocus>
								<option value="Incomplete or meaningless article">Incomplete or meaningless article</option>
								<option value="Empty topic">Empty topic</option>
								<option value="Any other reason">Any other reason</option>
							</select>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection