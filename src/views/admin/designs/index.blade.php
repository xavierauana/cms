@extends("cms::layouts.default")

@section("content")
    @component('cms::components.container')
        @slot('title') All Design Layouts @endslot
        
        @component('cms::elements.accordion_container',['id'=>'design_accordion'])
            
            @if(auth('admin')->user()->hasPermission('edit_definition'))
                @component('cms::elements.accordion_item')
                    @slot('parentId')design_accordion @endslot
                    @slot('id')definition_panel @endslot
                    @slot('title')Layout Definition @endslot
                    @if(Auth::guard('admin')->user()->hasPermission('create_definition'))
                        @slot('panelActions') <a
                            class=" mt-1 btn btn-sm text-light btn-success float-right"
                            href="{{route('designs.create', 'definition')}}">Create Definition</a>@endslot
                    @endif
                    <div class="table-responsive">
					<table class="table">
				        <thead>
				            <th>Definition File Name</th>
				            <th>Actions</th>
				        </thead>
				        <tbody>
					        @if(count($design['definitions']))
                                @foreach($design['definitions'] as $layout)
                                    <tr>
							            <td>{{$layout}}</td>
								        <td>
								            <div class="btn btn-group btn-group-sm">
									            @if(Auth::guard('admin')->user()->hasPermission('edit_definition'))
                                                    <a href="{{route('designs.edit', 'definition').'?file='.$layout}}"
                                                       class="btn btn-info">Edit</a>
                                                @endif
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
                @endcomponent
            @endif
            
            @if(auth('admin')->user()->hasPermission('edit_layout'))
                @component('cms::elements.accordion_item')
                    @slot('parentId')design_accordion @endslot
                    @slot('id')layouts_panel @endslot
                    @slot('title')Layouts @endslot
                    @if(Auth::guard('admin')->user()->hasPermission('create_layout'))
                        @slot('panelActions') <a
                            class=" mt-1 btn btn-sm text-light btn-success float-right"
                            href="{{route('designs.create','layouts')}}">Create Layout</a>@endslot
                    @endif
                    <div class="table-responsive">
					<table class="table">
				        <thead>
				            <th>File Name</th>
                            @if(auth('admin')->user()->hasPermission('edit_content'))
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
                                                        <a href="{{route('contents.index', $page->id)}}"
                                                           class="btn btn-xs btn-primary">{{$page->uri}}</a>
                                                    @endforeach
                                                @endif
							            </td>
                                        @endif
                                        <td>
								            <div class="btn btn-group btn-group-sm">
									            @if(Auth::guard('admin')->user()->hasPermission('edit_layout'))
                                                    <a href="{{route('designs.edit', 'layouts').'?file='.$layout}}"
                                                       class="btn btn-info">Edit</a>
                                                @endif
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
                @endcomponent
                @component('cms::elements.accordion_item')
                    @slot('parentId')design_accordion @endslot
                    @slot('id')partials_panel @endslot
                    @slot('title')Partials @endslot
                    @if(Auth::guard('admin')->user()->hasPermission('create_layout'))
                        @slot('panelActions') <a
                            class=" mt-1 btn btn-sm text-light btn-success float-right"
                            href="{{route('designs.create','partials')}}">Create Partial</a>@endslot
                    @endif
                    <div class="table-responsive">
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
									            @if(Auth::guard('admin')->user()->hasPermission('edit_layout'))
                                                    <a href="{{route('designs.edit', 'partials').'?file='.$partial}}"
                                                       class="btn btn-info">Edit</a>
                                                @endif
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
                @endcomponent
            @endif
        
        @endcomponent
    @endcomponent
@endsection
