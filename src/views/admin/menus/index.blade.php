@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')
			All Menus
			<a href="{{route('menus.create')}}"
			   class="btn btn-sm btn-success pull-right">Create Menu Group</a>
		@endslot
		
		@component('cms::elements.accordion_container',['id'=>'menus_accrodion'])
			
			@foreach($menus as $index=>$menu)
				@include("cms::components.menu")
			@endforeach
		
		@endcomponent
	
	@endcomponent

@endsection