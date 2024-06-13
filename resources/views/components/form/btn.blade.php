@props(
    [
        'title',
        'type' => 'button',
        'wireAction'
    ]
)

<button wire:click="{{ $wireAction }}"
    wire:loading.attr="disabled"
    {{ $attributes->merge(['class'=>'btn']) }}>
    {{ $title }}
</button>

