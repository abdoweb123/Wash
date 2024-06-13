<?php

namespace App\Livewire\Company;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserNotification;
use App\Functions\PushNotification;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class OrdersLive extends Component
{
    use WithPagination, LivewireAlert;

    public $order_id;
    public $search;

    public function openDeleteModal($order_id)
    {
        $this->order_id = $order_id;
    }

    public function delete()
    {
        $order = Order::find($this->order_id);
        if($order){
            $order->delete();
            $this->alert('success', __('dash.alert_delete'));
        }
    }
    public function changeStatus($order_id)
    {
        $order = Order::find($order_id);
        if($order->status == null){
            $order->status = 'approved';
        }elseif($order->status == 'approved'){
            $order->status = 'onway';
        }elseif($order->status == 'onway'){
            $order->status = 'processing';
        }elseif($order->status == 'processing'){
            $order->status = 'done';
        }
        $order->save();
        $this->sendNotification($order->user_id, $order->id, $order->status);
        $this->alert('success', __('dash.alert_update'));
    }

    public static function sendNotification($user_id, $order_id, $status)
    {
        $noti = new UserNotification();
        $noti->user_id = $user_id;
        $noti->order_id = $order_id;
        if($status == 'approved'){
            $noti->body_ar = "تمت الموافقة على طلبك";
            $noti->body_en = "Your order has been approved";
        }elseif($status == 'onway'){
            $noti->body_ar = "طلبك في الطريق اليك";
            $noti->body_en = "Your order is on way";
        }elseif($status == 'processing'){
            $noti->body_ar = "جاري العمل على طلبك";
            $noti->body_en = "Your order is under processing";
        }elseif($status == 'done'){
            $noti->body_ar = "تم الانتهاء من طلبك";
            $noti->body_en = "Your order has been completed";
        }
        $noti->save();

        PushNotification::send($noti['body_'.lang()], null);
    }


    public function render()
    {
        $company_id = auth('admin')->user()->company_id;
        if(!$company_id){
            abort(403);
        }
        $orders = Order::with('user', 'payment_method')
        ->when($this->search, function ($query) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'LIKE', '%' . $this->search . '%')
                ->orWhere('phone', 'LIKE', '%' . $this->search . '%');
            });
        })
        ->where('company_id', $company_id)->latest()->paginate(20);
        return view('livewire.company.orders-live', compact('orders'))
        ->extends('admin.layout')
        ->section('content');
    }
}
