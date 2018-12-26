<tr data-id="{{$language->id}}">
	<td>{{$language->label}}</td>
	<td>{{$language->code}}</td>
	<td>{{$language->is_active?"Yes":"No"}}</td>
	<td>{{$language->is_default?"Yes":"No"}}</td>
	<td>
		<div class="btn-group btn-group-sm">
			@if(Auth::guard('admin')->user()->hasPermission('edit_language'))
				<a href="{{route('languages.edit', $language->id)}}"
				   class="btn btn-info">Edit</a>
			@endif
			@if(Auth::guard('admin')->user()->hasPermission('delete_language'))
				<button class="btn btn-danger"
				        @click.prevent="deleteItem({{$language->id}})">Delete</button>
			@endif
		</div>
	</td>
</tr>