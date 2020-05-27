@php $admin = auth('admin')->user() @endphp
@extends("cms::layouts.default")

@section("content")

    @component('cms::components.container')
        @slot('title')
            All Translation Files
            @if($admin->hasPermission('create_translation'))
                <a href="{{route('cms::admin.create.translations')}}"
                   class="btn btn-sm btn-success pull-right">Create New Translation</a>
            @endif
        @endslot
        <form class="form" method="GET" action="{{route('cms::admin.index.translations')}}">
			<div class="row">
				<div class="col col-sm-6 col-md-4 col-lg-3 ml-auto">
					<div class="input-group mb-3">
					  <input type="text" class="form-control"
                             name="keyword"
                             placeholder="Keyword"
                             aria-label="Keyword"
                             value="{{request()->query('keyword')}}"
                             aria-describedby="button-addon2">
					  <div class="input-group-append">
					    <button class="btn btn-outline-secondary" type="submit"
                                id="button-addon2">Search</button>
					  </div>
					</div>
				</div>
			</div>
		</form>

        <div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th><a href="{{sortableLink('code')}}">Language Code</a> </th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
                @foreach($files as $file)
                    <tr>
	<td>{{($languages->toArray())[str_replace('.json','',$file->getFileName())] }}</td>
	<td>
         @if( $admin->hasPermission('show_translation') or $admin->hasPermission('edit_translation'))

            <a href="{{route('cms::admin.edit.translations',['filename'=>$file->getFileName()])}}"
               class="btn btn-sm btn-info">Edit</a>
        @endif
        @if($admin->hasPermission('delete_translation'))
            <form
                class="d-inline"
                onsubmit="return confirm('Are you sure to delete the translation file?')"
                action="{{route('cms::admin.delete.translations',['filename'=>$file->getFileName()])}}"
                method="POST">
                {{csrf_field()}}
                {{method_field('DELETE')}}
                <button class="btn btn-sm btn-danger" type="submit">Delete</button>
            </form>
        @endif

	</td>
</tr>

                @endforeach
                </tbody>
			</table>
		</div>



    @endcomponent
@endsection
