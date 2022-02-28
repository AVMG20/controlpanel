<?php
    /**
     * @required string $name
     * @required string $label
     * @required string $min
     * @required string $max
     *
     * @optional string $tooltip
     * @optional string $step
     */
?>

<div class="form-group mb-3">
    <div class="d-flex justify-content-between">
        <label for="{{$name}}">{{$label}}</label>
        @if(isset($tooltip) && !empty($tooltip))
            <span><i data-bs-toggle="tooltip" data-bs-placement="top" title="{{$tooltip}}" class="fas fa-info-circle"></i></span>
        @endif
    </div>
    <input value="{{old($name,  $value)}}" id="{{$name}}"
           name="{{$name}}"
           type="number"
           min="{{$min}}"
           max="{{$max}}"
           @if(isset($step) && !empty($step))
           step="{{$step}}"
           @endif
           class="form-control @error($name)is-invalid @enderror">
    @error($name)
    <div class="invalid-feedback">{{$message}}</div>@enderror
</div>
