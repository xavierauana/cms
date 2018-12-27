@extends("themes.default.partials.default")

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