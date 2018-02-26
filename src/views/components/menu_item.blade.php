<sortable-list id="{{$id}}">
	@foreach($menu->links as $link)
		@if($link->children->count())
			@include("cms::components.menu_item",['links'=>$link->children, 'id'=>$id."-submenu-".$link])
		@else
			<li>{{$link->name}}</li>
		@endif
	@endforeach
</sortable-list>

