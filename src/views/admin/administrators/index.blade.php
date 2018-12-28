@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')All Administrators
		@if(Auth::guard('admin')->user()->hasPermission('create_admin'))
			<a href="{{route('administrators.create')}}"
			   class="btn btn-sm btn-success pull-right">Create Admin</a>
		@endif
		@endslot
		
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Roles</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				@foreach($administrators as $admin)
					<tr>
						<td>{{$admin->name}}</td>
						<td>{{$admin->email}}</td>
						<td>
							@foreach($admin->roles as $role)
								<a href="{{route('admin_roles.edit',$role) }}"><span
											class="badge badge-success">{{$role->label}}</span></a>
							@endforeach
						</td>
						<td>
							<div class="btn-group btn-group-sm">
								@if(Auth::guard('admin')->user()->hasPermission('edit_admin'))
									<a href="{{route('administrators.edit', $admin->id)}}"
									   class="btn btn-info">Edit</a>
								@endif
								@if(Auth::guard('admin')->user()->hasPermission('delete_admin'))
									<delete-item
											url="{{route('administrators.destroy', $admin->id)}}"
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