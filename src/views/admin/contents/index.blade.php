@extends("cms::layouts.default")

@section("content")
	
	
	@component('cms::components.container')
		@slot('title'){{$page->uri}}
		@if(auth('admin')->user()->hasPermission('edit_design'))
			<a href="/admin/designs/edit/layouts?file={{$page->template}}"
			   class="btn btn-primary btn-sm pull-right">Edit Template: {{$page->template}}</a>
		@endif
		@endslot
		
		
		<content-blocks :contents="{{ json_encode($contents) }}"
		                :editable="{{auth()->user()->hasPermission('edit_content')?1:0}}"
		                :deleteable="{{auth()->user()->hasPermission('delete_content')?1:0}}"
		                :languages="{{$languages}}"
		                :can-add="{{$page->editable?1:0}}"
		></content-blocks>
		
		
		<page-children v-if="{{$page->has_children}} === 1"
		               :page="{{$page}}"
		               :children="{{$page->children()->with('permission')->sorted()->get()}}"></page-children>
	
	
	@endcomponent


@endsection