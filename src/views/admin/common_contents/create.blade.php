@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Create New Common Content @endslot
		
		{{Form::open(['url'=>route('cms::common_contents.store'), 'method'=>'POST','files'=>true])}}
		
		<div class="form-group">
			{{Form::label('label', 'Label')}}
			{{Form::text('label', '', ['class'=>'form-control', 'placeholder'=>'Label', 'required'])}}
			@if ($errors->has('label'))
				<span class="help-block">
                    <strong>{{ $errors->first('label') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('key', 'Identifier')}}
			{{Form::text('key', '', ['class'=>'form-control', 'placeholder'=>'Identifier','required'])}}
			@if ($errors->has('key'))
				<span class="help-block">
                    <strong>{{ $errors->first('key') }}</strong>
                </span>
			@endif
		</div>
		
		<div class="form-group">
			{{Form::label('type', 'Content Type')}}
			{{Form::select('type', ['Text', 'HTML'],0, ['class'=>'form-control', 'required', 'onchange'=>'updateTextarea(event)'])}}
			@if ($errors->has('type'))
				<span class="help-block">
                    <strong>{{ $errors->first('type') }}</strong>
                </span>
			@endif
		</div>
		
		<div class="card">
			<div class="card-body">
			<div class="form-group">
			@if($languages->count()>1)
					<ul class="nav nav-tabs" role="tablist">
					 @foreach($languages as $language)
							@component('cms::elements.tabheading', [
							'isActive'=>$language->is_default,
							'tabPanelId'=>'content_'.$language->code
							])
								{{$language->label}}
							@endcomponent
						@endforeach
			  </ul>
				@endif
				<div class="tab-content mt-3">
				@foreach($languages  as $index=>$language)
						@component('cms::elements.tabpanel', [
						'isActive'=>$language->is_default,
						'tabPanelId'=>'content_'.$language->code
						])
							<div class="form-group">
								{{Form::label("content[$index][content]", 'Content')}}
								{{Form::textarea("content[$index][content]", '', ['class'=>'form-control', 'placeholder'=>"{$language->label} Content"])}}
								{{Form::hidden("content[$index][lang_id]", $language->id)}}
								@if ($errors->has("content.{$index}.content"))
									<span class="help-block">
		                                <strong>{{ $errors->first("content.{$index}.content") }}</strong>
		                            </span>
								@endif
							</div>
						@endcomponent
					@endforeach
			  </div>
				</div>
			</div>
			
		</div>
		
		<div class="form-group mt-3">
			{{Form::submit('Create', ['class'=>'btn btn-success'])}}
			<a href='{{route('cms::common_contents.index')}}' class="btn btn-info">Back</a>
		</div>
		
		
		{{Form::close()}}
	
	@endcomponent

@endsection
@section('scripts')
	<script>
		
		let textareas = document.querySelectorAll('textarea')
        let typeInput = document.getElementById('type').value

        function initCKEDITOR(el) {
          let token = document.head.querySelector('meta[name="csrf-token"]');

          const options = {
            filebrowserImageBrowseUrl: '/filemanager?type=Images',
            filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=' + token.content,
            filebrowserBrowseUrl     : '/filemanager?type=Files',
            filebrowserUploadUrl     : '/filemanager/upload?type=Files&_token=' + token.content,
            extraAllowedContent      : '*(*);*{*};*[id]'
          }
          CKEDITOR.replace(el, options)
        }

        function updateTextarea(e) {
          if (e.target.value === '1') {
            textareas.forEach(function (el) {
              initCKEDITOR(el)
            })
          } else {
            Object.keys(CKEDITOR.instances).forEach(function (instanceKey) {
              var instance = CKEDITOR.instances[instanceKey]
              if (instance) {
                instance.destroy()
              }
            })
          }
        }

        if (typeInput === "1") {
          textareas.forEach(function (el) {
            initCKEDITOR(el)
          })
        }

       
	</script>

@endsection