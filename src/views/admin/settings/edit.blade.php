@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Update {{$setting->label}} @endslot
		
		{{Form::model($setting, ['route'=>["settings.update", $setting->id], 'method'=>'PUT'])}}
		
		<div class="form-group">
			{{Form::label('label', 'Label')}}
			{{Form::text('label', null, ['class'=>'form-control', 'placeholder'=>'Setting Label', 'required'])}}
			@if ($errors->has('label'))
				<span class="help-block">
                    <strong>{{ $errors->first('label') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('value', 'Value')}}
			{{Form::text('value', null, ['class'=>'form-control', 'placeholder'=>'Value','required'])}}
			@if ($errors->has('value'))
				<span class="help-block">
                    <strong>{{ $errors->first('value') }}</strong>
                </span>
			@endif
		</div>
		
		
		<div class="form-group">
			{{Form::submit('Update', ['class'=>'btn btn-success'])}}
			<a href='{{route('settings.index')}}' class="btn btn-info">Back</a>
		</div>
		
		
		{{Form::close()}}
	@endcomponent


@endsection