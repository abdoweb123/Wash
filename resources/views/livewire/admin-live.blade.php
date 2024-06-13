@section('title')
    <x-pageTitle current="{{ __('dash.admins') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.admins') }}" active />
    </x-pageTitle>
@endsection

<div>
    <x-table :pagination="$admins" :columns="['name', 'email','phone','is_approved']" searchable="{{ __('dash.name') }}, {{ __('dash.email') }}, {{ __('dash.phone') }}">
        @forelse ($admins as $admin)
            <tr Role="row" class="odd">
                <td>{{ $admins->firstItem() + $loop->index }}</td>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ $admin->phone }}</td>
                <td>
                    @if ($admin->is_approved == 0)
                        <span role="button" wire:click='changeStatus({{ $admin->id }})'
                            class="bg-info-100 px-4 py-2 rounded-5 rounded-full shadow-sm">
                            <i class="fa-regular fa-clock"></i>
                            {{ __('dash.not approved') }}
                        </span>
                    @else
                        <span role="button" wire:click='changeStatus({{ $admin->id }})'
                            class="bg-info-600 px-4 py-2 rounded-5 rounded-full">
                            <i class="fa-solid fa-check"></i>
                            {{ __('dash.approvedAdmin') }}
                        </span>
                    @endif
                </td>
                <td>
                    <a wire:click="openEditModal({{ $admin->id }})" type="button" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="text-primary lni lni-pencil mr-10"></i>
                    </a>
                    @if($admin->company_id!=null)
                        <a wire:click="openDeleteModal({{ $admin->id }})" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="text-danger lni lni-trash-can"></i>
                        </a>
                    @endif
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
            <x-form.input name="name" label="{{ __('dash.name') }}"></x-form.input>
            <x-form.input name="email" type="email" label="{{ __('dash.email') }}"></x-form.input>
            <x-form.select name="phone_code" label="{{ __('dash.phone_code') }}">
                @foreach(Countries() as $country)
                  <x-form.option name="{{$country->phone_code}} ({{$country['title_'.lang()]}})" value="{{$country->phone_code}}"></x-form.option>
                @endforeach
                
            </x-form.select>
            <x-form.input name="phone" type="text" label="{{ __('dash.phone') }}"></x-form.input>
            <x-form.input name="password" label="{{ __('dash.password') }}"></x-form.input>
        </div>
    </x-modal>
    
    
    {{-- Edit --}}
    <x-modal wireAction="update" id="editModal" title="{{ __('dash.update') }}" type="info">
        <div class="row">
            <x-form.input name="name" label="{{ __('dash.name') }}"></x-form.input>
            <x-form.input name="email" type="email" label="{{ __('dash.email') }}"></x-form.input>
             <x-form.select name="phone_code" label="{{ __('dash.phone_code') }}">
                @foreach(Countries() as $country)
                  <x-form.option name="{{$country->phone_code}} ({{$country['title_'.lang()]}})"  value="{{$country->phone_code}}"></x-form.option>
                @endforeach
                
            </x-form.select>
            <x-form.input name="phone" type="text" label="{{ __('dash.phone') }}"></x-form.input>
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
</div>

@section('js')
<x-closeModal />
@endsection
