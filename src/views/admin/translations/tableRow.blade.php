<tr>
	<td>{{($languages->toArray())[str_replace('.json','',$file->getFileName())] }}</td>
	<td>
        <div class="btn-group btn-group-sm">
            <a href="{{route('cms::admin.edit.translations',['filename'=>$file->getFileName()])}}"
               class="btn btn-sm btn-info">Edit</a>
            <form
                onsubmit="return confirm('Are you sure to delete the translation file?')"
                action="{{route('cms::admin.delete.translations',['filename'=>$file->getFileName()])}}"
                method="POST">
                {{csrf_field()}}
                {{method_field('DELETE')}}
                <button class="btn btn-sm btn-danger" type="submit">Delete</button>
            </form>

        </div>
	</td>
</tr>
