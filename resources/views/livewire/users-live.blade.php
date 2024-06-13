@section('title')
    <x-pageTitle current="{{ __('dash.users') }}">
        <x-breadcrumb title="{{ __('dash.home') }}" route="{{ route('dashboard.home') }}" />
        <x-breadcrumb title="{{ __('dash.users') }}" active />
    </x-pageTitle>
@endsection

<div>
    <button data-bs-toggle="modal" data-bs-target="#sendModal" class="btn btn-dark">{{ __('dash.message') }}</button>
    <x-table :pagination="$users" :columns="['name', 'phone', 'email', 'devices', 'created_at']" noAction noCreate>
        @forelse ($users as $user)
            <tr Role="row" class="odd">
                <td>{{ $users->firstItem() + $loop->index }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->tokens_count }}</td>
                <td>
                    <x-fulldate :date="$user->created_at"></x-fulldate>
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


    <x-modal wireAction="sendMsg" id="sendModal" title="{{ __('dash.message') }}" type="primary">
        <div class="row">
            <x-form.regtextarea name="message" label="{{ __('dash.message') }}"></x-form.regtextarea>
        </div>
    </x-modal>

        {{-- show --}}
        {{-- <x-modal size="modal-lg" id="showModal" title="{{ __('dash.show') }}" type="danger">
            <div class="row">
                <table class="table table-busered">
                    <tbody>
                        <x-showitem name="{{ __('dash.created_at') }}">
                            <x-fulldate :date="$created_at"></x-fulldate>
                        </x-showitem>
                        <x-showitem name="{{ __('dash.name') }}">{{ $name }}</x-showitem>
                        <x-showitem name="{{ __('dash.email') }}">{{ $email }}</x-showitem>
                        <x-showitem name="{{ __('dash.message') }}">{{ $message }}</x-showitem>
                    </tbody>
                </table>
            </div>
        </x-modal> --}}
    
</div>

@section('js')
<x-closeModal />
@endsection
