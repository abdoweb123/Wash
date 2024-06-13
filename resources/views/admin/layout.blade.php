<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ setting('title') }}</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf_token" value="{{ csrf_token() }}" content="{{ csrf_token() }}" />
    {{-- <meta name="DT_Lang" value="{{ DT_Lang() }}" content="{{ DT_Lang() }}"/> --}}
    {{-- <meta name="user-theme" content="{{ auth()->user()->theme }}" /> --}}
    <link rel="icon" href="{{ asset(setting('logo')) }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
    <link rel="stylesheet" href="{{ asset('admin/css/lineicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/dashboard.css') }}">

    @yield('css')
    @if (lang('ar'))
        <link rel="stylesheet" href="{{ asset('admin/css/ar.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('admin/css/en.css') }}">
    @endif

</head>

<body>
    <aside class="sidebar-nav-wrapper active">
        <div class="navbar-logo">
            @if (auth('admin')->user()->company_id == null)
                <a href="{{ route('dashboard.home') }}">
                    <img src="{{ asset(setting('logo')) }}" alt="logo" style="height: 100px;max-width: 198px;" />
                </a>
            @else
                <a href="{{ route('dashboard.company.home') }}">
                    <img src="{{ asset(auth('admin')->user()->company->logo) }}" alt="logo" style="height: 100px;max-width: 198px;" />
                </a>
            @endif
        </div>

        @include('admin.inc.sidebar')

    </aside>
    <div class="overlay"></div>

    <main class="main-wrapper active">
        <header class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-6" style="z-index: 100">
                        <div class="header-left">
                            <div class="menu-toggle-btn mr-20">
                                <button id="menu-toggle" class="main-btn primary-btn btn-hover">
                                    <i class="lni lni-menu"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-6">
                        <div class="header-right">
                            @if (auth()->user()->company_id == null)
                                <x-notification></x-notification>
                            @endif
                            <div class="profile-box ml-15">
                                <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="profile-info">
                                        <div class="info">
                                            <h6>{{ auth('admin')->user()->name ?? 'no auth yet' }}</h6>
                                        </div>
                                    </div>
                                    <i class="lni lni-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">
                                    <li>
                                        <form action="{{ route('dashboard.logout') }}" method="POST">
                                            @csrf
                                            <a href="{{ route('dashboard.logout') }}"
                                                onclick="event.preventDefault(); this.closest('form').submit();"> <i
                                                    class="lni lni-exit"></i>Log out</a>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section class="section">
            <div class="container-fluid">
                @yield('title')
                @yield('content')
            </div>
        </section>

        <footer class="footer">
            <div class="container-fluid text-center" style="direction: ltr">
                <p class="text-sm">
                    © All Copyrights Reserved 2023, Powered By
                    <a href="https://emcan-group.com/" rel="nofollow" target="_blank">
                        Emcan Solutions
                    </a>
                </p>
            </div>
        </footer>
    </main>

    <script src="{{ asset('admin/js/dashboard.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
        integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="{{ asset('admin/js/main.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script> --}}

    @yield('js')
    @livewireScripts
    <script src="{{ asset('admin/js/wire-sweetalert.js') }}"></script>
    <x-livewire-alert::scripts />

    @stack('js')
</body>

</html>
