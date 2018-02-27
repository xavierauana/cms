@extends("themes.default.partials.default")

@section("meta_title") A & A Creation - {{getContent($page, 'meta_title')}} @endsection

@section("content")
	
	<div class='container text-center'>
		
		<h2>{!! $page->getContent('date') !!}</h2>
		
		<video poster="/{{$page->getContent('poster')}}" controls>
			
			<source src="{{ $page->getContent('video') }}">
			
		</video>
		
		{!! $page->getContent('summary') !!}
		
		{!! $page->getContent('content') !!}
		
		<p>file name: {{$page->getContent('file')}}</p>
		<p>{{isset($user)?"YES":"NO"}}</p>
		<p>{{isset($template)?"YES":"NO"}}</p>

    </div>
@endsection