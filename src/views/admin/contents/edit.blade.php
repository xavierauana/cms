@extends("cms::layouts.default")

@section("content")
	@component('cms::components.container')
		@slot('title'){{$child->uri}}
		@if(auth('admin')->user()->hasPermission('edit_design'))
			<a href="{{route('designs.edit', 'layouts')."?file={$child->template}"}}"
			   class="btn btn-primary btn-sm pull-right">Edit Template: {{$child->template}}</a>
		@endif
		@endslot
		<content-blocks :contents="{{json_encode($contents)}}"
		                :editable="{{auth()->user()->hasPermission('edit_content')?1:0}}"
		                :deleteable="{{auth()->user()->hasPermission('delete_content')?1:0}}"
		                :languages="{{\Anacreation\Cms\Models\Language::all()}}"
		                :types="{{json_encode((new \Anacreation\Cms\Services\ContentService())->getTypesForJs())}}"
		></content-blocks>
	@endcomponent


@endsection