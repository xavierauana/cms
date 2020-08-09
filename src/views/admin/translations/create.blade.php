@extends("cms::layouts.default")

@section("content")

    @component('cms::components.container')
        @slot('title')Create New Translation File @endslot

        {{Form::open(['url'=>route('cms::admin.store.translations'), 'method'=>'POST'])}}

        <div class="form-group">
			{{Form::label('code', 'Language')}}
            {{Form::select('code', $languages,[], ['class'=>'form-control', 'placeholder'=>'Select Language', 'required'])}}
            @if ($errors->has('label'))
                <span class="help-block">
                    <strong>{{ $errors->first('label') }}</strong>
                </span>
            @endif
		</div>

        <div class="form-group mt-3">
			{{Form::submit('Create', ['class'=>'btn btn-success'])}}
            <a href='{{route('cms::admin.index.translations')}}' class="btn btn-info">Back</a>
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
