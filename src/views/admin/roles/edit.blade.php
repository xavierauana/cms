@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Edit Role: {{$role->label}} @endslot
		
		{{Form::model($role, ['route'=>["roles.update", $role->id], 'method'=>'PUT'])}}
		
		<div class="form-group">
			{{Form::label('label', 'Label')}}
			{{Form::text('label', $role->label, ['class'=>'form-control', 'placeholder'=>'Role Label'])}}
			@if ($errors->has('label'))
				<span class="help-block">
                                        <strong>{{ $errors->first('label') }}</strong>
                                    </span>
			@endif
		</div>
		
		<div class="form-group">
			{{Form::label('code', 'Code')}}
			{{Form::text('code',$role->code,['class'=>'form-control','placeholder'=>'Role Code'])}}
			@if ($errors->has('code'))
				<span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
			@endif
		</div>
		
		<section>
			<legend>All Permissions</legend>
			<table class="table">
				<thead>
					<th>Select</th>
					<th>Label</th>
				</thead>
				<tbody>
					@foreach($permissions as $permission)
						<tr>
							<td>
								{{Form::checkbox('permissions[]', $permission->id,in_array($permission->id, $role->permissions->pluck('id')->toArray()))}}
							</td>
							<td>
								{{$permission->label}}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</section>
		
		<div class="form-group">
			{{Form::submit('Update', ['class'=>'btn btn-success'])}}
			<a href='{{route('pages.index')}}' class="btn btn-info">Back</a>
		</div>
		
		{{Form::close()}}
	@endcomponent

@endsection