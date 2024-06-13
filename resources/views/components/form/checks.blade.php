@props(['name'])
<div>
    <input id="{{ $name }}" class="checkbox-style" wire:model.defer="{{ $name }}" type="checkbox">
    <label for="{{ $name }}" class="checkbox-style-1-label">
        {{ $slot }}
    </label>

</div>
@if($errors->has($name))
<span class="text-danger">{{ $errors->first($name) }}</span>
@endif
