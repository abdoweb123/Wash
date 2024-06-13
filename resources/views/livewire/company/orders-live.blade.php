<div class="card-styles mt-4">
    <input wire:model.live='search' type="text" style="width: 20%;" class="form-control w-20" placeholder="{{ __('dash.searchFor') }} {{ __('dash.name') }} , {{ __('dash.phone') }}">
    <div class="table-wrapper table-responsive">
        <table class="table clients-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('dash.created_at') }}</th>
                    <th>{{ __('dash.name') }}</th>
                    <th>{{ __('dash.phone') }}</th>
                    <th>{{ __("dash.price") }}</th>
                    <th>{{ __("dash.payment_method") }}</th>
                    <th>{{ __('dash.current_status') }}</th>
                    <th>{{ __('dash.next_status') }}</th>
                    <th>{{ __('dash.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr class="text-center">
                    <td>{{ $orders->firstItem() + $loop->index }}</td>
                    <td>{{ $order->created_at->format('Y/m/d H:i a') }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->user->phone }}</td>
                    <td>{{ $order->net_total }}</td>
                    <td>{{ $order->payment_method['name_'.lang()] }}</td>
                    <td>
                        <span class="alert-success px-3 rounded-2">{{ $order->status == null ? 'new' : __('dash.'.$order->status) }}</span>
                    </td>
                    <td>
                        @if ($order->status == null)
                            <span role="button" wire:click='changeStatus({{ $order->id }})' class="alert-success alert-warning opacity-50 px-3 rounded-2">
                                <i class="fa-regular fa-clock"></i>
                                {{ __('dash.approved') }}
                            </span>
                        @elseif($order->status == 'approved')
                            <span role="button" wire:click='changeStatus({{ $order->id }})' class="alert-success alert-warning opacity-50 px-3 rounded-2">
                                <i class="fa-solid fa-check"></i>
                                {{ __('dash.onway') }}
                            </span>
                        @elseif($order->status == 'onway')
                            <span role="button" wire:click='changeStatus({{ $order->id }})' class="alert-success alert-warning opacity-50 px-3 rounded-2">
                                <i class="fa-solid fa-check"></i>
                                {{ __('dash.processing') }}
                            </span>
                        @elseif($order->status == 'processing')
                            <span role="button" wire:click='changeStatus({{ $order->id }})' class="alert-success alert-warning opacity-50 px-3 rounded-2">
                                <i class="fa-solid fa-check"></i>
                                {{ __('dash.done') }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <a wire:click="openDeleteModal({{ $order->id }})" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="text-danger lni lni-trash-can"></i>
                        </a>   
                        <a target="_blank" href="{{ route('dashboard.order_show', $order->id) }}" type="button">
                            <i class="text-info lni lni-eye mr-10"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            {{ $orders->links() }}
        </table>    
    </div>

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
