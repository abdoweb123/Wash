@extends('admin.layout')

@section('title')
    <x-pageTitle current="Home">
        <li class="breadcrumb-item active" aria-current="page">
            {{ __('dash.home') }}
        </li>
    </x-pageTitle>
@endsection

@section('content')
    <div class="header row">
        <div class="col-12 col-md-4 mb-3">
            <div class="bg-dark-100 card rounded-md shadow">
                <div class="card-body">
                    <h5>{{ __('dash.companies') }}</h5>
                    <p style="font-size: xxx-large;"
                        class="{{ lang('en') ? 'float-lg-end' : 'float-lg-start' }} mt-5 text-semi-bold text-primary">
                        {{ $home['companies'] ?? '' }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-3">
            <div class="bg-dark-100 card rounded-md shadow">
                <div class="card-body">
                    <h5>{{ __('dash.services') }}</h5>
                    <p style="font-size: xxx-large;"
                        class="{{ lang('en') ? 'float-lg-end' : 'float-lg-start' }} mt-5 text-semi-bold text-primary">
                        {{ $home['services'] ?? '' }}</p>
                </div>
            </div>

        </div>
        <div class="col-12 col-md-4 mb-3">
            <div class="bg-dark-100 card rounded-md shadow">
                <div class="card-body">
                    <h5>{{ __('dash.orders') }}</h5>
                    <p style="font-size: xxx-large;"
                        class="{{ lang('en') ? 'float-lg-end' : 'float-lg-start' }} mt-5 text-semi-bold text-primary">
                        {{ $home['orders'] ?? '' }}</p>
                </div>
            </div>

        </div>
        <div class="col-12 col-md-4 mb-3">
            <div class="bg-dark-100 card rounded-md shadow">
                <div class="card-body">
                    <h5>{{ __('dash.users') }}</h5>
                    <p style="font-size: xxx-large;"
                        class="{{ lang('en') ? 'float-lg-end' : 'float-lg-start' }} mt-5 text-semi-bold text-primary">
                        {{ $home['users'] ?? '' }}</p>
                </div>
            </div>
        </div>
        @if ($home['contacts'] ?? false)
        <div class="col-12 col-md-4 mb-3">
            <div class="bg-dark-100 card rounded-md shadow">
                <div class="card-body">
                    <h5>{{ __('dash.contactus') }}</h5>
                    <p style="font-size: xxx-large;"
                        class="{{ lang('en') ? 'float-lg-end' : 'float-lg-start' }} mt-5 text-semi-bold text-primary">
                        {{ $home['contacts'] ?? '' }}</p>
                </div>
            </div>
        </div>
        @endif
        <div class="col-12 col-md-4 mb-3">
            <div class="bg-dark-100 card rounded-md shadow">
                <div class="card-body">
                    <h5>{{ __('dash.notifications') }}</h5>
                    <p style="font-size: xxx-large;"
                        class="{{ lang('en') ? 'float-lg-end' : 'float-lg-start' }} mt-5 text-semi-bold text-primary">
                        {{ $home['notifications'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
