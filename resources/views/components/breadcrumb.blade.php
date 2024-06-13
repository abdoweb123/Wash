@props([
    'active' => false,
    'title',
    'route' => null
])

    @if ($active)
        <li class="breadcrumb-item active text-bold text-black" aria-current="page">
            {{ $title }}
        </li>
    @else
        <li class="breadcrumb-item" aria-current="page">
            <a wire:navigate href="{{ $route }}">
                {{ $title }}
            </a>
        </li>
    @endif
