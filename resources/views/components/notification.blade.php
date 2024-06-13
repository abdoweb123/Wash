<div>
    <div class="notification-box ml-15 d-none d-md-flex">
        <button class="dropdown-toggle" type="button" id="notification"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-regular fa-bell"></i>
            @if ($notifications_count > 0)
                <span>{{ $notifications_count }}</span>
            @endif
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notification" style="max-height: fit-content;">
            @forelse ($notifications as $notification)
            <li>
                <a href="{{ $notification->link ?? '#' }}" style="{{ lang('ar') ? 'text-align: right' : ''}}">
                    <div class="content">
                        <h5>
                            {{ $notification['title_'.lang()] }}
                            <span class="{{ lang('ar') ? 'float-md-start' : 'float-md-end' }}">{{ $notification->created_at->diffForHumans() }}</span>
                        </h5>
                        <h7 class="text-success">
                            <i class="fa-solid fa-at"></i>
                            {{ $notification->from }}
                        </h7>
                        <p>
                            {{ $notification['body_'.lang()] }}
                        </p>
                    </div>
                </a>
            </li>
            @empty
            {{ __('dash.empty_notifications') }}
            @endforelse
            <li>
                <a href="{{ route('dashboard.notifications') }}" class="text-active text-semi-bold">{{ __('dash.see_all') }}</a>
            </li>
        </ul>
    </div>
</div>