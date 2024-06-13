@extends('admin.layout')
@section('title')
    <x-pageTitle current="{{ __('dash.public_setting') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.public_setting') }}" active />
    </x-pageTitle>
@endsection

@section('content')

    <form action="{{ route('dashboard.public_setting_post') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="bg-white m-2 mt-10 p-2 rounded-3 row shadow-sm">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
    
            @foreach ($settings as $setting)
                @if ($setting->type == 'string')
                    <div class='form-group mt-10 col-12 col-md-6'>
                        <label class="w-100" for="{{ $setting->key }}">{{ __('dash.'.$setting->key) }}</label>
                        <input id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" type="text">
                    </div>            
                @elseif ($setting->type == 'text')
                    <div class ='form-group mt-10 col-12 col-md-6'>
                        <label class="w-100" for="{{ $setting->key }}">{{ __('dash.'.$setting->key) }}</label>
                        <textarea rows="10" id="{{ $setting->key }}" name="{{ $setting->key }}" class="form-control">{{ $setting->value }}</textarea>
                    </div>            
                @elseif ($setting->type == 'editor')
                    <div class ='form-group mt-10 col-12 col-md-6'>
                        <label class="w-100" for="{{ $setting->key }}">{{ __('dash.'.$setting->key) }}</label>
                        <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" class="form-control">{{ $setting->value }}</textarea>
                    </div>            
                @elseif ($setting->type == 'image')
                    <div class ='form-group mt-10 col-12 col-md-6'>
                        <label class="w-100" for="{{ $setting->key }}">{{ __('dash.'.$setting->key) }}</label>
                        <input id="{{ $setting->key }}" name="{{ $setting->key }}" class="form-control" type="file">
                    </div>            
                @endif
                @if($errors->has($setting->key))
                    <span class="text-danger">{{ $errors->first($setting->key) }}</span>
                @endif
                @if ($setting->hr == true)
                    <hr class="mt-5">
                @endif
                @if ($setting->br == true)
                    <div></div>
                @endif
            @endforeach

            <button type="submit" class="btn btn-success w-auto mt-5">{{ __('dash.submit') }}</button>
        </div>

    </form>
@endsection