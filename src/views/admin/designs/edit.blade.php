@extends("cms::layouts.default")

@section("content")
	<div class="container">
		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">
				<h2>Edit Design: {{$file}}</h2>
			</div>
		    <div class="panel-body">
		        <code-editor id="codeEditor"
		                     content_uri="{{Request::url(). "?file=".$file}}"></code-editor>
		    </div>
		</div>
	</div>
@endsection