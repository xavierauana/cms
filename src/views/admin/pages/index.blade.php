@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')
			All Pages
			@if(Auth::guard('admin')->user()->hasPermission('create_page'))
				<a href="{{route('pages.create')}}"
				   class="btn btn-sm btn-success pull-right">Create Page</a>
			@endif
		@endslot
		
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
					@each('cms::admin.pages.tableRow', $pages, 'page')
				</tbody>
			</table>
		</div>
	@endcomponent
@endsection