@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')All Pages <a href="{{route('permissions.index')}}"
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
								<a href="{{route('permissions.edit', $permission->id)}}"
								   class="btn btn-info">Edit</a>
								<delete-item
										url="{{route('permissions.destroy', $permission->id)}}"
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