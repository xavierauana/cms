@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Create Admin @endslot
		
		{{Form::open(['route'=>["administrators.store"], 'method'=>'POST'])}}
		
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
			{{Form::label('password', 'Password')}}
			{{Form::password('password', ['class'=>'form-control', 'placeholder'=>'Email'])}}
			@if ($errors->has('password'))
				<span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('password_confirmation', 'Password Confirmation')}}
			{{Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>'Email'])}}
			@if ($errors->has('password_confirmation'))
				<span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('roles[]', 'Roles')}}
			{{Form::select('roles[]',$roles ,null, ['class'=>'form-control', 'multiple'])}}
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