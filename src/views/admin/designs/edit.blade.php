@extends("cms::layouts.default")

@push('header-scripts')
	<link rel="stylesheet"
	      href="{{asset('css/cms/codemirror/lib/codemirror.css')}}">
	<link rel="stylesheet"
	      href="{{asset('js/cms/codemirror/addon/fold/foldgutter.css')}}">
	<link rel="stylesheet"
	      href="{{asset('css/cms/codemirror/theme/dracula.css')}}">
	<script src=" {{asset('js/cms/codemirror/lib/codemirror.js')}}"></script>
	@foreach(File::files(public_path('js/cms/codemirror/addon/fold')) as $addonFile)
		@if($addonFile->getExtension() === "js")
			<script src="{{asset('js/cms/codemirror/addon/fold/'.$addonFile->getFileName())}}"></script>
		@endif
	@endforeach
	<script src="{{asset('js/cms/codemirror/mode/javascript/javascript.js')}}"></script>
	<script src="{{asset('js/cms/codemirror/mode/css/css.js')}}"></script>
	<script src="{{asset('js/cms/codemirror/mode/htmlmixed/htmlmixed.js')}}"></script>
	<script src="{{asset('js/cms/codemirror/mode/xml/xml.js')}}"></script>
	<script src="{{asset('js/cms/codemirror/mode/php/php.js')}}"></script>
@endpush

@section("content")
	<div class="container">
		<div class="mt-3 card card-default">
			<!-- Default panel contents -->
			<form class="form"
			      action="{{route('update.design',[$type,'file'=>$file])}}"
			      method="POST"
			      id="edit-form">
				{{csrf_field()}}
				{{method_field("PUT")}}
				<input type="hidden" id="code" name="code" />
				
			</form>
			<h2 class="card-header">Edit Design: {{$file}}</h2>
		        <code-editor id="codeEditor"
		                     type="{{$type}}"
		                     content_uri="{{Request::url(). "?file=".$file}}"></code-editor>
		</div>
	</div>
@endsection