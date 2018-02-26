@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Create New Role @endslot
		
		{{Form::open(['url'=>"/admin/roles", 'method'=>'POST'])}}
		
		<div class="form-group">
			{{Form::label('label', 'Label')}}
			{{Form::text('label', '', ['class'=>'form-control', 'placeholder'=>'Role Label','required'])}}
			@if ($errors->has('label'))
				<span class="help-block">
                                        <strong>{{ $errors->first('label') }}</strong>
                                    </span>
			@endif
		</div>
		
		<div class="form-group">
			{{Form::label('code', 'Code')}}
			{{Form::text('code',null,['class'=>'form-control','placeholder'=>'Role Code','required'])}}
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
								{{Form::checkbox('permissions[]', $permission->id,null)}}
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
			{{Form::submit('Create', ['class'=>'btn btn-success'])}}
			<a href='{{route('pages.index')}}' class="btn btn-info">Back</a>
		</div>
		
		
		{{Form::close()}}
	@endcomponent


@endsection