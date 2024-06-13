@section('title')
    <x-pageTitle current="{{ __('dash.tac') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.tac') }}" active />
    </x-pageTitle>
@endsection

<div>
    <div class="border card-body rounded-md shadow mb-15">
        <x-form.textarea wire:model="value_en" label="{{ __('dash.content_en') }}"></x-form.textarea>
        <x-form.textarea wire:model="value_ar" label="{{ __('dash.content_ar') }}"></x-form.textarea>
        {{-- <label id="user_type" class="form-label">{{ __('dash.user_type') }}</label>
        <select  for="user_type" name="user_type"  class="form-control">
            
            <option    value="client">
                {{ __('dash.client') }}</option>
            <option  value="admin">
                {{ __('dash.admin') }}</option>
          
        </select> --}}
        <button wire:click="store" wire:loading.attr="disabled" class="mt-2 btn btn-primary">{{ __('dash.submit') }}</button>
    </div>
</div>