<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Resources\OldOrdersResource;

class ProfileController extends Controller
{
    public function old_orders()
    {
        $user = auth('sanctum')->user();
        $orders = Order::where('user_id', $user->id)->withCount('order_details')->get();
        $data = [
            'orders' => OldOrdersResource::collection($orders)
        ];
        return ResponseHelper::make($data);
    }

    public function old_order_details($order_id)
    {
        $user = auth('sanctum')->user();
        $order = Order::where('id', $order_id)->where('user_id', $user->id)->withCount('order_details')->first();
        if(!$order){
            return ResponseHelper::make(NULL, 'dev:order_id is not found!',false,200);
        }
        $cards_ids = OrderDetail::where('order_id', $order_id)->pluck('card_id');
        $cards = Card::whereIn('id', $cards_ids)->get();
        $totals = [
            "sub_total" => amount_format($order->sub_total),
            "promo_code_discount" => amount_format($order->promo_code_discount) ?? 0,
            "vat_cost" => amount_format($order->vat_cost),
            "net_total" => amount_format($order->net_total),
        ];
        $data = [
            'order' => OldOrdersResource::make($order),
            'totals' => $totals,
            'cards' => CardResource::collection($cards)
        ];
        return ResponseHelper::make($data);
    }

}
