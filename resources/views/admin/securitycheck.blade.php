@extends('master')

@section('content')
	<div class="content-wrapper ambient-key-shadows">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="text-center">
					Security Check
				</h3>
				<br><br>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right">
					<div class="col-sm-12">
					<form action="" method="post" id="translation-form">
						{!! csrf_field() !!}
						
						<div class="clearfix"></div>
						<strong>Input:</strong>
						<br>
						<?php echo $direct;?>
						<div class="clearfix"></div>
						<strong>Database:</strong>
						<br>
						<?php echo $inserted;?>
						<br><br>
						<div class="clearfix"></div>
						<div class="form-group">
							<textarea class="form-control" id="sec_check" name="sec_check" rows="5" placeholder="Text"></textarea>
						</div>
						
						<button type="submit" name="send" id="send" value="1" class="btn btn-info">Submit</button>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection