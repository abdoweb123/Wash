@section('title')
    <x-pageTitle current="{{ __('dash.regions') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.regions') }}" active />
    </x-pageTitle>
@endsection

<div>
    <x-table :columns="['name_ar', 'name_en', 'delivery_cost']">
        @forelse ($regions as $region)
            <tr Role="row" class="odd">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $region->title_ar }}</td>
                <td>{{ $region->title_en }}</td>
                <td>{{ $region->delivery_cost }}</td>
                <td>
                    <a wire:click="openEditModal({{ $region->id }})" type="button" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="text-primary lni lni-pencil mr-10"></i>
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

        {{-- Edit --}}
        <x-modal wireAction="update" id="editModal" title="{{ __('dash.update') }}" type="info">
            <div class="row">
                <div class="row">
                    <x-form.input type="number" name="delivery_cost" label="{{ __('dash.delivery_cost') }}"></x-form.input>
                </div>
            </div>
        </x-modal>    
</div>

@section('js')
<x-closeModal />
@endsection
