@extends("cms::layouts.default")

@section("content")
	@component('cms::components.container')
		@slot('title')
			All Design Layouts
		@endslot
		<div class="panel-group" id="accordion" role="tablist"
		     aria-multiselectable="true">
				  <div class="panel panel-default">
				    <div class="panel-heading" role="tab" id="headingOne">
				      <h4 class="panel-title">
				        <a role="button" data-toggle="collapse"
				           data-parent="#accordion" href="#collapseOne"
				           aria-expanded="true" aria-controls="collapseOne">
				          Layouts
				        </a>
				      </h4>
				    </div>
				    <div id="collapseOne" class="panel-collapse collapse in"
				         role="tabpanel" aria-labelledby="headingOne">
				      <div class="panel-body">
				        <table class="table">
					        
					        <thead>
					            <th>File Name</th>
					            @if(auth('admin')->user()->hasPermission('index_content'))
						            <th>Pages</th>
					            @endif
					            <th>Actions</th>
					        </thead>
					        <tbody>
					        @if(count($design['layouts']))
						        @foreach($design['layouts'] as $layout)
							        <tr>
							            <td>{{$layout}}</td>
								        @if(auth('admin')->user()->hasPermission('edit_content'))
									        <td>
										        @if(isset($pages[$layout]))
											        @foreach($pages[$layout] as $page)
												        <a href="/admin/pages/{{$page->id}}/contents"
												           class="btn btn-xs btn-primary">{{$page->uri}}</a>
											        @endforeach
										        @endif
							            </td>
								        @endif
								        <td>
								            <div class="btn btn-group btn-group-sm">
									            <a href="/admin/designs/edit/layouts?file={{$layout}}"
									               class="btn btn-info">Edit</a>
									            <button class="btn btn-danger">Delete</button>
								            </div>
							            </td>
						            </tr>
						        @endforeach
					        @else
						        <td colspan="2">No Partial File</td>
					        @endif
					        </tbody>
				        </table>
				      </div>
				    </div>
				  </div>
				  <div class="panel panel-default">
				    <div class="panel-heading" role="tab" id="headingTwo">
				      <h4 class="panel-title">
				        <a class="collapsed" role="button"
				           data-toggle="collapse" data-parent="#accordion"
				           href="#collapseTwo" aria-expanded="false"
				           aria-controls="collapseTwo">
				          Partials
				        </a>
				      </h4>
				    </div>
				    <div id="collapseTwo" class="panel-collapse collapse"
				         role="tabpanel" aria-labelledby="headingTwo">
				      <div class="panel-body">
				      </div>
					    <table class="table">
					        <thead>
					            <th>File Name</th>
					            <th>Actions</th>
					        </thead>
					        <tbody>
					        @if(count($design['partials']))
						        @foreach($design['partials'] as $partial)
							        <tr>
							            <td>{{$partial}}</td>
							            <td>
								            <div class="btn btn-group btn-group-sm">
									            <a href="/admin/designs/edit/partials?file={{$partial}}"
									               class="btn btn-info">Edit</a>
									            <button class="btn btn-danger">Delete</button>
								            </div>
							            </td>
						            </tr>
						        @endforeach
					        @else
						        <td colspan="2">No Partial File</td>
					        @endif
					        </tbody>
				        </table>
				    </div>
				  </div>
				</div>
	@endcomponent
@endsection