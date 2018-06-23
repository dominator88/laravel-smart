<div class="form-group">
	<label class="col-md-3 control-label">{{ $title }}</label>
	<div class="col-md-7">

		@foreach($data as $k=>$v)
		<label class="radio-inline"><input name="{{ $name }}" type="radio" value="{{ $k }}"
			@if($value == $k)
				checked=true
			@endif
			>{{ $v }}</label>
		@endforeach

	</div>
</div>