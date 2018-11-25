@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Create New Menu @endslot
		
		{{Form::open(['url'=>route('menus.store'), 'method'=>'POST'])}}
		
		<div class="form-group">
			{{Form::label('name', 'Name')}}
			{{Form::text('name', '', ['class'=>'form-control', 'placeholder'=>'Menu Name'])}}
			@if ($errors->has('name'))
				<span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('code', 'Code')}}
			{{Form::text('code', '', ['class'=>'form-control', 'placeholder'=>'Menu Code'])}}
			@if ($errors->has('code'))
				<span class="help-block">
                    <strong>{{ $errors->first('code') }}</strong>
                </span>
			@endif
		</div>
		
		<div class="form-group">
			{{Form::submit('Create', ['class'=>'btn btn-success'])}}
			<a href='{{route('menus.index')}}' class="btn btn-info">Back</a>
		</div>
		
		
		{{Form::close()}}
	@endcomponent

@endsection