@extends("cms::layouts.default")

@section("content")
    
    @component('cms::components.container')
        @slot('title'){{__('System Settings')}}
        @if(Auth::user()->hasPermission('create_setting'))
            <a href="{{route('settings.create')}}"
               class="float-right btn btn-create btn-sm btn-success text-light">{{__('Create')}}</a>
        @endif
        @endslot
        
        <div class="table-responsive">
			<table class="table table-hover" ref="table">
				<thead>
					<tr>
						<th>Label</th>
						<th>Key</th>
						<th>Value</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				
				@foreach($settings as $setting)
                    <tr data-id="{{$setting->id}}">
						<td>{{$setting->label}}</td>
						<td>{{$setting->key}}</td>
						<td>{{$setting->value}}</td>
						<td>
							<form action="{{route('settings.destroy', $setting->id)}}"
                                  onsubmit="confirmDelete(event)"
                                  method="POST">
								{{csrf_field()}}
                                {{method_field('delete')}}
                                
                                <div class="btn-group btn-group-sm">
									@if(Auth::user()->hasPermission('edit_setting'))
                                        <a href="{{route('settings.edit', $setting->id)}}"
                                           class="btn btn-info">Edit</a>
                                    @endif
                                    @if(!$setting->is_default and Auth::user()->hasPermission('delete_setting'))
                                        <button class="btn btn-danger">Delete</button>
                                    @endif
								</div>
								</form>
						</td>
					</tr>
                @endforeach
				</tbody>
			</table>
		</div>
    
    @endcomponent
@endsection

@section('scripts')
    <script>
		function confirmDelete(e) {
          e.preventDefault()
          if (confirm('Are you sure to delete the item?')) {
            e.target.submit()
          }
        }
	</script>
@endsection
