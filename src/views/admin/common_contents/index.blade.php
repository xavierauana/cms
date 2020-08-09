@extends("cms::layouts.default")

@section("content")

	@component('cms::components.container')
		@slot('title')
			All Pages
			@if(Auth::guard('admin')->user()->can('create',\Anacreation\Cms\Models\CommonContent::class))
				<a href="{{route('cms::common_contents.create')}}"
				   class="btn btn-sm btn-success pull-right">Create Common Content</a>
			@endif
		@endslot
		<form class="form" method="GET" action="{{route('cms::common_contents.index')}}">
			<div class="row">
				<div class="col col-sm-6 col-md-4 col-lg-3 ml-auto">
					<div class="input-group mb-3">
					  <input type="text" class="form-control"
					         name="keyword"
					         placeholder="Keyword"
					         aria-label="Keyword"
					         value="{{request()->query('keyword')}}"
					         aria-describedby="button-addon2">
					  <div class="input-group-append">
					    <button class="btn btn-outline-secondary" type="submit"
					            id="button-addon2">Search</button>
					  </div>
					</div>
				</div>
			</div>
		</form>

		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th><a href="{{sortableLink('label')}}">Label</a> </th>
						<th><a href="{{sortableLink('key')}}">Key</a></th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@each('cms::admin.common_contents.tableRow', $commonContents, 'content')
				</tbody>
			</table>
			{{$commonContents->appends(request()->query())->links("pagination::bootstrap-4")}}
		</div>



	@endcomponent
@endsection
