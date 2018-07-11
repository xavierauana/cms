@extends("cms::layouts.default")

@section("content")
	
	
	@component('cms::components.container')
		@slot('title'){{$page->uri}}
		@if(auth('admin')->user()->hasPermission('edit_design'))
			<a href="{{route('designs.edit', 'layouts')."?file={$page->template}"}}"
			   class="btn btn-primary btn-sm pull-right">Edit Template: {{$page->template}}</a>
		@endif
		@endslot
		
		
		<content-blocks :contents="{{ json_encode($contents) }}"
		                :editable="{{auth()->user()->hasPermission('edit_content')}}=='1'"
		                :deleteable="{{auth()->user()->hasPermission('delete_content')}}=='1'"
		                :languages="{{$languages}}"
		                :can-add="{{$page->editable}}=='1'"
		                :types="{{json_encode((new \Anacreation\Cms\Services\ContentService())->getTypesForJs())}}"
		></content-blocks>
		
		
		<page-children v-if="{{$page->has_children}} == '1'"
		               base-url="{{route('pages.index')}}"
		               :page="{{$page}}"
		               :children="{{$page->children()->with('permission')->sorted()->get()}}"></page-children>
	
	
	@endcomponent


@endsection