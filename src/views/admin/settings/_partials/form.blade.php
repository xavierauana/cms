<div class="form-group">
	{{Form::label('label', 'Label')}}
	{{Form::text('label', null, ['class'=>'form-control', 'placeholder'=>'Setting Label', 'required'])}}
	@if ($errors->has('label'))
		<span class="help-block">
            <strong>{{ $errors->first('label') }}</strong>
        </span>
	@endif
</div>
@if(!isset($setting))
	<div class="form-group">
	{{Form::label('key', 'Key')}}
		{{Form::text('key', null, ['class'=>'form-control', 'placeholder'=>'Unique Identifier', 'required'])}}
		@if ($errors->has('key'))
			<span class="help-block">
            <strong>{{ $errors->first('key') }}</strong>
        </span>
		@endif
</div>
@endif
<div class="form-group">
	{{Form::label('value', 'Value')}}
	{{Form::text('value', null, ['class'=>'form-control', 'placeholder'=>'Value','required'])}}
	@if ($errors->has('value'))
		<span class="help-block">
            <strong>{{ $errors->first('value') }}</strong>
        </span>
	@endif
</div>
		