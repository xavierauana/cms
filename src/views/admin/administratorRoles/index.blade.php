@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')All Admin Roles
		@if(Auth::guard('admin')->user()->hasPermission('create_admin_role'))
			<a href="{{route('admin_roles.create')}}"
			   class="btn btn-sm btn-success pull-right">Create Admin Role</a>
		@endif
		@endslot
		
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Label</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				@foreach($roles as $role)
					<tr>
						<td>{{$role->label}}</td>
						
						<td>
							<div class="btn-group btn-group-sm">
								@if(Auth::guard('admin')->user()->hasPermission('edit_admin_role'))
									<a href="{{route('admin_roles.edit', $role->id)}}"
									   class="btn btn-info">Edit</a>
								@endif
								@if(Auth::guard('admin')->user()->hasPermission('delete_admin_role'))
									<delete-item
											url="{{route('admin_roles.destroy', $role->id)}}"
											inline-template>
										<button class="btn btn-danger"
										        @click.prevent="deleteItem">Delete</button>
									</delete-item>
								@endif
							</div>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	@endcomponent


@endsection