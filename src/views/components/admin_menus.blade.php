<ul class="nav navbar-nav">
    @if(Auth::guard('admin')->user()->hasPermission('index_design'))
		<li><a href="/admin/designs">Design</a></li> @endif
	@if(Auth::guard('admin')->user()->hasPermission('index_menu'))
		<li><a href="/admin/menus">Menus</a></li> @endif
	@if(Auth::guard('admin')->user()->hasPermission('index_page'))
		<li><a href="/admin/pages">Pages</a></li> @endif
	
	<li class="dropdown">
        <a href="#" class="dropdown-toggle"
           data-toggle="dropdown" role="button"
           aria-expanded="false" aria-haspopup="true">
           User Management <span
			        class="caret"></span>
        </a>

        <ul class="dropdown-menu">
           @if(Auth::guard('admin')->user()->hasPermission('index_user'))
		        <li><a href="/admin/users">Users</a></li> @endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_role'))
		        <li><a href="/admin/roles">Roles</a></li> @endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_permission'))
		        <li><a href="/admin/permissions">Permissions</a></li> @endif
            </li>
		        <li role="separator" class="divider"></li>
		        @if(Auth::guard('admin')->user()->hasPermission('index_administrator'))
			        <li><a>Administrators</a></li>@endif
		        <li><a>Admin Roles</a></li>
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
		        <li><a href="/admin/languages">Languages</a>
			        @endif
            </li>
        </ul>
    </li>
</ul>