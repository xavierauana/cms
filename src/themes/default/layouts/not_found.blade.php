@extends("themes.default.partials.default")

@section("meta_title")

A & A Creation - Not Found

@endsection

@section("content")

    <div class='container'>
        <h1>Page is not Found!</h1>
        <div>{!! $page->getContent('content') !!}</div>
    </div>
@endsection