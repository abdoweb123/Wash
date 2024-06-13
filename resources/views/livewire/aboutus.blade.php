@section('title')
    <x-pageTitle current="{{ __('dash.about_us') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.about_us') }}" active />
    </x-pageTitle>
@endsection

<div>
    <div class="bg-body border card-body mb-15 rounded-md shadow">
        <div class="row">
            <x-form.textarea class="col-12" wire:model="about_paragraph_ar" label="{{ __('dash.desc_ar') }}"></x-form.textarea>
            <x-form.textarea class="col-12" wire:model="about_paragraph_en" label="{{ __('dash.desc_en') }}"></x-form.textarea>
        <x-form.btn wireAction="store" title="Submit" class="btn-primary mt-15 w-auto mx-2"></x-form.btn>

    </div>
</div>
