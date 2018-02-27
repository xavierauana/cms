<ol style="min-height: 10px;"
    data-parentId="{{$parentId}}"
    class="connectedSortable">
	@foreach($links as $link)
		<li data-id="{{$link->id}}"
		    data-order="{{$link->order}}"
		    data-parentId="{{$parentId}}"
		    style="margin-bottom: 10px;position: relative">
			<span>{{$link->name}}</span>
			<small>(uri: {{$link->uri}})</small>
			@if($link->is_active)
				<span class="label label-success">Active</span>
			@else
				<span class="label label-warning">Inactive</span>
			@endif
			<form action="{{route('menus.links.destroy', [$menu->id, $link->id])}}"
			      method="POST" style="display: inline"
			      @submit="confirmDelete($event,'Delete this link?')">
				{{method_field('delete')}}
				{{csrf_field()}}
				<button class="btn btn-danger btn-xs pull-right"
				        style="margin-left: 10px"
				>Delete</button>
			</form>
			
			<a class="btn btn-info btn-xs pull-right"
			   href="{{route('menus.links.edit', [$menu->id, $link->id])}}"
			>Edit</a>
			
			@include('cms::components.links',['links'=>$link->children()->orderBy('order')->get(), 'parentId'=>$link->id])
		</li>
	@endforeach
</ol>