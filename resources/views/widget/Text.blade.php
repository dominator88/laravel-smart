<div class="form-group">
    <label class="col-md-2 control-label">{{ $title }}}</label>
    <div class="col-md-6">
        <input type="text" name="{{ $name }}" placeholder="{{ $placeholder }}" class="form-control "
                                           value="{{ $value }}">
    </div>
	<div class="col-md-3">
        <span class="help-inline"> {{ $help }} </span>
    </div>
</div>