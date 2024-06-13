@section('title')
    <x-pageTitle current="{{ __('dash.services') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.services') }}" active />
    </x-pageTitle>
@endsection

<div>
    <x-table addFun :columns="['service', 'price', 'cleaning_materials_cost']">
        @forelse ($company_services as $c_service)
            <tr Role="row" class="odd">
                <td>{{ $loop->index + 1}}</td>
                <td>
                    {{ $c_service->service['title_'.lang()] }}
                </td>
                <td>{{ $c_service->price }} / {{ $c_service->standard['singular_title_'.lang()] }}</td>
                <td>{{ $c_service->cleaning_materials_cost }}</td>
                <td>
                    <a wire:click="openEditModal({{ $c_service->id }})" type="button" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="text-primary lni lni-pencil mr-10"></i>
                    </a>
                    <a wire:click="openDeleteModal({{ $c_service->id }})" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
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
            <x-form.select name="service" label="{{ __('dash.service') }}">
                @foreach ($services ?? [] as $service)
                    <option value="{{ $service->id }}">{{ $service['title_'.lang()] }}</option>
                @endforeach
            </x-form.select>
            <x-form.select name="standard" label="{{ __('dash.standard') }}">
                @foreach ($standards as $standard)
                    <option value="{{ $standard->id }}">{{ $standard['singular_title_'.lang()] .' / '. $standard['plural_title_'.lang()] }}</option>
                @endforeach
            </x-form.select>
            <x-form.input type="number" name="price" label="{{ __('dash.price') }}"></x-form.input>
            <x-form.input type="number" name="cleaning_materials_cost" label="{{ __('dash.cleaning_materials_cost') }}"></x-form.input>
            <x-form.regtextarea name="disc_ar" class="col-12" label="{{ __('dash.disc_ar') }}"></x-form.regtextarea>
            <x-form.regtextarea name="disc_en" class="col-12" label="{{ __('dash.disc_en') }}"></x-form.regtextarea>
        </div>
    </x-modal>

    {{-- Edit --}}
    <x-modal wireAction="update" id="editModal" title="{{ __('dash.update') }}" type="info">
        <div class="row">
            <x-form.select name="service" label="{{ __('dash.service') }}">
                @foreach ($services ?? []  as $service)
                    <option value="{{ $service->id }}">{{ $service['title_'.lang()] }}</option>
                @endforeach
            </x-form.select>
            <x-form.select name="standard" label="{{ __('dash.standard') }}">
                @foreach ($standards as $standard)
                    <option value="{{ $standard->id }}">{{ $standard['singular_title_'.lang()] .' / '. $standard['plural_title_'.lang()] }}</option>
                @endforeach
            </x-form.select>
            <x-form.input type="number" name="price" label="{{ __('dash.price') }}"></x-form.input>
            <x-form.input type="number" name="cleaning_materials_cost" label="{{ __('dash.cleaning_materials_cost') }}"></x-form.input>
            <x-form.regtextarea name="disc_ar" class="col-12" label="{{ __('dash.disc_ar') }}"></x-form.regtextarea>
            <x-form.regtextarea name="disc_en" class="col-12" label="{{ __('dash.disc_en') }}"></x-form.regtextarea>
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
