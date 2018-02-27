@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')All Pages <a href="{{route('pages.create')}}"
		                           class="btn btn-sm btn-success pull-right">Create Page</a> @endslot
		
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Uri</th>
						<th>Template</th>
						<th>Active</th>
						<th>Is Restricted</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				@foreach($pages as $page)
					<tr>
						<td>{{$page->uri}}</td>
						<td>{{$page->template}}</td>
						<td>{{$page->is_active?"Yes":"No"}}</td>
						<td>{{$page->is_restricted?$page->showPermission():"No"}}</td>
						<td>
							<div class="btn-group btn-group-sm">
								<a href='{{route('contents.index', $page->id)}}'
								   class="btn btn-primary">Content</a>
								<a href="{{route('pages.edit', $page->id)}}"
								   class="btn btn-info">Edit</a>
								<delete-item
										url="{{route('pages.destroy', $page->id)}}"
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