@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')All Roles
		@if(Auth::guard('admin')->users()->hasPermission('create_role'))
			<a href="{{route('roles.create')}}"
			   class="btn btn-sm btn-success pull-right">Create Role</a>
		@endif
		@endslot
		
		<div class="table-responsive">
			<delete-item url="/admin/roles/"
			             inline-template>
				<table class="table table-hover" ref="table">
				<thead>
					<tr>
						<th>Label</th>
						<th>Code</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach($roles as $role)
						<tr data-id="{{$role->id}}">
							<td>{{$role->label}}</td>
							<td>{{$role->code}}</td>
							<td>
								<div class="btn-group btn-group-sm">
									@if(Auth::guard('admin')->users()->hasPermission('edit_role'))
										<a href="{{route('roles.edit', $role->id)}}"
										   class="btn btn-info">Edit</a>
									@endif
									@if(Auth::guard('admin')->users()->hasPermission('delete_role'))
										<button class="btn btn-danger"
										        @click.prevent="deleteItem({{$role->id}})">Delete</button>
									@endif
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			</delete-item>
		</div>
	@endcomponent


@endsection