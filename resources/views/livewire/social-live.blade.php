@section('title')
    <x-pageTitle current="{{ __('dash.social_links') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.social_links') }}" active />
    </x-pageTitle>
@endsection

<div>
    <x-table :columns="['image', 'title_ar', 'title_en', 'user_type']">
        @forelse ($socials as $social)
            <tr Role="row" class="odd">
                <td>{{ $loop->index + 1 }}</td>
                <td>
                    <a target="_blank" href="{{ $social->link }}">
                        <img src="{{ asset($social->image) }}" style="width: 100px; height:100px">
                    </a>
                </td>
                <td>{{ $social->title_ar }}</td>
                <td>{{ $social->title_en }}</td>
                <td>{{ $social->user_type == 'client' ? __('dash.client') : __('dash.admin') }}</td>
                <td>
                    <a wire:click="openEditModal({{ $social->id }})" type="button" data-bs-toggle="modal"
                        data-bs-target="#editModal">
                        <i class="text-primary lni lni-pencil mr-10"></i>
                    </a>
                    <a wire:click="openDeleteModal({{ $social->id }})" type="button" data-bs-toggle="modal"
                        data-bs-target="#deleteModal">
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
            <x-form.input name="link" label="{{ __('dash.link') }}"></x-form.input>
            <x-form.inputImage name="image" class="col-6" label="{{ __('dash.image') }}"></x-form.inputImage>
            <div class="col-6">
                @if ($image ?? false)
                    <p>{{ __('dash.waiting_for_upload') }}:</p>
                    <div class="image-container float-start">
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ $image->temporaryUrl() }}">
                    </div>
                @endif
            </div>
            {{-- <label id="user_type" class="form-label">{{ __('dash.user_type') }}</label>
            <select for="user_type" name="user_type" class="form-control">

                <option selected value="client">
                    {{ __('dash.client') }}</option>
                <option value="admin">
                    {{ __('dash.admin') }}</option>

            </select> --}}
            <x-form.select name="user_type" label="{{ __('dash.user_type') }}">
                <x-form.option name="{{ __('dash.client') }}" value="client"></x-form.option>
                <x-form.option name="{{ __('dash.admin') }}" value="admin"></x-form.option>
            </x-form.select>

        </div>
    </x-modal>

    {{-- Edit --}}
    <x-modal wireAction="update" id="editModal" title="{{ __('dash.update') }}" type="info">
        <div class="row">
            <x-form.input name="title_ar" label="{{ __('dash.title_ar') }}"></x-form.input>
            <x-form.input name="title_en" label="{{ __('dash.title_en') }}"></x-form.input>
            <x-form.input name="link" label="{{ __('dash.link') }}"></x-form.input>
            <x-form.inputImage name="image" class="col-6" label="{{ __('dash.image') }}"></x-form.inputImage>
            <div class="col-6">
                @if ($image)
                    <p>{{ __('dash.waiting_for_upload') }}:</p>
                    <div class="image-container float-start">
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ $image->temporaryUrl() }}">
                    </div>
                @elseif ($old_image)
                    <p>{{ __('dash.existing') }}:</p>
                    <div class="image-container float-start">
                        <img class="rounded" style="width: 100px; height: 100px;" src="{{ asset($old_image) }}">
                    </div>
                @endif
            </div>
            <x-form.select name="user_type" label="{{ __('dash.user_type') }}">
                <x-form.option name="{{ __('dash.client') }}" value="client"></x-form.option>
                <x-form.option name="{{ __('dash.admin') }}" value="admin"></x-form.option>
            </x-form.select>
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
