<?php
$class = (isset($class) and (!isset($subMenu) or $subMenu === false)) ? $class : null;
$hasSubMenu = isset($subMenu) and $subMenu === true;
$hasChildForTheMenu = !!$links->first(function (
    \Anacreation\Cms\Models\Link $link
) {
    return $link->hasChild();
});
?>


<ul class="list-unstyled menu {{$class}} @if($hasChildForTheMenu) dropdown @endif @if($hasSubMenu) dropdown-menu sub_menu @endif">
	@foreach($links as $link)
		<li role="presentation"
		    class="level-{{$level}} @if($hasChild = $link->hasChild()) dropdown @endif">
			
			<a @if($hasChild)
			   data-toggle="dropdown"
			   role="button"
			   aria-haspopup="true"
			   aria-expanded="false"
			   href="#"
			   class="dropdown-toggle"
			   @else
			   href="{{$link->uri}}"
					@endif>{{$link->name}}
				@if($hasChild)
					<span class="caret"></span>
				@endif
			</a>
			@if($hasChild)
				@include("cms::components.menu_template", [
				'links'=>$link->children,
				'subMenu'=>true,
				'level'=>$level+1
				])
			@endif
		</li>
	@endforeach
</ul>