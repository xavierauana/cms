@if(Auth::guard('admin')->user()->hasPermission('index_design'))
	<li><a class="nav-link" href="{{route('designs.index')}}">Design</a></li> @endif
@if(Auth::guard('admin')->user()->hasPermission('index_menu'))
	<li><a class="nav-link" href="{{route('menus.index')}}">Menus</a></li> @endif
@if(Auth::guard('admin')->user()->hasPermission('index_page'))
	<li><a class="nav-link" href="{{route('pages.index')}}">Pages</a></li> @endif

<li class="dropdown">
        <a href="#" class="nav-link dropdown-toggle"
           data-toggle="dropdown" role="button"
           aria-expanded="false" aria-haspopup="true">
           User Management <span
			        class="caret"></span>
        </a>

        <ul class="dropdown-menu">
           @if(Auth::guard('admin')->user()->hasPermission('index_user'))
		        <li><a class="nav-link" href="{{'/'.config('admin.route_prefix')."/users"}}">Users</a></li> @endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_role'))
		        <li><a class="nav-link" href="{{'/'.config('admin.route_prefix')."/roles"}}">Roles</a></li> @endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_permission'))
		        <li><a class="nav-link" href="{{'/'.config('admin.route_prefix')."/permissions"}}">Permissions</a></li> @endif
            </li>
		        <li role="separator" class="divider"></li>
		        @if(Auth::guard('admin')->user()->hasPermission('index_administrator'))
			        <li><a class="nav-link">Administrators</a></li>@endif
		
		        <li><a class="nav-link" href="{{config('admin.route_prefix')."/roles"}}">Admin Roles</a></li>
		        <li><a class="nav-link">Admin Permissions</a></li>
        </ul>
    </li>
	<li class="dropdown">
        <a href="#" class=" nav-link dropdown-toggle"
           data-toggle="dropdown" role="button"
           aria-expanded="false" aria-haspopup="true">
           Settings <span
			        class="caret"></span>
        </a>

        <ul class="dropdown-menu">
            @if(Auth::guard('admin')->user()->hasPermission('index_language'))
		        <li><a class="nav-link" href="{{route('languages.index')}}">Languages</a>
			        @endif
            </li>
        </ul>
    </li>