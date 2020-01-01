@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Update {{$setting->label}} @endslot
		
		{{Form::model($setting, ['route'=>["settings.update", $setting->id], 'method'=>'PUT'])}}
		
		@include('cms::admin.settings._partials.form')
		
		<div class="form-group">
			{{Form::submit('Update', ['class'=>'btn btn-success'])}}
			<a href='{{route('settings.index')}}' class="btn btn-info">Back</a>
		</div>
		
		
		{{Form::close()}}
	@endcomponent


@endsection