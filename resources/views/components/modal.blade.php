@props([
    'size' => '',
    'id',
    'title',
    'type',
    'btnName' => __('dash.confirm'),
    'wireAction' => false,
    'submit' => false,
    'addNew' => false,
])

<div wire:ignore.self wire:loading.attr='disabled' class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}"
    aria-hidden="true">
    <div class="modal-dialog {{ $size }}">
        <div class="modal-content">
            <div class="modal-header d-block modal-header">
                <button type="button" class="bg-warning btn-close float-start mt-0" data-bs-dismiss="modal" aria-label="Close"></button>
                <h1 class="float-end fs-5 modal-title" id="detailsModal">{{ $title }}</h1>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if ($wireAction)
            <div class="modal-footer">
                <button wire:click='{{ $wireAction }}' type="button" class="btn btn-{{ $type }}" wire:loading.attr='disabled'>{{ $btnName }}</button>
                @if ($addNew)
                    <button wire:click='{{ $wireAction }}("true")' type="button" class="btn btn-dark" wire:loading.attr='disabled'>{{ $btnName }} واضافة جديد</button>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
