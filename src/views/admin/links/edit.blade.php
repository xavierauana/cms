@extends("cms::layouts.default")

@section("content")
	@component('cms::components.container')
		@slot('title')Edit Link: {{$link->name}} @endslot
		
		{{Form::model($link, ['url'=>route("menus.links.update", [$menu->id, $link->id]), 'method'=>'PUT'])}}
		
		<div class="form-group">
			{{Form::label('external_uri', 'External Link')}}
			{{Form::text('external_uri', $link->external_uri, ['class'=>'form-control', 'placeholder'=>'page uri'])}}
			@if ($errors->has('external_uri'))
				<span class="help-block">
                    <strong>{{ $errors->first('external_uri') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('page_id', 'Link to Page')}}
			{{Form::select('page_id', $pages, $link->page_id, ['class'=>'form-control'])}}
			@if ($errors->has('page_id'))
				<span class="help-block">
                    <strong>{{ $errors->first('page_id') }}</strong>
                </span>
			@endif
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
						{{Form::text("name[$index][content]", $link->getNameAttribute($language->code), ['class'=>'form-control', 'placeholder'=>'Link Name'])}}
						{{Form::hidden("name[$index][lang_id]", $language->id)}}
						@if ($errors->has("name.{$index}.content"))
							<span class="help-block">
                                <strong>{{ $errors->first("name.{$index}.content") }}</strong>
                            </span>
						@endif
					@endcomponent
				@endforeach
			  </div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', 'Is Active')}}
			{{Form::select('is_active', [0=>'No', 1=>'Yes'],$link->is_active,['class'=>'form-control'])}}
			@if ($errors->has('is_active'))
				<span class="help-block">
                    <strong>{{ $errors->first('is_active') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('parent_id', 'Parent Link')}}
			{{Form::select('parent_id', $links ,$link->parent_id,['class'=>'form-control'])}}
			@if ($errors->has('parent_id'))
				<span class="help-block">
                                        <strong>{{ $errors->first('parent_id') }}</strong>
                                    </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::submit('Update', ['class'=>'btn btn-success'])}}
			<a href='{{route('menus.index')}}' class="btn btn-info">Back</a>
		</div>
		{{Form::close()}}
	@endcomponent


@endsection