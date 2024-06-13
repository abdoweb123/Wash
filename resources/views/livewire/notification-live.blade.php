@section('title')
    <x-pageTitle current="{{ __('dash.notifications') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.notifications') }}" active />
    </x-pageTitle>
@endsection

<div>
    <x-table :pagination="$notifications"
        :columns="['title', 'from', 'body', 'created_at']"
        searchable="all data"
        noCreate
        deleteAll
    >
        @forelse ($notifications as $noti)
            <tr Role="row" class="odd">
                <td>{{ $notifications->firstItem() + $loop->index }}</td>
                <td>{{ $noti['title_'.lang()] }}</td>
                <td>{{ $noti['from'] }}</td>
                <td>{{ $noti['body_'.lang()] }}</td>
                <td>
                    <x-fulldate :date="$noti->created_at"></x-fulldate>
                </td>
                <td>
                    <a wire:click="openDeleteModal({{ $noti->id }})" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
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
