<tr>
	<td>{{$page->uri}}</td>
	<td>{{$page->template}}</td>
	<td>{{$page->is_active?"Yes":"No"}}</td>
	<td>{{$page->is_restricted?$page->showPermission():"No"}}</td>
	<td>
		<div class="btn-group btn-group-sm">
			@if(Auth::guard('admin')->user()->hasPermission('index_content'))
				<a href='{{route('contents.index', $page->id)}}'
				   class="btn btn-primary">Content</a>
			@endif
			@if(Auth::guard('admin')->user()->hasPermission('edit_page'))
				<a href="{{route('pages.edit', $page->id)}}"
				   class="btn btn-info">Edit</a>
			@endif
			
			@if(Auth::guard('admin')->user()->hasPermission('delete_page'))
				<delete-item
						url="{{route('pages.destroy', $page->id)}}"
						inline-template>
					<button class="btn btn-danger"
					        @click.prevent="deleteItem">Delete</button>
				</delete-item>
			@endif
		</div>
	</td>
</tr>