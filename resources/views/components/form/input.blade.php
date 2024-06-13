@props(
    [
        'type' => 'text',
        'name',
        'label',
        'placeholder' => ''
    ]
)

<div {{ $attributes->merge(['class'=>'form-group mb-10']) }}>
    <label class="w-100" for="{{ $name }}">{{ $label }}</label>
    <input id="{{ $name }}" wire:model="{{ $name }}" class="form-control" type="{{ $type }}" placeholder="{{ $placeholder }}">

    @if($errors->has($name))
        <span class="text-danger">{{ $errors->first($name) }}</span>
    @endif
</div>
