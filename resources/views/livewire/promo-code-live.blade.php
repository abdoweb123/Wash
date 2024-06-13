@section('title')
    <x-pageTitle current="{{ __('dash.promo_codes') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.promo_codes') }}" active />
    </x-pageTitle>
@endsection

<div>
    <x-table :pagination="$promos" :columns="['code', 'discount', 'from', 'to']" searchable="{{ __('dash.code') }}">
        @forelse ($promos as $promo)
            <tr Role="row" class="odd">
                <td>{{ $promos->firstItem() + $loop->index }}</td>
                <td>
                    <strong>
                        {{ $promo->code }}
                    </strong>
                </td>
                <td>{{ $promo->discount }}</td>
                <td>
                    {{ $promo->from }}
                </td>
                <td>
                    {{ $promo->to }}
                </td>
                <td>
                    <a wire:click="openEditModal({{ $promo->id }})" type="button" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="text-primary lni lni-pencil mr-10"></i>
                    </a>
                    <a wire:click="openDeleteModal({{ $promo->id }})" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
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
            <x-form.input name="code" label="{{ __('dash.code') }}"></x-form.input>
            <x-form.input name="discount" type="number" label="{{ __('dash.discount') }}"></x-form.input>
            <x-form.input name="from" type="date" label="{{ __('dash.from') }}"></x-form.input>
            <x-form.input name="to" type="date" label="{{ __('dash.to') }}"></x-form.input>
        </div>
    </x-modal>

    {{-- Edit --}}
    <x-modal wireAction="update" id="editModal" title="{{ __('dash.update') }}" type="info">
        <div class="row">
            <x-form.input name="code" label="{{ __('dash.code') }}"></x-form.input>
            <x-form.input name="discount" type="number" label="{{ __('dash.discount') }}"></x-form.input>
            <x-form.input name="from" type="date" label="{{ __('dash.from') }}"></x-form.input>
            <x-form.input name="to" type="date" label="{{ __('dash.to') }}"></x-form.input>
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
