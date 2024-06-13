@section('title')
    <x-pageTitle current="{{ __('dash.groups') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.groups') }}" active />
    </x-pageTitle>
@endsection

<div>
    <x-table :columns="['title_ar', 'title_en']">
        @forelse ($groups as $type)
            <tr Role="row" class="odd">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $type->title_ar }}</td>
                <td>{{ $type->title_en }}</td>
                <td>
                    <a wire:click="openEditModal({{ $type->id }})" type="button" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="text-primary lni lni-pencil mr-10"></i>
                    </a>
                    <a wire:click="openDeleteModal({{ $type->id }})" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
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
            <x-form.input name="title_ar" label="{{ __('dash.title_ar') }}"></x-form.input>
            <x-form.input name="title_en" label="{{ __('dash.title_en') }}"></x-form.input>
        </div>
    </x-modal>

    {{-- Edit --}}
    <x-modal wireAction="update" id="editModal" title="{{ __('dash.update') }}" type="info">
        <div class="row">
            <x-form.input name="title_ar" label="{{ __('dash.title_ar') }}"></x-form.input>
            <x-form.input name="title_en" label="{{ __('dash.title_en') }}"></x-form.input>
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
