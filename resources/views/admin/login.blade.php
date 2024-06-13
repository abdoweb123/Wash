<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <link rel="shortcut icon" href="{{ asset('login_assets/images/favicon_1.ico') }}">

    <title>{{ setting('title') }}</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@400;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Changa', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Changa', sans-serif !important;
        }

    </style>

    <link href="{{ asset('login_assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('login_assets/css/core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('login_assets/css/components.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('login_assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('login_assets/css/pages.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('login_assets/css/menu.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('login_assets/css/responsive.css') }}" rel="stylesheet" type="text/css" />

</head>

<body>

    <div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
        <div class=" card-box">
            <div class="panel-heading text-center">
                <img src="{{ setting('logo') }}" style="width: 100px;height: 100px;">
                <h3 class="text-center">تسجيل دخول</h3>
            </div>
            <div class="panel-body">
                @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
                @endif
                @if (session()->has('danger'))
                <div class="alert alert-danger">
                    {{ session()->get('danger') }}
                </div>
                @endif
                <form class="form-horizontal m-t-20" action="{{route('dashboard.login.post')}}" method="POST">
                    @csrf
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="email" required placeholder="Email">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" name="password" required placeholder="Password">
                        </div>
                    </div>

                    {{-- <div class="form-group ">
                        <div class="col-xs-12">
                            <div class="checkbox checkbox-primary">
                                <input id="checkbox-signup" name="remember" type="checkbox">
                                <label for="checkbox-signup">
                                    Remember Me
                                </label>
                            </div>

                        </div>
                    </div> --}}

                    <div class="form-group text-center m-t-40">
                        <div class="col-xs-12">
                            <button class="btn btn-github btn-block text-uppercase waves-effect waves-light" type="submit" name="submit">Login</button>
                        </div>
                    </div>
                    <div class="form-group m-t-30 m-b-0">
                        <div class="col-sm-12">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <p class="text-sm text-center">
                    جميع الحقوق محفوظة بواسطة
                    <a href="https://emcan-group.com/" rel="nofollow" target="_blank">
                        إمكان
                    </a>
                </p>
            </div>
        </div>
    </footer>
