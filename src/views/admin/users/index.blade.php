@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')All Roles <a href="{{route('roles.create')}}"
		                           class="btn btn-sm btn-success pull-right">Create Role</a> @endslot
		
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
									
									<a href="{{route('roles.edit', $role->id)}}"
									   class="btn btn-info">Edit</a>
									<button class="btn btn-danger"
									        @click.prevent="deleteItem({{$role->id}})">Delete</button>
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