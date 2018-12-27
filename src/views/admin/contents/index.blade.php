@extends("cms::layouts.default")

@section("content")
	
	
	@component('cms::components.container')
		@slot('title'){{$page->uri}}
		@if(Auth::guard('admin')->user()->hasPermission('edit_layout'))
			<a href="{{route('designs.edit', 'layouts')."?file={$page->template}"}}"
			   class="btn btn-primary btn-sm pull-right">Edit Template: {{$page->template}}</a>
		@endif
		@endslot
		
		@include("cms::admin.contents.content_blocks",['contentOwner'=>$page])
		
		<page-children v-if="{{json_encode($page->has_children)}} "
		               base-url="{{route('pages.index')}}"
		               :page="{{$page}}"
		               :children="{{$page->children()->with('permission')->sorted()->get()}}"></page-children>
	
	
	@endcomponent


@endsection