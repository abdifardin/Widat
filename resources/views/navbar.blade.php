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
			<a class="navbar-brand" href="{{ route('main.root') }}">
				{{ trans('common.site_name') }}
			</a>
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
							<span class="label">{{ trans('common.translators') }}</span>
						</a>
					</li>
					{{--<li>--}}
						{{--<a href="{{ route('admin.delete') }}">--}}
							{{--<span class="fa fa-trash fa-2x"></span>--}}
							{{--<span class="label">{{ trans('common.delete') }}</span>--}}
						{{--</a>--}}
					{{--</li>--}}
					<li>
						<a href="{{ route('admin.inspection') }}">
							<span class="fa fa-user-secret fa-2x"></span>
							<span class="label">{{ trans('common.inspection') }}</span>
						</a>
					</li>
					<li>
						<a href="{{ route('main.edit_account', [
							'user_id' => \Illuminate\Support\Facades\Auth::user()->id
						]) }}">
							<span class="fa fa-pencil fa-2x"></span>
							<span class="label">{{ trans('common.edit_account') }}</span>
						</a>
					</li>
					<li>
						<a href="{{ route('admin.delete_recommendation') }}">
							<span class="fa fa-trash fa-2x"></span>
							<span class="label">{{ trans('common.delete_recommendations') }} <span class="badge">{{ $delete_recommendations_num }}</span></span>
						</a>
					</li>
				@elseif(\Illuminate\Support\Facades\Auth::user()->user_type=='translator')
					<li>
						<a href="{{ route('translator.stats',[
							'user_id' => \Illuminate\Support\Facades\Auth::user()->id
						]) }}">
							<span class="fa fa-bar-chart fa-2x"></span>
							<span class="label">{{ trans('common.stats') }}</span>
						</a>
					</li>
					<li>
						<a href="{{ route('translator.topics') }}">
							<span class="fa fa-language fa-2x"></span>
							<span class="label">{{ trans('common.topics') }}</span>
						</a>
					</li>
					<li>
						<a href="{{ route('main.edit_account', [
							'user_id' => \Illuminate\Support\Facades\Auth::user()->id
						]) }}">
							<span class="fa fa-pencil fa-2x"></span>
							<span class="label">{{ trans('common.edit_account') }}</span>
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
			<form id="search-form" class="navbar-form navbar-right" action="{{ route('main.suggestions') }}" method="post">
				{!! csrf_field() !!}
				<div class="form-group search-form-group">
					<input id="topic-peek-search" type="text" class="form-control" placeholder="Type and hit enter">
					<div class="suggestions text-center hidden">
						<div class="list-group">
						</div>
					</div>
				</div>
			</form>
			<form class="hidden" action="{{ route('main.peek') }}" id="peek-form"></form>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
@endif