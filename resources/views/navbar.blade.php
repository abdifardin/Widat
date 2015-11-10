@if(\Illuminate\Support\Facades\Auth::check())
<nav class="navbar navbar-default ambient-key-shadows">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
					data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">{{ trans('common.site_name') }}</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">

				@if(\Illuminate\Support\Facades\Auth::user()->user_type=='admin')
					<li>
						<a href="{{ route('admin.admins') }}">
							<span class="fa fa-certificate fa-2x"></span>
							<span class="label">{{ trans('common.admins') }}</span>
						</a>
					</li>
					<li>
						<a href="{{ route('admin.translators') }}">
							<span class="fa fa-users fa-2x"></span>
							<span class="label">{{ trans('common.users') }}</span>
						</a>
					</li>
				@endif

				<li>
					<a href="{{ route('auth.logout') }}" class="text-danger">
						<span class="fa fa-sign-out fa-2x"></span>
						<span class="label">{{ trans('common.logout') }}</span>
					</a>
				</li>

			</ul>
			<form class="navbar-form navbar-right" role="search">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Search">
				</div>
			</form>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
@endif