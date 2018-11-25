@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Systems Settings @endslot
		
		<div class="table-responsive">
			<table class="table table-hover" ref="table">
				<thead>
					<tr>
						<th>Label</th>
						<th>Value</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				
				@foreach($settings as $setting)
					<tr data-id="{{$setting->id}}">
						<td>{{$setting->label}}</td>
						<td>{{$setting->value}}</td>
						<td>
							<div class="btn-group btn-group-sm">
								<a href="{{route('settings.edit', $setting->id)}}"
								   class="btn btn-info">Edit</a>
							</div>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	@endcomponent
@endsection