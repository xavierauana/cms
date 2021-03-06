@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Create New Page @endslot
		
		{{Form::open(['url'=>route('pages.store'), 'method'=>'POST'])}}
		
		<div class="form-group">
			{{Form::label('uri', 'Uri')}}
			{{Form::text('uri', '', ['class'=>'form-control', 'placeholder'=>'page uri'])}}
			@if ($errors->has('uri'))
				<span class="help-block">
                    <strong>{{ $errors->first('uri') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('has_children', 'Has Children')}}
			{{Form::select('has_children', [0=>'No', 1=>'Yes'],0,['class'=>'form-control'])}}
			@if ($errors->has('has_children'))
				<span class="help-block">
                    <strong>{{ $errors->first('has_children') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('is_active', 'Is Active')}}
			{{Form::select('is_active', [0=>'No', 1=>'Yes'],1,['class'=>'form-control'])}}
			@if ($errors->has('is_active'))
				<span class="help-block">
                    <strong>{{ $errors->first('is_active') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('is_restricted', 'Is Restricted')}}
			{{Form::select('is_restricted', [0=>'No', 1=>'Yes'],0,['class'=>'form-control'])}}
			@if ($errors->has('is_restricted'))
				<span class="help-block">
                    <strong>{{ $errors->first('is_restricted') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('permission_id', 'Required Permission')}}
			{{Form::select('permission_id', $permissions,0,['class'=>'form-control'])}}
			@if ($errors->has('permission_id'))
				<span class="help-block">
                    <strong>{{ $errors->first('permission_id') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('in_sitemap', 'Show in sitemap')}}
			{{Form::select('in_sitemap', [0=>'No', 1=>'Yes'], 1,['class'=>'form-control'])}}
			@if ($errors->has('in_sitemap'))
				<span class="help-block">
                    <strong>{{ $errors->first('in_sitemap') }}</strong>
                </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('template', 'Layout Template')}}
			{{Form::select('template', array_combine(array_values(
			$layouts),array_values(
			$layouts)),null,['class'=>'form-control'])}}
			@if ($errors->has('template'))
				<span class="help-block">
                    <strong>{{ $errors->first('template') }}</strong>
                </span>
			@endif
		</div>
		
		<div class="form-group">
			{{Form::label('order', 'Order')}}
			{{Form::number('order', 0,['class'=>'form-control'])}}
			@if ($errors->has('order'))
				<span class="help-block">
                    <strong>{{ $errors->first('order') }}</strong>
                </span>
			@endif
		</div>
		
		<div class="form-group">
			{{Form::submit('Create', ['class'=>'btn btn-success'])}}
			<a href='{{route('pages.index')}}' class="btn btn-info">Back</a>
		</div>
		
		
		{{Form::close()}}
	@endcomponent


@endsection