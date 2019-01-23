@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Create new setting @endslot
		
		{{Form::open(['route'=>["settings.store"], 'method'=>'POST'])}}
		
		@include('cms::admin.settings._partials.form')
		
		<div class="form-group">
			{{Form::submit('Create', ['class'=>'btn btn-success'])}}
			<a href='{{route('settings.index')}}' class="btn btn-info">Back</a>
		</div>
		
		
		{{Form::close()}}
	@endcomponent


@endsection