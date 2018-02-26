@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')All Pages <a href="/admin/permissions/create"
		                           class="btn btn-sm btn-success pull-right">Create Page</a> @endslot
		
		<div class="table-responsive">
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
								<a href='/admin/permissions/{{$permission->id}}/contents'
								   class="btn btn-primary">Content</a>
								<a href="/admin/permissions/{{$permission->id}}/edit"
								   class="btn btn-info">Edit</a>
								<delete-item
										url="/admin/permissions/{{$permission->id}}"
										inline-template>
								<button class="btn btn-danger"
								        @click.prevent="deleteItem">Delete</button>
									</delete-item>
							</div>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	@endcomponent


@endsection