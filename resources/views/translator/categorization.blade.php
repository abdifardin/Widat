<?php
function currentUrl() {
	return $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}
?>
@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">
					{{ trans('common.categorization') }} <strong>(TEST)</strong>
				</h3>
				<br />
				
				<div class="translation-group">
					<h3 class="lang-name navbar-left">{{ trans('common.categorization_message') }}</h3>
					<form action="" method="get" id="translation-form">
						{!! csrf_field() !!}
						<div class="form-group">
							<input type="text" name="cat_keyword" value="{{ $cat_keyword }}" id="cat_keyword" class="form-control"/>
							<button type="submit" name="search" value="1" class="btn btn-primary" style="margin-left: 12px;">
								<i class="fa fa-search"></i>
								{{ trans('common.categorization_key_label') }}
							</button>
						</div>
					</form>
				</div>
				@if(count($category_list) > 0)
				<br>
				<div class="translation-group">
					<h3 class="lang-name navbar-left">Filter by first character</h3>
					<ul class="pagination">
						<li><a href="<?php echo currentUrl().'&firstchar=A'?>">A</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=B'?>">B</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=C'?>">C</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=D'?>">D</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=E'?>">E</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=F'?>">F</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=G'?>">G</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=H'?>">H</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=I'?>">I</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=J'?>">J</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=K'?>">K</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=L'?>">L</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=M'?>">M</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=N'?>">N</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=O'?>">O</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=P'?>">P</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=Q'?>">Q</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=R'?>">R</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=S'?>">S</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=T'?>">T</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=U'?>">U</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=V'?>">V</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=W'?>">W</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=X'?>">X</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=Y'?>">Y</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=Z'?>">Z</a></li>
						<li><a href="<?php echo currentUrl().'&firstchar=Other'?>">Other</a></li>
					</ul>
				</div>
				
				<form method="get">
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<th width="1px;"></th>
								<th>Category Title</th>
							</thead>
							<tbody>
								@foreach($category_list as $c)
								<tr>
									<td><input type="checkbox" name="cats_selected[]" class="cats_selected" value="{{ $c['cl_to'] }}"></td>
									<td>{{ $c['cl_to'] }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<button type="submit" name="search_selected" value="1" class="btn btn-primary">
							{{ trans('common.categorization_search_topics') }}
						</button>
					</div>
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				</form>
				
				@elseif(count($topics_list) > 0)
					<br><br>
					@foreach($topics_list as $t)
						
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
							<a style="background-color:#FAFAFA;" href="{{ route('translator.translate', ['topic_id' => $t->id]) }}"
							   class="btn btn-block btn-default">
								{{ urldecode(str_replace("_", " ", $t->topic)) }}
								@if($t->edited_at)
								<br />{{ date('n/j/Y - H:i', $t->edited_at) }}
								@endif
							</a>
							<br />
						</div>
					@endforeach
				@else
					<h4 class="text-center text-danger text-capitalize">
						No Items Found..!
					</h4>
				@endif
			</div>
		</div>
	</div>
@endsection