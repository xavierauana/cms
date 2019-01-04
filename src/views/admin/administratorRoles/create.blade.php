@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Create Admin Role @endslot
		
		{{Form::open(['route'=>["admin_roles.store"], 'method'=>'POST'])}}
		
		<div class="form-group">
			{{Form::label('label', 'Label')}}
			{{Form::text('label',null, ['class'=>'form-control', 'placeholder'=>'Name'])}}
			@if ($errors->has('label'))
				<span class="help-block">
                    <strong>{{ $errors->first('label') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('code','Admin Role Code',['class'=>'form-label'])}}
			{{Form::text('code',null,['class'=>$errors->has('code')?"form-control is-invalid":"form-control"])}}
			<p><small><em>This for system only and cannot be changed in future.</em></small></p>
			@if ($errors->has('code'))
				<span class="invalid-feedback">
		          <strong>{{ $errors->first('code') }}</strong>
		      </span>
			@endif
		</div>
		
		<fieldset>
			<legend>Permissions</legend>
			<div class="row">
				@foreach($permissions as $permission)
					<div class="col-6 col-sm-4 col-md-3">
							<div class="form-group">
								<label>
									<input type="checkbox"
									       value="{{$permission->id}}"
									       name="permissions[]" />
									{{$permission->label}}
								</label>
							</div>
					</div>
				@endforeach
			</div>
		</fieldset>
		
		<div class="form-group">
			{{Form::submit('Create', ['class'=>'btn btn-success'])}}
			<a href='{{route('administrators.index')}}' class="btn btn-info">Back</a>
		</div>
		
		{{Form::close()}}
	@endcomponent


@endsection