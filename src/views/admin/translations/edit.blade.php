@extends("cms::layouts.default")

@section("content")
    <div class="container">
		<div class="mt-3 card card-default">
			<!-- Default panel contents -->
            <h2 class="card-header">Edit Translation: {{str_replace('.json','', $file->getFileName())}}</h2>
            <div class="card-body">
                <form class="form"
                      action="{{route('cms::admin.update.translations',['filename'=>$file->getFileName()])}}"
                      method="POST"
                      id="edit-form">
				{{csrf_field()}}
                    {{method_field("PUT")}}
                    <textarea rows="20" class="form-control"
                              name="code">{{file_get_contents($file)}} </textarea>

                    @if(auth('admin')->user()->hasPermission('edit_translation'))
                        <button class="btn btn-primary btn-block">Update</button>
                    @endif
			</form>
            </div>




		</div>
	</div>
@endsection
