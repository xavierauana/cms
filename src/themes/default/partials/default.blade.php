<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	
	
	<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<title>@yield('meta_title', 'A & A CMS')</title>
		
		<meta name="description"
		      content="{{$page->getContent('meta_description')}}">
		<meta name="keywords" content="{{$page->getContent('meta_keywords')}}">
	<!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<script src="https://cdn.dashjs.org/latest/dash.all.min.js"></script>
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
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        @if(($languages = $language->activeLanguages())->count() > 1)
		                    @foreach($languages as $lang)
			                    @if(app()->getLocale() !== $lang->code)
				                    <li><a href="/setLocale/{{$lang->code}}">{{$lang->label}}</a></li>
			                    @endif
		                    @endforeach
	                    @endif
                    <!-- Authentication Links -->
	                    @guest
		                    <li><a href="{{ route('login') }}">Login</a></li>
		                    <li><a href="{{ route('register') }}">Register</a></li>
	                    @else
		                    <li class="dropdown">
                                <a href="#" class="dropdown-toggle"
                                   data-toggle="dropdown" role="button"
                                   aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span
			                                class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
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
            </div>
        </nav>
	
	    {!! \Anacreation\Cms\Models\Menu::renderWithCode('footer') !!}
	
	    @yield('content')
    </div>

    <!-- Scripts -->
    {{--<script src="{{ asset('js/app.js') }}"></script>--}}
</body>
</html>