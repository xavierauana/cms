@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')All Languages
		@if(Auth::guard('admin')->user()->hasPermission('create_language'))
			<a href="{{route('languages.create')}}"
			   class="btn btn-sm btn-success pull-right">Create Language</a>
		@endif
		@endslot
		
		<div class="table-responsive">
			<delete-item url="/admin/languages/" inline-template>
			<table class="table table-hover" ref="table">
				<thead>
					<tr>
						<th>Label</th>
						<th>Code</th>
						<th>Is Active</th>
						<th>Is Default</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@each('cms::admin.languages.tableRow', $languages, 'language')
				</tbody>
			</table>
			</delete-item>
		</div>
	@endcomponent
@endsection