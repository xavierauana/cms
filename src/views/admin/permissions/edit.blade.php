@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Edit Permission: {{$permission->label}} @endslot
		
		{{Form::model($permission, ['route'=>["permissions.update", $permission->id], 'method'=>'PUT'])}}
		
		<div class="form-group">
			{{Form::label('label', 'Label')}}
			{{Form::text('label', $permission->label, ['class'=>'form-control', 'placeholder'=>'Permission Label'])}}
			@if ($errors->has('label'))
				<span class="help-block">
                                        <strong>{{ $errors->first('label') }}</strong>
                                    </span>
			@endif
		</div>
		
		<div class="form-group">
			{{Form::label('code', 'Code')}}
			{{Form::text('code',$permission->code,['class'=>'form-control','placeholder'=>'Permission Code'])}}
			@if ($errors->has('code'))
				<span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
			@endif
		</div>
		
		<div class="form-group">
			{{Form::submit('Update', ['class'=>'btn btn-success'])}}
			<a href='{{route('permissions.index')}}' class="btn btn-info">Back</a>
		</div>
		
		{{Form::close()}}
	@endcomponent

@endsection