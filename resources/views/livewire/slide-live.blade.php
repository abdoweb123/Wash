@section('title')
    <x-pageTitle current="{{ __('dash.slider') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.slider') }}" active />
    </x-pageTitle>
@endsection

<div>
    <x-table :columns="['image', 'paragraph_ar', 'paragraph_en', 'link']">
        @forelse ($slides as $slide)
            <tr Role="row" class="odd">
                <td>{{ $loop->index + 1 }}</td>
                <td>
                    <img src="{{ asset($slide->image) }}" style="width: 200px; height:100px">
                </td>
                <td>{{ $slide->paragraph_ar }}</td>
                <td>{{ $slide->paragraph_en }}</td>
                <td>
                    <a wire:click="openEditModal({{ $slide->id }})" type="button" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="text-primary lni lni-pencil mr-10"></i>
                    </a>
                    <a wire:click="openDeleteModal({{ $slide->id }})" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
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
            <x-form.input name="paragraph_ar" label="{{ __('dash.paragraph_ar') }}"></x-form.input>
            <x-form.input name="paragraph_en" label="{{ __('dash.paragraph_en') }}"></x-form.input>
            <x-form.inputImage name="image" class="col-6" label="{{ __('dash.image') }}"></x-form.inputImage>
            <div class="col-6">
                @if ($image ?? false)
                    <p>{{ __('dash.waiting_for_upload') }}:</p>
                    <div class="image-container float-start">
                        <img class="rounded" style="width: 200px; height: 100px;" src="{{ $image->temporaryUrl() }}">
                    </div>
                @endif
            </div>
        </div>
    </x-modal>

    {{-- Edit --}}
    <x-modal wireAction="update" id="editModal" title="{{ __('dash.update') }}" type="info">
        <div class="row">
            <x-form.input name="paragraph_ar" label="{{ __('dash.paragraph_ar') }}"></x-form.input>
            <x-form.input name="paragraph_en" label="{{ __('dash.paragraph_en') }}"></x-form.input>
            <x-form.inputImage name="image" class="col-6" label="{{ __('dash.image') }}"></x-form.inputImage>
            <div class="col-6">
                @if ($image)
                    <p>{{ __('dash.waiting_for_upload') }}:</p>
                    <div class="image-container float-start">
                        <img class="rounded" style="width: 200px; height: 100px;" src="{{ $image->temporaryUrl() }}">
                    </div>
                @elseif ($old_image)
                    <p>{{ __('dash.existing') }}:</p>
                    <div class="image-container float-start">
                        <img class="rounded" style="width: 200px; height: 100px;" src="{{ asset($old_image) }}">
                    </div>
                @endif
            </div>
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
