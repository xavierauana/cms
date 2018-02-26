@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Create New Link for Menu: {{$menu->name}} @endslot
		
		{{Form::open(['url'=>"/admin/menus/{$menu->id}/links", 'method'=>'POST'])}}
		
		<div class="form-group">
			{{Form::label('external_uri', 'External Link')}}
			{{Form::text('external_uri', '', ['class'=>'form-control', 'placeholder'=>'page uri'])}}
		</div>
		<div class="form-group">
			{{Form::label('page_id', 'Link to Page')}}
			{{Form::select('page_id', $pages ,null,['class'=>'form-control'])}}
		</div>
		<div class="form-group">
			@if($languages->count()>1)
				<ul class="nav nav-tabs" role="tablist">
					 @foreach($languages as $language)
						@component('cms::elements.tabheading', [
						'isActive'=>$language->is_default,
						'tabPanelId'=>$language->code
						])
							{{$language->label}}
						@endcomponent
					@endforeach
			  </ul>
			@endif
			<div class="tab-content">
				@foreach($languages  as $index=>$language)
					@component('cms::elements.tabpanel', [
					'isActive'=>$language->is_default,
					'tabPanelId'=>$language->code
					])
						{{Form::label("name[$index][content]", 'Name')}}
						{{Form::text("name[$index][content]", '', ['class'=>'form-control', 'placeholder'=>'Link Name'])}}
						{{Form::hidden("name[$index][lang_id]", $language->id)}}
					@endcomponent
				@endforeach
			  </div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', 'Is Active')}}
			{{Form::select('is_active', [0=>'No', 1=>'Yes'],0,['class'=>'form-control'])}}
		</div>
		<div class="form-group">
			{{Form::label('parent_id', 'Parent Link')}}
			{{Form::select('parent_id', $links ,null,['class'=>'form-control'])}}
		</div>
		<div class="form-group">
			{{Form::submit('Create', ['class'=>'btn btn-success'])}}
			<a href='{{route('menus.index')}}' class="btn btn-info">Back</a>
		</div>
		
		
		{{Form::close()}}
	@endcomponent

@endsection