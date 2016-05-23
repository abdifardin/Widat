<?php
$chars = array(
	'A' => 0,
	'B' => 0,
	'C' => 0,
	'D' => 0,
	'E' => 0,
	'F' => 0,
	'G' => 0,
	'H' => 0,
	'I' => 0,
	'J' => 0,
	'K' => 0,
	'L' => 0,
	'M' => 0,
	'N' => 0,
	'O' => 0,
	'P' => 0,
	'Q' => 0,
	'R' => 0,
	'S' => 0,
	'T' => 0,
	'U' => 0,
	'V' => 0,
	'W' => 0,
	'X' => 0,
	'Y' => 0,
	'Z' => 0
);

$select_options_list = '';
foreach($category_list as $c){
	numCounter($chars, ucfirst($c['cl_to'][0]));
	$select_options_list .= "<option value='$c[cl_to]'>$c[cl_to]</option>";
}
	
function currentUrl() {
	return $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function numCounter(&$chars_arr, $char='') {
	$result_string = '';
	
	if($char != ''){
		if(array_key_exists($char, $chars_arr)){	
			$chars_arr[$char] = $chars_arr[$char] + 1;
		}
	}else{
		foreach($chars_arr as $k => $v){
			if($v > 0){
				$cururl = currentUrl();
				$result_string .= "<li><a href='$cururl&firstchar=$k'>$k ($v)</a></li>";
			}
		}
		return $result_string;
	}
}
?>
@extends('master')

@section('content')
	@if(isset($action_result) && $action_result)
		@if($action_error)
			<div class="content-wrapper ambient-key-shadows action-result action-error">
		@else
			<div class="content-wrapper ambient-key-shadows action-result action-success">
		@endif
			<span class="fa fa-times-circle fa-2x"></span>
			{{ $action_result }}
		</div>
	@endif
	
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">
					Category
				</h3>
				<br />

				<div class="translation-group">
					<h3 class="lang-name navbar-left">{{ trans('common.categorization_message') }}</h3>
					<form action="" method="get" id="translation-form">
						{!! csrf_field() !!}
						<div class="form-group">
							<input type="text" name="cat_keyword" value="{{ $cat_keyword }}" id="cat_keyword" class="form-control" autofocus/>
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
					<span class="clearfix"></span>
					<ul class="pagination">
						<?php echo numCounter($chars)?>
					</ul>
				</div>
				<br/><b/>
				<form method="get">
					<div class="form-group">
						<select name="cats_selected[]" id="cats_selected" size="1" multiple="multiple" class="form-control">
							{!! $select_options_list !!}
						</select>
						<br><br>
						<button type="submit" name="search_selected" value="1" class="btn btn-primary">
							{{ trans('common.categorization_search_topics') }}
						</button>
					</div>
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				</form>
				
				@elseif(count($topics_list) > 0)
					<br/><br/>
					<form action="" method="post">
						<div class="table-responsive">
							<table class="table table-striped">
								<thead>
									<th width="4%"><input type="checkbox" name="bulk_select_all" id="bulk_select_all" value=""></th>
									<th>{{ trans('common.title') }}</th>
								</thead>
								<tbody>
									@foreach($topics_list as $t)
									<tr>
										<td><input type="checkbox" name="bulk_save[]" class="bulk_select" value="{{ $t->id }}"></td>
										<td>
											{{ urldecode(str_replace("_", " ", $t->topic)) }}
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							<button type="submit" name="save_result" value="1" class="btn btn-info">
								Save Selected
							</button>
						</div>
					</form>
				@else
					<h4 class="text-center text-danger text-capitalize">
						No Items Found..!
					</h4>
				@endif
			</div>
		</div>
	</div>
@endsection