<tr>
	<td>{{$content->label}}</td>
	<td>{{$content->key}}</td>
	<td>
		<div class="btn-group btn-group-sm">
			@if(Auth::guard('admin')->user()->can('edit',$content))
				<a href="{{route('cms::common_contents.edit', $content->id)}}"
				   class="btn btn-info">Edit</a>
			@endif
			
			@if(Auth::guard('admin')->user()->can('delete',$content))
				<delete-item
						url="{{route('cms::common_contents.destroy', $content->id)}}"
						inline-template>
					<button class="btn btn-danger"
					        @click.prevent="deleteItem">Delete</button>
				</delete-item>
			@endif
		</div>
	</td>
</tr>