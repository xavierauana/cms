@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')All Languages <a href="/admin/languages/create"
		                               class="btn btn-sm btn-success pull-right">Create Language</a> @endslot
		
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
				
				@foreach($languages as $language)
					<tr data-id="{{$language->id}}">
						<td>{{$language->label}}</td>
						<td>{{$language->code}}</td>
						<td>{{$language->is_active?"Yes":"No"}}</td>
						<td>{{$language->is_default?"Yes":"No"}}</td>
						<td>
							<div class="btn-group btn-group-sm">
								<a href="/admin/languages/{{$language->id}}/edit"
								   class="btn btn-info">Edit</a>
								<button class="btn btn-danger"
								        @click.prevent="deleteItem({{$language->id}})">Delete</button>
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