@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Create New Permission @endslot
		
		{{Form::open(['url'=>route('permissions.store'), 'method'=>'POST'])}}
		
		<div class="form-group">
			{{Form::label('label', 'Label')}}
			{{Form::text('label', '', ['class'=>'form-control', 'placeholder'=>'Permission Label'])}}
		</div>
		
		<div class="form-group">
			{{Form::label('code', 'Code')}}
			{{Form::text('code',null,['class'=>'form-control','placeholder'=>'Permission Code'])}}
		</div>
		
		<div class="form-group">
			{{Form::submit('Create', ['class'=>'btn btn-success'])}}
			<a href='{{route('permissions.index')}}' class="btn btn-info">Back</a>
		</div>
		
		
		{{Form::close()}}
	@endcomponent


@endsection