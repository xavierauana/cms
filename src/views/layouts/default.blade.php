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
    <link href="{{ asset('css/cms/app.css') }}" rel="stylesheet">
	@yield("stylesheets")
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">

                <!-- Branding Image -->
	                <a class="navbar-brand" href="{{ url('/') }}">
                        A & A CMS
                    </a>
                
                    
                    <div class="collapse navbar-collapse"
                         id="navbarSupportedContent">
                        
                        
                         <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            
                             @auth('admin')
		
		                        @include('cms::components.admin_menus')
	
	                        @endif
                         
    
                        </ul>


                         <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
	                        @if($user = Auth::guard('admin')->user('admin'))
		
		                        <li class="nav-item dropdown">
                                    <a id="navbarDropdown"
                                       class="nav-link dropdown-toggle" href="#"
                                       role="button" data-toggle="dropdown"
                                       aria-haspopup="true"
                                       aria-expanded="false">
                                        {{ $user->name }} <span
			                                    class="caret"></span>
                                    </a>
    
                                    <div class="dropdown-menu"
                                         aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item"
                                           href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
    
                                        <form id="logout-form"
                                              action="{{ route('logout') }}"
                                              method="POST"
                                              style="display: none;">
                                            {{csrf_field()}}
                                        </form>
                                    </div>
                                </li>
	
	                        @else
		
		                        <li><a class="nav-link"
		                               href="{{ route('login') }}">Login</a></li>
		                        <li><a class="nav-link"
		                               href="{{ route('register') }}">Register</a></li>
	                        @endguest
                        </ul>
                        
                    </div>
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
