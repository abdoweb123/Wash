@props(
    [
        'name',
        'label',
        'placeholder' => '',
        'rows' => 3
    ]
)

<div {{ $attributes->merge(['class'=>'form-group mb-10']) }}>
    <label class="w-100" for="{{ $name }}">{{ $label }}</label>
    <textarea id="{{ $name }}" wire:model="{{ $name }}"  rows="{{ $rows }}" class="form-control mceNoEditor" placeholder="{{ $placeholder }}"></textarea>

    @if($errors->has($name))
        <span class="text-danger">{{ $errors->first($name) }}</span>
    @endif
</div>
