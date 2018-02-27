@extends("themes.default.partials.default")

@section("meta_title") A & A Creation - {{getContent($page, 'meta_title')}} @endsection

@section("content")
	
	<div class='container text-center'>
		{!! $page->getContent('content') !!}
    </div>
	
	@if($page->getContent('boo'))
		<div class='container text-center'>
		{!! $page->getContent('controlled_content') !!}
    </div>
	@endif

@endsection