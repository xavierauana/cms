@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')All Pages
		@if(Auth::guard('admin')->users()->hasPermission('create_permission'))
			<a href="{{route('permissions.index')}}"
			   class="btn btn-sm btn-success pull-right">Create Page</a>
		@endif
		@endslot
		
		<div class="table-responsive">
			<delete-item
					url="{{route('permissions.destroy', $permission->id)}}"
					inline-template>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Label</th>
							<th>Code</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
					@foreach($permissions as $permission)
						<tr>
							<td>{{$permission->label}}</td>
							<td>{{$permission->code}}</td>
							<td>
								<div class="btn-group btn-group-sm">
									@if(Auth::guard('admin')->users()->hasPermission('edit_permission'))
										<a href="{{route('permissions.edit', $permission->id)}}"
										   class="btn btn-info">Edit</a>
									@endif
									@if(Auth::guard('admin')->users()->hasPermission('delete_permission'))
										<button class="btn btn-danger"
										        @click.prevent="deleteItem">Delete</button>
										s@endif
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