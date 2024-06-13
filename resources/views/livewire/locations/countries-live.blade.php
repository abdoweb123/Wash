@section('title')
    <x-pageTitle current="{{ __('dash.countries') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.countries') }}" active />
    </x-pageTitle>
@endsection

<div>
    <x-table :columns="['name_ar', 'name_en']">
        @forelse ($countries as $country)
            <tr Role="row" class="odd">
                <td>{{ $loop->index + 1 }}</td>
                {{-- <td>
                    <img src="{{ asset($country->image) }}" style="width: 100px; height: 100px;">
                </td> --}}
                <td>{{ $country->title_ar }}</td>
                <td>{{ $country->title_en }}</td>
                {{-- <td>
                    <span class="status-btn {{ $country->accept_orders ? 'active-btn' : 'close-btn' }}">
                        {{ $country->accept_orders ? __('dash.accept') : __('dash.no') }}
                        <i wire:key="{{ $country->id }}" wire:click="changeStatus({{ $country->id }})" wire:loading.attr='disabled' role="button" class="fa-solid fa-retweet"></i>
                    </span>    
                </td> --}}
                <td>
                    <a href="{{ route('dashboard.regions', $country->id) }}" class="btn btn-success">{{ __('dash.regions') }}</a>
                    {{-- <a wire:click="openShowModal({{ $company->id }})" type="button" data-bs-toggle="modal" data-bs-target="#showModal">
                        <i class="text-info lni lni-eye mr-10"></i>
                    </a> --}}
                    {{-- <a wire:click="openEditModal({{ $country->id }})" country="button" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="text-primary lni lni-pencil mr-10"></i>
                    </a>
                    <a wire:click="openDeleteModal({{ $country->id }})" country="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="text-danger lni lni-trash-can"></i>
                    </a> --}}
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

</div>

@section('js')
<x-closeModal />
@endsection
