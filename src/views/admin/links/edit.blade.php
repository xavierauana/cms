@extends("cms::layouts.default")

@section("content")
    @component('cms::components.container')
        @slot('title')Edit Link: {{$link->name}} @endslot

        {{Form::model($link, ['url'=>route("menus.links.update", [$menu->id, $link->id]), 'method'=>'PUT','files'=>true])}}

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
        <div class="card mb-3">
            <div class="card-body">
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
                                @if($url = $link->getImage($language->code))
                                    <div>
								<div class="col py-3">
									<img class="img-fluid" style="height: 100px"
                                         src="{{$url}}">
								</div>
									<span
                                        onclick="deleteImage(event, '{{route('menus.links.image.delete',[$menu, $link,$language->code])}}')"
                                        class="btn btn-danger">Delete Image</span>

							</div>
                                @endif
                                <div class="form-group">
							{{Form::label("name[$index][content]", 'Name')}}
                                    {{Form::text("name[$index][content]", $link->getNameAttribute($language->code), ['class'=>'form-control', 'placeholder'=>'Link Name'])}}
                                    {{Form::hidden("name[$index][lang_id]", $language->id)}}
                                    @if ($errors->has("name.{$index}.content"))
                                        <span class="help-block">
                                <strong>{{ $errors->first("name.{$index}.content") }}</strong>
                            </span>
                                    @endif
						</div>


                                <div class="form-group">
							<div class="form-group">
								{{Form::label("files[$index][file]", 'Image')}}
                                {{Form::file("files[$index][file]", ['class'=>'form-control', 'placeholder'=>"{$language->label} Link Name"])}}
                                {{Form::hidden("files[$index][lang_id]", $language->id)}}
                                @if ($errors->has("name.{$index}.content"))
                                    <span class="help-block">
		                                <strong>{{ $errors->first("name.{$index}.content") }}</strong>
		                            </span>
                                @endif
							</div>
						</div>
                            @endcomponent
                        @endforeach
			  </div>
		        </div>
            </div>

        </div>

        <div class="form-group">
			{{Form::label('class', 'Custom Class Name')}}
            {{Form::text('class',null,['class'=>'form-control'])}}
            @if ($errors->has('class'))
                <span class="help-block">
                    <strong>{{ $errors->first('class') }}</strong>
                </span>
            @endif
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


@section('scripts')
    <script>
				function deleteImage(e, uri) {
                    e.preventDefault()
                    var form = document.createElement("form")
                    form.action = uri
                    form.method = "POST"
                    var methodField = document.createElement("input")
                    methodField.name = '_method'
                    methodField.type = 'hidden'
                    methodField.value = "DELETE"
                    var csrfField = document.createElement("input")
                    csrfField.name = '_token'
                    csrfField.type = 'hidden'
                    csrfField.value = "{{csrf_token()}}"

                    form.appendChild(methodField)
                    form.appendChild(csrfField)

                    document.getElementsByTagName('body')[0].appendChild(form)

                    form.submit()


                }
			</script>
@endsection
