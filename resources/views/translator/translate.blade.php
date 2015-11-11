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

					<h3 class="title">{{ str_replace('_', ' ', $topic->topic) }}</h3>

					<p>
						{{ $topic->abstract }}
					</p>
				</div>

				<br />

				<div class="translation-group">
					<h3 class="lang-name navbar-left">{{ trans('common.kurdish') }}</h3>
					<form action="" method="post">
						<div class="form-toolbar float-right">
							<label for="use-rich-format" class="btn btn-default">
								<input type="checkbox" name="use-rich-format" id="use-rich-format" />
								<span class="float-right">{{ trans('common.use_rich_format') }}</span>
							</label>

							<a class="btn btn-primary reserve-topic" href="javascript:;">
								<span class="fa fa-flag"></span>
								{{ trans('common.reserve_topic') }}
							</a>

							<button type="submit" name="save" value="1" class="btn btn-success">
								<span class="fa fa-floppy-o"></span>
								{{ trans('common.save_changes') }}
							</button>
						</div>

						<div class="clearfix"></div>


						<div class="form-group">
							<input type="text" name="ku_trans_title" class="form-control" />
						</div>
						<div class="form-group">
							<textarea class="form-control" id="ku_trans_abstract" name="ku_trans_abstract"></textarea>
							<div id="summernote"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection