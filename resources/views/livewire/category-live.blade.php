@section('title')
    <x-pageTitle current="{{ __('dash.categories') }}">
        <li class="breadcrumb-item active" aria-current="page">
            {{ __('dash.categories') }}
        </li>
    </x-pageTitle>
@endsection

<div>
    <button wire:click='openAddModal' type="button" class="btn btn-dark my-3 rounded-3" data-bs-toggle="modal"
        data-bs-target="#addAssignedModal">
        {{ __('dash.create_new') }}
    </button>

    @foreach ($categories as $category)
    <div class="card mb-10">
        <div class="card-header">
            <div class="float-end">
                {{ $category->title_ar }} | {{ $category->title_en }}
            </div>

            <div class="float-start">
                <a wire:click="openAddModal({{ $category->id }})" wire:key='{{$category->id }}' type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fa-solid fa-plus mr-10"></i>
                </a>
                <a wire:click="openEditModal({{ $category->id }})" wire:key='{{$category->id }}' type="button" data-bs-toggle="modal" data-bs-target="#editAssignedModal">
                    <i class="text-primary lni lni-pencil mr-10"></i>
                </a>
                <a wire:click="openDeleteModal({{ $category->id }})" wire:key='{{$category->id }}' type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="text-danger lni lni-trash-can mr-10"></i>
                </a>
            </td>
            </div>
        </div>
        <div class="card-body">
            <div class="table-wrapper table-responsive">
                <table class="table clients-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>{{ __('dash.title_ar') }}</th>
                            <th>{{ __('dash.title_en') }}</th>
                            <th>{{ __('dash.image') }}</th>
                            <th>{{ __('dash.cards_count') }}</th>
                            <th width=10%>{{ __('dash.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($category->subs as $sub_category)
                            <tr Role="row" class="odd">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sub_category->title_ar }}</td>
                                <td>{{ $sub_category->title_en }}</td>
                                <td>
                                    <img class="rounded border" style="width: 50px; height: 50px;"
                                    src="{{ asset($sub_category->image) }}" alt="">
                                </td>
                                <td>
                                    {{ $sub_category->cards_count }}
                                </td>    
                                <td>
                                    <a href="{{ route('dashboard.category_cards', $sub_category->id) }}" type="button">
                                        <i class="text-info lni lni-eye mr-10"></i>
                                    </a>    
                                    <a wire:click="openEditModal({{ $sub_category->id }})" wire:key='{{ $category->id . $sub_category->id }}' type="button" data-bs-toggle="modal" data-bs-target="#editModal">
                                        <i class="text-primary lni lni-pencil mr-10"></i>
                                    </a>
                                    <a wire:click="openDeleteModal({{ $sub_category->id }})" wire:key='{{ $category->id . $sub_category->id }}' type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="text-danger lni lni-trash-can mr-10"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>    
        </div>
    </div> 
    @endforeach


    {{-- Add addModalParent--}}
    <x-modal wireAction="storeParent" id="addAssignedModal" title="{{ __('dash.add') }}" type="primary">
        <div class="row">
            <x-form.input name="title_ar" label="{{ __('dash.title_ar') }}"></x-form.input>
            <x-form.input name="title_en" label="{{ __('dash.title_en') }}"></x-form.input>
            <x-form.inputImage name="image" class="col-6" label="{{ __('dash.image') }}"></x-form.inputImage>
            <div class="col-6">
                @if ($image ?? false)
                    <div class="image-container float-start">
                        <p>{{ __('dash.waiting_for_upload') }}</p>
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ $image->temporaryUrl() }}">
                    </div>
                @endif
            </div>
        </div>
    </x-modal>
    {{--  editAssignedModal--}}
    <x-modal wireAction="update" id="editAssignedModal" title="{{ __('dash.update') }}" type="primary">
        <div class="row">
            <x-form.input name="title_ar" label="{{ __('dash.title_ar') }}"></x-form.input>
            <x-form.input name="title_en" label="{{ __('dash.title_en') }}"></x-form.input>
            <x-form.inputImage name="image" class="col-6" label="{{ __('dash.image') }}"></x-form.inputImage>
            <div class="col-6">
                @if ($image)
                    <div class="image-container float-start">
                        <p>{{ __('dash.waiting_for_upload') }}</p>
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ $image->temporaryUrl() }}">
                    </div>
                @elseif ($old_image)
                    <div class="image-container float-start">
                        <p>{{ __('dash.existing') }}</p>
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ asset($old_image) }}">
                    </div>
                @endif
            </div>
        </div>
    </x-modal>


    {{-- Add Chield --}}
    <x-modal wireAction="storeChield" id="addModal" title="{{ $modal_title ?? '' }}"  type="primary">
        <div class="row">
            <x-form.input name="title_ar" label="{{ __('dash.title_ar') }}"></x-form.input>
            <x-form.input name="title_en" label="{{ __('dash.title_en') }}"></x-form.input>
            <x-form.inputImage name="image" class="col-6" label="{{ __('dash.image') }}"></x-form.inputImage>
            <div class="col-6">
                @if ($image ?? false)
                    <div class="image-container float-start">
                        <p>{{ __('dash.waiting_for_upload') }}</p>
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ $image->temporaryUrl() }}">
                    </div>
                @endif
            </div>
        </div>
    </x-modal>

    {{-- Edit --}}
    <x-modal wireAction="update" id="editModal" title="{{ __('dash.update') }}" type="info">
        <div class="row">
            <x-form.input name="title_ar" label="{{ __('dash.title_ar') }}"></x-form.input>
            <x-form.input name="title_en" label="{{ __('dash.title_en') }}"></x-form.input>
            <x-form.inputImage name="image" class="col-6" label="{{ __('dash.image') }}"></x-form.inputImage>
            <div class="col-6">
                @if ($image)
                    <div class="image-container float-start">
                        <p>{{ __('dash.waiting_for_upload') }}</p>
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ $image->temporaryUrl() }}">
                    </div>
                @elseif ($old_image)
                    <div class="image-container float-start">
                        <p>{{ __('dash.existing') }}</p>
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ asset($old_image) }}">
                    </div>
                @endif
            </div>
        </div>
    </x-modal>

    {{-- Delte --}}
    <x-modal wireAction="delete" id="deleteModal" title="{{ __('dash.delete') }}" type="danger">
        <div class="row">
            <x-alert type="warning">
                <h3>{{ __('dash.alert_delete_confirm') }}</h3>
            </x-alert>
        </div>
    </x-modal>

</div>

@section('js')
<script>
    window.addEventListener('closeModal', event=> {
       $('#addAssignedModal').modal('hide')
       $('#editAssignedModal').modal('hide')
       $('#addModal').modal('hide')
       $('#editModal').modal('hide')
       $('#deleteModal').modal('hide')
    });
</script>
@endsection
