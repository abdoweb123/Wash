@props(['name', 'label' => null])

<div {{ $attributes->merge(['class'=>'form-group mb-10']) }}>
    <label class="w-100" for="{{ $name }}">{{ $label }}</label>
    <select id="{{ $name }}" wire:model.live="{{ $name }}" class="form-control">
        <option hidden>--</option>
        {{ $slot }}
    </select>
    @if($errors->has($name))
        <span class="text-danger">{{ $errors->first($name) }}</span>
    @endif
</div>
