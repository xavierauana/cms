@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Edit Admin Role: {{$role->name}} @endslot
		
		{{Form::model($role, ['route'=>["admin_roles.update", $role], 'method'=>'PUT'])}}
		
		<div class="form-group">
			{{Form::label('label', 'Label')}}
			{{Form::text('label',null, ['class'=>'form-control', 'placeholder'=>'Name'])}}
			@if ($errors->has('label'))
				<span class="help-block">
                    <strong>{{ $errors->first('label') }}</strong>
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
									       name="permissions[]"
									       @if($role->permissions->contains($permission->id)) checked @endif />
									{{$permission->label}}
								</label>
							</div>
					</div>
				@endforeach
			</div>
		</fieldset>
		
		<div class="form-group">
			{{Form::submit('Update', ['class'=>'btn btn-success'])}}
			<a href='{{route('administrators.index')}}' class="btn btn-info">Back</a>
		</div>
		
		{{Form::close()}}
	@endcomponent


@endsection