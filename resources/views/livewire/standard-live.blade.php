@section('title')
    <x-pageTitle current="{{ __('dash.standards') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.standards') }}" active />
    </x-pageTitle>
@endsection

<div>
    <x-table :columns="['singular_title_ar', 'plural_title_ar','singular_title_en', 'plural_title_en']">
        @forelse ($standards as $standard)
            <tr Role="row" class="odd">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $standard->singular_title_ar }}</td>
                <td>{{ $standard->plural_title_ar }}</td>
                <td>{{ $standard->singular_title_en }}</td>
                <td>{{ $standard->plural_title_en }}</td>
                <td>
                    <a wire:click="openEditModal({{ $standard->id }})" type="button" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="text-primary lni lni-pencil mr-10"></i>
                    </a>
                    <a wire:click="openDeleteModal({{ $standard->id }})" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="text-danger lni lni-trash-can"></i>
                    </a>
                </td>
            </tr>
        @empty
        <div class="card my-4 py-4 rounded-full shadow-sm">
            <div class="card-body  text-center">
                <h4>{{ __('dash.nodata') }}</h4>
            </div>
        </div>
        @endforelse
    </x-table>

    {{-- Add --}}
    <x-modal wireAction="store" id="addModal" title="{{ __('dash.add') }}" type="primary">
        <div class="row">
            <x-form.input name="singular_title_ar" label="{{ __('dash.singular_title_ar') }}"></x-form.input>
            <x-form.input name="plural_title_ar" label="{{ __('dash.plural_title_ar') }}"></x-form.input>
            <x-form.input name="singular_title_en" label="{{ __('dash.singular_title_en') }}"></x-form.input>
            <x-form.input name="plural_title_en" label="{{ __('dash.plural_title_en') }}"></x-form.input>
        </div>
    </x-modal>

    {{-- Edit --}}
    <x-modal wireAction="update" id="editModal" title="{{ __('dash.update') }}" type="info">
        <div class="row">
            <x-form.input name="singular_title_ar" label="{{ __('dash.singular_title_ar') }}"></x-form.input>
            <x-form.input name="plural_title_ar" label="{{ __('dash.plural_title_ar') }}"></x-form.input>
            <x-form.input name="singular_title_en" label="{{ __('dash.singular_title_en') }}"></x-form.input>
            <x-form.input name="plural_title_en" label="{{ __('dash.plural_title_en') }}"></x-form.input>
        </div>
    </x-modal>

    {{-- Delete --}}
    <x-modal wireAction="delete" id="deleteModal" title="{{ __('dash.delete') }}" type="danger">
        <div class="row">
            <x-alert type="warning">
                <h3>{{ __('dash.alert_delete_confirm') }}</h3>
            </x-alert>
        </div>
    </x-modal>
    
</div>

@section('js')
<x-closeModal />
@endsection
