@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">{{ trans('common.delete_recommendations') }}</h3>
				<div class="translation-group">
					<form action="" method="post" id="translation-form">
						{!! csrf_field() !!}
						<div class="form-toolbar float-right">
							<button type="submit" name="delete" value="1" class="btn btn-danger">
								{{ trans('common.delete') }}
							</button>
							<button type="submit" name="postpone" value="1" class="btn btn-info">
								{{ trans('common.delete_recommendation_postpone') }}
							</button>
							<button type="submit" name="deny" value="1" class="btn btn-info">
								{{ trans('common.delete_recommendation_deny') }}
							</button>
						</div>
						<div class="clearfix"></div>
					</form>
					<div class="abstract-wrapper">
						<p id="en-abstract">
							<strong><a href="{{ route('translator.stats', ['user_id' => $translator->id]) }}" target="_blank">
							{{ $translator->name . ' ' . $translator->surname }}
							</a>: </strong>
							{{ $recommendations_reason }}
						</p>
					</div>
					
					<h3 class="title">{{ str_replace('_', ' ', $topic) }}</h3>
					<div class="abstract-wrapper">
						<p id="en-abstract">
							{{ $abstract }}
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection