@extends('themes.default.partials.default')

@section("content")
	<h1>{!! $page->getContent('title') !!}</h1>
	<p>{!! $page->getContent('content')!!}</p>
@endsection