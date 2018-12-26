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
			<h2 class="card-header">Create {{$type}}</h2>
		        <div class="card-body">
			        
			        {{Form::open(['url'=>route('designs.store',$type),'method'=>'POST'])}}
			        <div class="form-group">
			        	{{Form::label('file','Name',['class'=>'form-label'])}}
				        {{Form::text('file',null,['class'=>$errors->has('file')?"form-control is-invalid":"form-control"])}}
				        @if ($errors->has('file'))
					        <span class="invalid-feedback">
			                  <strong>{{ $errors->first('file') }}</strong>
			              </span>
				        @endif
			        </div>
			        
			        <div class="form-group">
				        {{Form::submit('Create',['class'=>"btn btn-success"])}}
			        </div>
			
			        {{Form::close()}}
		        </div>
		</div>
	</div>
@endsection