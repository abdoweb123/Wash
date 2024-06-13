<div class="card-styles mt-4">
    <input wire:model.live='search' type="text" style="width: 20%;" class="form-control w-20" placeholder="{{ __('dash.searchFor') }} {{ __('dash.name') }} , {{ __('dash.phone') }}, {{ __('dash.company') }}">
    <div class="table-wrapper table-responsive">
        <table class="table clients-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('dash.created_at') }}</th>
                    <th>{{ __('dash.company') }}</th>
                    <th>{{ __('dash.name') }}</th>
                    <th>{{ __('dash.phone') }}</th>
                    <th>{{ __("dash.price") }}</th>
                    <th>{{ __("dash.payment_method") }}</th>
                    <th>Transaction no</th>
                    <th>{{ __('dash.current_status') }}</th>
                    <th>{{ __('dash.actions') }}</th> 
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr class="text-center">
                    <td>{{ $orders->firstItem() + $loop->index }}</td>
                    <td>{{ $order->created_at->format('Y/m/d H:i a') }}</td>
                    <td>{{ $order->company['title_'.lang()] }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->user->phone }}</td>
                    <td>{{ $order->net_total }}</td>
                    <td>{{ $order->payment_method['name_'.lang()] }}</td>
                    <td>
                        @if ($order->transaction_number)
                            @if ($order->is_paid)
                                <i class="fa-regular fa-circle-check text-success"></i>
                            @else
                                <i class="fa-regular fa-circle-xmark text-danger"></i>
                            @endif
                        @endif
                        {{ $order->transaction_number }}
                    </td>
                    {{-- <td>{{ $order->payment_method['name_'.lang()] }}</td> --}}
                    <td>
                        <span class="alert-success px-3 rounded-2">{{ $order->status == null ? 'new' : __('dash.'.$order->status) }}</span>
                    </td>
                    <td>
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
