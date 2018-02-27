<ul class="nav navbar-nav">
    @if(Auth::guard('admin')->user()->hasPermission('index_design'))
		<li><a href="{{route('designs.index')}}">Design</a></li> @endif
	@if(Auth::guard('admin')->user()->hasPermission('index_menu'))
		<li><a href="{{route('menus.index')}}">Menus</a></li> @endif
	@if(Auth::guard('admin')->user()->hasPermission('index_page'))
		<li><a href="{{route('pages.index')}}">Pages</a></li> @endif
	
	<li class="dropdown">
        <a href="#" class="dropdown-toggle"
           data-toggle="dropdown" role="button"
           aria-expanded="false" aria-haspopup="true">
           User Management <span
			        class="caret"></span>
        </a>

        <ul class="dropdown-menu">
           @if(Auth::guard('admin')->user()->hasPermission('index_user'))
		        <li><a href="{{'/'.config('admin.route_prefix')."/users"}}">Users</a></li> @endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_role'))
		        <li><a href="{{'/'.config('admin.route_prefix')."/roles"}}">Roles</a></li> @endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_permission'))
		        <li><a href="{{'/'.config('admin.route_prefix')."/permissions"}}">Permissions</a></li> @endif
            </li>
		        <li role="separator" class="divider"></li>
		        @if(Auth::guard('admin')->user()->hasPermission('index_administrator'))
			        <li><a>Administrators</a></li>@endif
		
		        <li><a href="{{config('admin.route_prefix')."/roles"}}">Admin Roles</a></li>
		        <li><a>Admin Permissions</a></li>
        </ul>
    </li>
	<li class="dropdown">
        <a href="#" class="dropdown-toggle"
           data-toggle="dropdown" role="button"
           aria-expanded="false" aria-haspopup="true">
           Settings <span
			        class="caret"></span>
        </a>

        <ul class="dropdown-menu">
            @if(Auth::guard('admin')->user()->hasPermission('index_language'))
		        <li><a href="{{route('languages.index')}}">Languages</a>
			        @endif
            </li>
        </ul>
    </li>
</ul>