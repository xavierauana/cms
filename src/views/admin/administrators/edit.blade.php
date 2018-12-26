@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Edit Admin: {{$admin->name}} @endslot
		
		{{Form::model($admin, ['route'=>["administrators.update", $admin], 'method'=>'PUT'])}}
		
		<div class="form-group">
			{{Form::label('name', 'Name')}}
			{{Form::text('name',null, ['class'=>'form-control', 'placeholder'=>'Name'])}}
			@if ($errors->has('name'))
				<span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('email', 'Email')}}
			{{Form::email('email',null, ['class'=>'form-control', 'placeholder'=>'Email'])}}
			@if ($errors->has('email'))
				<span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('roles[]', 'Roles')}}
			{{Form::select('roles[]',$roles ,$admin->roles->pluck('id'), ['class'=>'form-control', 'multiple'])}}
			@if ($errors->has('roles'))
				<span class="help-block">
                    <strong>{{ $errors->first('roles') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::submit('Update', ['class'=>'btn btn-success'])}}
			<a href='{{route('administrators.index')}}' class="btn btn-info">Back</a>
		</div>
		
		{{Form::close()}}
	@endcomponent


@endsection