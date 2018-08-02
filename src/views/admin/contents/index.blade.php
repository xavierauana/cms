@extends("cms::layouts.default")

@section("content")
	
	
	@component('cms::components.container')
		@slot('title'){{$page->uri}}
		@if(auth('admin')->user()->hasPermission('edit_design'))
			<a href="{{route('designs.edit', 'layouts')."?file={$page->template}"}}"
			   class="btn btn-primary btn-sm pull-right">Edit Template: {{$page->template}}</a>
		@endif
		@endslot
		
		<content-blocks
				:contents="{{ count($contents) > 0 ? json_encode($contents) : json_encode(new stdClass()) }}"
				:editable="{{json_encode(auth()->user()->hasPermission('edit_content'))}}"
				:deleteable="{{json_encode(auth()->user()->hasPermission('delete_content'))}}"
				:languages="{{$languages}}"
				:can-add="{{json_encode($page->editable)}}"
				:types="{{json_encode((new \Anacreation\Cms\Services\ContentService())->getTypesForJs())}}"
		></content-blocks>
		
		
		<page-children v-if="{{json_encode($page->has_children)}} "
		               base-url="{{route('pages.index')}}"
		               :page="{{$page}}"
		               :children="{{$page->children()->with('permission')->sorted()->get()}}"></page-children>
	
	
	@endcomponent


@endsection