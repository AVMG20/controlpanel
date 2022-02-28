<?php
/**
 * @required string $name
 * @required string $label
 *
 * @optional string $type
 * @optional string $value
 * @optional string $tooltip
 */
?>

<div class="form-group mb-3">
    <div class="d-flex justify-content-between">
        <label for="{{$name}}">{{$label}}</label>
        @if(isset($tooltip) && !empty($tooltip))
            <span><i data-bs-toggle="tooltip" data-bs-placement="top" title="{{$tooltip}}" class="fas fa-info-circle"></i></span>
        @endif
    </div>
    <input value="{{old($name,  $value ?? null)}}" id="{{$name}}"
           name="{{$name}}"
           type="{{$type ?? 'text'}}" class="form-control @error($name)is-invalid @enderror"/>

    @error($name)
        <div class="invalid-feedback">{{$message}}</div>
    @enderror
</div>
