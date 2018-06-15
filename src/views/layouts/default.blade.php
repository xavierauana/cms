<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>A & A CMS</title>
	
	<!-- Styles -->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/cms/app.css') }}" rel="stylesheet">
    <link href="../../../../node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">
	@yield("stylesheets")
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed"
                            data-toggle="collapse"
                            data-target="#app-navbar-collapse"
                            aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        A & A CMS
                    </a>
                </div>
	
	            @auth('admin')
		            <div class="collapse navbar-collapse"
		                 id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
		            @include('cms::components.admin_menus')
		
		            <!-- Right Side Of Navbar -->
			            <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
				            @guest('admin')
					            <li><a href="{{ route('login') }}">Login</a></li>
					            <li><a href="{{ route('register') }}">Register</a></li>
				            @else
					            <li class="dropdown">
                                <a href="#" class="dropdown-toggle"
                                   data-toggle="dropdown" role="button"
                                   aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::guard('admin')->user()->name }}
	                                <span
			                                class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a href="/admin/profile">My Profile</a></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form"
                                              action="{{ route('logout') }}"
                                              method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
				            @endguest
                    </ul>
                </div>
	            @endauth
            </div>
        </nav>
	    @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{asset("vendor/cms/ckeditor/ckeditor.js")}}"></script>
    <script src="{{ asset('js/src-noconflict/ace.js') }}"></script>
    <script src="{{ asset('js/cms/manifest.js') }}"></script>
    <script src="{{ asset('js/cms/vendor.js') }}"></script>
    <script src="{{ asset('js/cms/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
