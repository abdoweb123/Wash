@props(
    [
        'routeName' => 'text',
        'title',
        'icon',
    ]
)

<li class="nav-item @if(str_contains(Route::currentRouteName(), $routeName)) active @endif">
        <a href="{{ route($routeName) }}">
        <span class="icon text-center">
            <i style="width: 20px;" class="{{ $icon }} mx-2"></i>
        </span>
        <span class="text">{{ $title }}</span>
    </a>
</li>
