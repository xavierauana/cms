<ol style="min-height: 10px;"
    data-parentId="{{$parentId}}"
    class="connectedSortable my-3">
@foreach($links as $link)
		<li data-id="{{$link->id}}"
		    data-order="{{$link->order}}"
		    data-parentId="{{$parentId}}"
		    class="my-3"
		    style="position: relative">
			<span>{{$link->name}}</span>
			<small>(uri: {{$link->uri}})</small>
			@if($link->is_active)
				<span class="badge badge-success">Active</span>
			@else
				<span class="badge badge-warning">Inactive</span>
			@endif
			<form action="{{route('menus.links.destroy', [$menu->id, $link->id])}}"
			      method="POST" style="display: inline"
			      @submit="confirmDelete($event,'Delete this link?')">
				{{method_field('delete')}}
				{{csrf_field()}}
				<div class="btn-group btn-group-sm float-right">
					<button class="btn btn-sm btn-danger"
					        style="margin-left: 10px"
					>Delete</button>
						<a class="btn btn-sm btn-info"
						   href="{{route('menus.links.edit', [$menu->id, $link->id])}}"
						>Edit</a>
				</div>
				
			</form>
			@include('cms::components.links',['links'=>$link->children()->order()->get(), 'parentId'=>$link->id])
		</li>
	@endforeach
</ol>