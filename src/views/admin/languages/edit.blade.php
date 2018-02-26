@extends("cms::layouts.default")

@section("content")
	
	@component('cms::components.container')
		@slot('title')Update Language: {{$language->label}} @endslot
		
		{{Form::model($language, ['route'=>["languages.update", $language->id], 'method'=>'PUT'])}}
		
		<div class="form-group">
			{{Form::label('label', 'Label')}}
			{{Form::text('label', $language->label, ['class'=>'form-control', 'placeholder'=>'Language Label', 'required'])}}
			@if ($errors->has('label'))
				<span class="help-block">
                                        <strong>{{ $errors->first('label') }}</strong>
                                    </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('code', 'Language Code')}}
			{{Form::text('code', $language->code, ['class'=>'form-control', 'placeholder'=>'Language Code','required'])}}
			@if ($errors->has('code'))
				<span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('is_active', 'Is Active')}}
			{{Form::select('is_active', [0=>'No', 1=>'Yes'],$language->is_active,['class'=>'form-control'])}}
			@if ($errors->has('is_active'))
				<span class="help-block">
                                        <strong>{{ $errors->first('is_active') }}</strong>
                                    </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('is_default', 'Is Default')}}
			{{Form::select('is_default', [0=>'No', 1=>'Yes'],$language->is_default,['class'=>'form-control'])}}
			@if ($errors->has('is_default'))
				<span class="help-block">
                                        <strong>{{ $errors->first('is_default') }}</strong>
                                    </span>
			@endif
		</div>
		<div class="form-group">
			{{Form::label('fallback_language_id', 'Fallback Language')}}
			{{Form::select('fallback_language_id', $languages, $language->fallback_language_id,['class'=>'form-control'])}}
			@if ($errors->has('fallback_language_id'))
				<span class="help-block">
                                        <strong>{{ $errors->first('fallback_language_id') }}</strong>
                                    </span>
			@endif
		</div>
		
		<div class="form-group">
			{{Form::submit('Update', ['class'=>'btn btn-success'])}}
			<a href='{{route('languages.index')}}' class="btn btn-info">Back</a>
		</div>
		
		
		{{Form::close()}}
	@endcomponent


@endsection