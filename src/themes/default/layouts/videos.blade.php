@extends("themes.default.partials.default")

@section("meta_title") A & A Creation - {{$page->getContent('meta_title')}} @endsection

@section("content")
	<div class='container'>
        <ul>
            @foreach ( $page->children as $child)
		        <li><a href="{{$page->uri}}/{{$child->uri}}">{{$child->getContent("title")}}</a></li>
	        @endforeach
        </ul>
    </div>
@endsection