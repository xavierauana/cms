@if(Auth::guard('admin')->user()->hasPermission(['edit_layout','edit_definition']))
	<li><a class="nav-link"
	       href="{{route('designs.index')}}">Design</a></li> @endif
@if(Auth::guard('admin')->user()->hasPermission('index_menu'))
	<li><a class="nav-link"
	       href="{{route('menus.index')}}">Menus</a></li> @endif
@if(Auth::guard('admin')->user()->hasPermission('index_page'))
	<li><a class="nav-link"
	       href="{{route('pages.index')}}">Pages</a></li> @endif

<li class="dropdown">
        <a href="#" class="nav-link dropdown-toggle"
           data-toggle="dropdown" role="button"
           aria-expanded="false" aria-haspopup="true">
           User Management <span
			        class="caret"></span>
        </a>

        <ul class="dropdown-menu">
           @if(Auth::guard('admin')->user()->hasPermission('index_user'))
		        <li><a class="nav-link"
		               href="{{'/'.config('admin.route_prefix')."/users"}}">Users</a></li> @endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_role'))
		        <li><a class="nav-link"
		               href="{{'/'.config('admin.route_prefix')."/roles"}}">Roles</a></li> @endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_permission'))
		        <li><a class="nav-link"
		               href="{{'/'.config('admin.route_prefix')."/permissions"}}">Permissions</a></li> @endif
            </li>
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
		        <li><a class="nav-link" href="{{route('languages.index')}}">Languages</a></li>@endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_setting'))
		        <li><a class="nav-link" href="{{route('settings.index')}}">Systems Settings</a></li>@endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_common_content'))
		        <li><a class="nav-link" href="{{route('cms::common_contents.index')}}">Common Content</a></li>@endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_admin'))
		        <li><a class="nav-link"
		               href="{{route('administrators.index')}}">Administrators</a></li>@endif
	        @if(Auth::guard('admin')->user()->hasPermission('index_admin_role'))
		        <li><a class="nav-link" href="{{route('admin_roles.index')}}">Administrator Roles</a></li>@endif
				
        </ul>
    </li>

	
	
	<li class="dropdown">
        <a href="#" class="nav-link dropdown-toggle"
           data-toggle="dropdown" role="button"
           aria-expanded="false" aria-haspopup="true">
           Plugins <span class="caret"></span>
        </a>
		<ul class="dropdown-menu">
	        @foreach(Anacreation\Cms\Plugin\CmsPluginCollection::Instance()->getPlugins() as $plugin)
				@if($entryPath = $plugin->getEntryPath())
					<li><a class="nav-link"
					       href="{{"/".config('admin.route_prefix')."/".$entryPath}}">{{$plugin->getEntryPathName()}}</a></li>
				@endif
			@endforeach
        </ul>
    </li>
