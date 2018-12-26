@extends("cms::layouts.default")

@section("content")
	<div class="container">
		
		{{Form::model($user, ['route'=>['profile.update', $user], 'method'=>'PUT'])}}
		
		{{csrf_field()}}
		
		<legend>My Profile</legend>
		
			<div class="form-group">
				{{Form::label('name', 'Name')}}
				{{Form::text('name', $user->name, ['class'=>'form-control'])}}
				@if ($errors->has('name'))
					<span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
				@endif
			</div>
			<div class="form-group">
				{{Form::label('email', 'Email')}}
				{{Form::text('email', $user->email, ['class'=>'form-control'])}}
				@if ($errors->has('email'))
					<span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
				@endif
			</div>
		
			<div class="form-group">
				{{Form::label('password', 'Password')}}
				{{Form::password('password', ['class'=>'form-control', 'placeholder'=>'Only if you want to change password'])}}
				@if ($errors->has('password'))
					<span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
				@endif
			</div>
		
			<div class="form-group">
				{{Form::label('password_confirmation', 'Password Confirmation')}}
				{{Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>'Only if you want to change password'])}}
			</div>
		
			<div class="form-group">
				{{Form::submit('Update', ['class'=>'btn btn-success'])}}
				<a href="/admin" class="btn btn-info">Back</a>
			</div>
		
		
		{{Form::close()}}
	
</div>


@endsection