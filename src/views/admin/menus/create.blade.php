@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Create New Menu @endslot
		
		{{Form::open(['url'=>"/admin/menus", 'method'=>'POST'])}}
		
		<div class="form-group">
			{{Form::label('name', 'Name')}}
			{{Form::text('name', '', ['class'=>'form-control', 'placeholder'=>'Menu Name'])}}
		</div>
		<div class="form-group">
			{{Form::label('code', 'Name')}}
			{{Form::text('code', '', ['class'=>'form-control', 'placeholder'=>'Menu Code'])}}
		</div>
		
		<div class="form-group">
			{{Form::submit('Create', ['class'=>'btn btn-success'])}}
			<a href='{{route('menus.index')}}' class="btn btn-info">Back</a>
		</div>
		
		
		{{Form::close()}}
	@endcomponent

@endsection