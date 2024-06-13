@section('title')
    <x-pageTitle current="{{ __('dash.companies') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.companies') }}" active />
    </x-pageTitle>
@endsection

<div>
    <x-table :pagination="$companies" :columns="['logo', 'title_ar', 'title_en']">
        @forelse ($companies as $company)
            <tr Role="row" class="odd">
                <td>{{ $companies->firstItem() + $loop->index }}</td>
                <td>
                    <img src="{{ asset($company->logo) }}" style="width: 100px; height:100px">
                </td>
                <td>{{ $company->title_ar }}</td>
                <td>{{ $company->title_en }}</td>
                <td>
                    <a wire:click="openShowModal({{ $company->id }})" type="button" data-bs-toggle="modal" data-bs-target="#showModal">
                        <i class="text-info lni lni-eye mr-10"></i>
                    </a>
                    <a wire:click="openEditModal({{ $company->id }})" type="button" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="text-primary lni lni-pencil mr-10"></i>
                    </a>
                    <a wire:click="openDeleteModal({{ $company->id }})" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
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
            <x-form.select name="phone_code" label="{{ __('dash.phone_code') }}">
                @foreach(Countries() as $country)
                  <x-form.option name="{{$country->phone_code}} ({{$country['title_'.lang()]}})" value="{{$country->phone_code}}"></x-form.option>
                @endforeach
                
            </x-form.select>
            <x-form.input name="phone" label="{{ __('dash.phone') }}"></x-form.input>
            <x-form.input name="google_map_link" label="{{ __('dash.google_map_link') }}"></x-form.input>
            <x-form.regtextarea name="disc_ar" class="col-12" label="{{ __('dash.disc_ar') }}"></x-form.regtextarea>
            <x-form.regtextarea name="disc_en" class="col-12" label="{{ __('dash.disc_en') }}"></x-form.regtextarea>
            <x-form.inputImage name="logo" class="col-6" label="{{ __('dash.image') }}"></x-form.inputImage>
            <div class="col-6">
                @if ($logo ?? false)
                    <p>{{ __('dash.waiting_for_upload') }}:</p>
                    <div class="image-container float-start">
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ $logo->temporaryUrl() }}">
                    </div>
                @endif
            </div>

            
            <x-form.input name="company_name" label="{{ __('dash.company_name') }}"></x-form.input>
            <x-form.input name="iban_number" label="{{ __('dash.iban_number') }}"></x-form.input>
            <x-form.input name="bank_name" label="{{ __('dash.bank_name') }}"></x-form.input>
            <x-form.input name="beneficiary_name" label="{{ __('dash.beneficiary_name') }}"></x-form.input>
            <hr>
            <x-form.input name="email" label="{{ __('dash.email') }}"></x-form.input>
            <x-form.input name="password" label="{{ __('dash.password') }}"></x-form.input>
        </div>
    </x-modal>

    {{-- Edit --}}
    <x-modal wireAction="update" id="editModal" title="{{ __('dash.update') }}" type="info">
        <div class="row">
            <x-form.input name="title_ar" label="{{ __('dash.title_ar') }}"></x-form.input>
            <x-form.input name="title_en" label="{{ __('dash.title_en') }}"></x-form.input>
           <x-form.select name="phone_code" label="{{ __('dash.phone_code') }}">
                @foreach(Countries() as $country)
                  <x-form.option name="{{$country->phone_code}} ({{$country['title_'.lang()]}})" value="{{$country->phone_code}}"></x-form.option>
                @endforeach
                
            </x-form.select>
            <x-form.input name="phone" label="{{ __('dash.phone') }}"></x-form.input>
            <x-form.input name="google_map_link" label="{{ __('dash.google_map_link') }}"></x-form.input>
            <x-form.regtextarea name="disc_ar" class="col-12" label="{{ __('dash.disc_ar') }}"></x-form.regtextarea>
            <x-form.regtextarea name="disc_en" class="col-12" label="{{ __('dash.disc_en') }}"></x-form.regtextarea>
            <x-form.inputImage name="image" class="col-6" label="{{ __('dash.image') }}"></x-form.inputImage>
            <div class="col-6">
                @if ($logo)
                    <p>{{ __('dash.waiting_for_upload') }}:</p>
                    <div class="image-container float-start">
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ $logo->temporaryUrl() }}">
                    </div>
                @elseif ($old_logo)
                    <p>{{ __('dash.existing') }}:</p>
                    <div class="image-container float-start">
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ asset($old_logo) }}">
                    </div>
                @endif
            </div>

            
            <x-form.input name="company_name" label="{{ __('dash.company_name') }}"></x-form.input>
            <x-form.input name="iban_number" label="{{ __('dash.iban_number') }}"></x-form.input>
            <x-form.input name="bank_name" label="{{ __('dash.bank_name') }}"></x-form.input>
            <x-form.input name="beneficiary_name" label="{{ __('dash.beneficiary_name') }}"></x-form.input>
            <hr>
            <x-form.input name="email" label="{{ __('dash.email') }}"></x-form.input>
            <x-form.input name="password" label="{{ __('dash.password') }}"></x-form.input>
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
    
    {{-- show --}}
    <x-modal size="modal-lg" id="showModal" title="{{ __('dash.show') }}" type="danger">
        <div class="row">
            <table class="table table-bordered">
                <tbody>
                    <x-showitem name="{{ __('dash.created_at') }}">
                        <x-fulldate :date="$created_at"></x-fulldate>
                    </x-showitem>
                    <x-showitem name="{{ __('dash.email') }}">{{ $email }}</x-showitem>
                    <x-showitem name="{{ __('dash.title_ar') }}">{{ $title_ar }}</x-showitem>
                    <x-showitem name="{{ __('dash.title_en') }}">{{ $title_en }}</x-showitem>
                    <x-showitem name="{{ __('dash.phone') }}">{{ $phone }}</x-showitem>
                    <x-showitem name="{{ __('dash.disc_ar') }}">{{ $disc_ar }}</x-showitem>
                    <x-showitem name="{{ __('dash.disc_en') }}">{{ $disc_en }}</x-showitem>
                </tbody>
            </table>
        </div>
    </x-modal>    
</div>

@section('js')
<x-closeModal />
@endsection
