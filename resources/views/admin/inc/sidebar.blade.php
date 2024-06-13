<nav class="sidebar-nav">
    <ul>
        {{-- Start --}}
        @if (auth()->user()->company_id == null)
        <x-nav-item title="{{ __('dash.dashboard') }}" icon="fa-solid fa-chart-simple"
        routeName="dashboard.home">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.services') }}" icon="fa-solid fa-sitemap"
            routeName="dashboard.services" id="services">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.standards') }}" icon="fa-solid fa-scale-balanced"
            routeName="dashboard.standards" id="standards">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.companies') }}" icon="fa-solid fa-industry"
            routeName="dashboard.companies" id="companies">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.orders') }}" icon="fa-solid fa-credit-card"
            routeName="dashboard.orders">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.users') }}" icon="fas fa-users"
            routeName="dashboard.users" id="users">
        </x-nav-item>
        <hr>

        <x-nav-item title="{{ __('dash.admins') }}" icon="fas fa-users-cog"
            routeName="dashboard.admins" id="admins">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.contactus') }}" icon="fas fa-envelope-open-text"
            routeName="dashboard.contacts">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.slider') }}" icon="fa-solid fa-panorama"
            routeName="dashboard.slider-images">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.social_links') }}" icon="fas fa-tools"
        routeName="dashboard.social_links">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.public_setting') }}" icon="fas fa-tools"
        routeName="dashboard.public_setting">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.payment_methods') }}" icon="fa-solid fa-credit-card"
            routeName="dashboard.payment_methods">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.about_us') }}" icon="fa-solid fa-file"
            routeName="dashboard.aboutus">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.tac') }}" icon="fa-solid fa-file"
            routeName="dashboard.terms">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.termsAdmin') }}" icon="fa-solid fa-file"
            routeName="dashboard.termsAdmin">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.pp') }}" icon="fa-solid fa-file"
            routeName="dashboard.privacy">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.privacyAdmin') }}" icon="fa-solid fa-file"
            routeName="dashboard.privacyAdmin">
        </x-nav-item>
        {{-- end --}}
        @else
        <x-nav-item title="{{ __('dash.dashboard') }}" icon="fa-solid fa-chart-simple"
        routeName="dashboard.company.home">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.services') }}" icon="fa-solid fa-sitemap"
            routeName="dashboard.company.services" id="services">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.worktimes') }}" icon="fa-solid fa-clock-rotate-left"
            routeName="dashboard.company.worktimes" id="worktimes">
        </x-nav-item>
        <x-nav-item title="{{ __('dash.orders') }}" icon="fa-solid fa-credit-card"
            routeName="dashboard.company.orders">
        </x-nav-item>
        @endif
        @if (lang('en'))
            <li class="nav-item">
                <a href="{{ route('lang', 'ar') }}">
                    <span class="icon text-center">
                        <img src="{{ asset('admin/language/ar.png') }}" style="width: 20px;" class="mx-2">
                    </span>
                    <span class="text">Arabic</span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a href="{{ route('lang', 'en') }}">
                    <span class="icon text-center">
                        <img src="{{ asset('admin/language/en.png') }}" style="border-radius: 50%;width: 20px;"
                            class="mx-2">
                    </span>
                    <span class="text">English</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
