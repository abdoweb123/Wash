<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Card;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Serial;
use App\Models\Address;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\OrderDetail;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\CompanyService;
use App\Models\UserNotification;
use App\Functions\ResponseHelper;
use App\Functions\PushNotification;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AddressResource;
use App\Http\Resources\PaymentResource;
use App\Http\Requests\UseCouponeRequest;
use App\Http\Resources\CartItemsResource;
use App\Http\Resources\OldOrdersResource;
use App\Http\Requests\OrderRequestRequest;
use App\Http\Resources\OrderDetailsResource;

class OrderController extends Controller
{
    public function payment_methods()
    {
        $methods = PaymentMethod::query()->where('display',1)->get();

        $data = [
            'methods' => PaymentResource::collection($methods),
        ];

        return ResponseHelper::make($data);
    }

    public function store(OrderRequest $request)
    {
//        return env('APP_URL').'da';

        $cart = Cart::query()->
            when(auth('sanctum')->check(), function ($query) {
                return $query->where('user_id', auth('sanctum')->id());
            })->first();
        if (!$cart) {
            return ResponseHelper::make(null, 'div: cart is empty :(', false, 200);
        }
        $order = new Order();
        $user = auth('sanctum')->user();
        $order->user_id = $user->id;
        $order->company_id = $cart->company_service->company_id;
        $order->address_id = $request->address_id;
        $order->date = $request->date;
        $order->time = Carbon::parse($request->time)->format('H:i');
        $order->payment_method_id = $request->payment_method_id;
        $order->save();

        $order->sub_total = $this->storeDetails($order->id);
        $order->vat_cost = $order->sub_total * (setting('vat') / 100);
        $order->net_total = $order->sub_total + $order->vat_cost;
        $order->save();
        $order->order_details;

        $source = PaymentMethod::find($request->payment_method_id)->tap_src;

        $data = [
            'order_id' => $order->id,
            'date' => Carbon::parse($order->date)->format('d F Y'),
            'day' => Carbon::parse($order->date)->format('l'),
            'time' => Carbon::parse($order->time)->format('H:i a'),
        ];

        $this->sendNotification($order->company_id, auth('sanctum')->user()->name);

        //for check only
        if ($source == null) {
            $order->save();
            Cart::where('user_id', $user->id)->delete();
            return ResponseHelper::make($data, __('dash.orderd_successfully'));
        } else {

            $tap = new TapController();
            $result = $tap->VerifyTapTransaction($order->id, $source);
            if ($result['success'] == false) {
                $data['error_msg'] = $result['error_msg'];
                return ResponseHelper::make($data, null, false);

            } else {
                $data['transaction_url'] = $result['transaction_url'];
                return ResponseHelper::make($data);
            }
        }

    }

    protected static function storeDetails($order_id)
    {
        $total = 0;
        $cart_items = Cart::query()
            ->when(auth('sanctum')->check(), function ($query) {
                return $query->where('user_id', auth('sanctum')->id());
            })->get();

        foreach ($cart_items as $cart_item) {
            $companyService = CompanyService::find($cart_item['company_service_id']);

            $detail = new OrderDetail();
            $detail->order_id = $order_id;
            $detail->company_service_id = $cart_item['company_service_id'];
            $detail->standard_id = $companyService->standard_id;
            $detail->standard_quantity = $cart_item['standard_quantity'];
            $detail->need_materials = $cart_item['need_materials'];
            $detail->cleaning_materials_cost = $cart_item['need_materials'] == true ? $companyService->cleaning_materials_cost : 0;
            $detail->title_ar = $companyService->service->title_ar;
            $detail->title_en = $companyService->service->title_en;
            $detail->price = $companyService->price;
            $detail->note = $cart_item['note'];
            $detail->save();

            $total += ($companyService->price + $detail->cleaning_materials_cost) * $cart_item['standard_quantity'];
        }

        return $total;
    }

    public static function sendNotification($company_id, $from)
    {
        $notification = new Notification();
        $notification->company_id = $company_id;
        $notification->title_ar = 'طلب جديد';
        $notification->title_en = 'New Order';
        $notification->from = $from;
        $notification->body_ar = 'هناك طلب جديد وصل اليك في صفحة الطلبات';
        $notification->body_en = 'There is a new Order coming to orders Page';
        $notification->link = route('dashboard.company.orders');
        $notification->save();
    }

    public function changeStatus($order_id)
    {
        $order = Order::find($order_id);
        if (!$order) {
            return ResponseHelper::make(null, 'dev: id not found', false);
        }
        if ($order->status == null) {
            $order->status = 'approved';
            $body = [
                "ar" => 'تمت الموافقة على طلبك',
                "en" => 'Your order has been approved',
            ];
            $body_ar = 'تمت الموافقة على طلبك';
            $body_en = 'Your order has been approved';
        } elseif ($order->status == 'approved') {
            $order->status = 'onway';
            $body = [
                "ar" => 'طلبك في الطريق اليك',
                "en" => 'Your order is on way',
            ];
            $body_ar = 'طلبك في الطريق اليك';
            $body_en = 'Your order is on way';

        } elseif ($order->status == 'onway') {
            $order->status = 'processing';
            $body = [
                "ar" => 'يتم العمل على طلبك',
                "en" => 'Your order is under processing',
            ];
            $body_ar = 'يتم العمل على طلبك';
            $body_en = 'Your order is under processing';

        } elseif ($order->status == 'processing') {
            $order->status = 'done';
            $body = [
                "ar" => 'تم الانتهاء من الخدمة',
                "en" => 'Your order has been completed',
            ];
            $body_ar = 'تم الانتهاء من الخدمة';
            $body_en = 'Your order has been completed';
        } elseif ($order->status == 'done') {
            return ResponseHelper::make(null, 'dev: order in last status!', false);
        }
        $order->save();

        $noty = new UserNotification();
        $noty->user_id = $order->user_id;
        $noty->order_id = $order->id;
        $noty->body_ar = $body_ar;
        $noty->body_en = $body_en;
        $noty->save();
        PushNotification::send($body, $order->id, auth('sanctum')->id(), 'client');

        $order = OldOrdersResource::make($order);

        return ResponseHelper::make($order, __('dash.alert_update'));
    }

    public function current_user_orders()
    {
        $orders = Order::with('user')

            ->when(auth('sanctum')->check(), function ($query) {
                return $query->where('user_id', auth('sanctum')->id());
            })
            ->when(request('company_id'), function ($query) {
                return $query->where('company_id', request('company_id'));
            })
            ->where(function ($q) {
                $q->where('status', '!=', 'done')
                    ->OrwhereNull('status');
            })->get();

        $data = [
            'orders' => OldOrdersResource::collection($orders)
        ];
        return ResponseHelper::make($data);
    }

    public function previous_user_orders()
    {
        $orders = Order::with('user')
            ->when(auth('sanctum')->check(), function ($query) {
                return $query->where('user_id', auth('sanctum')->id());
            })
            ->when(request('company_id'), function ($query) {
                return $query->where('company_id', request('company_id'));
            })
            ->where('status', 'done')
            ->get();

        $data = [
            'orders' => OldOrdersResource::collection($orders)
        ];
        return ResponseHelper::make($data);
    }

    public function order_details($order_id)
    {
        $order = Order::where('id', $order_id)->with('payment_method', 'company')->first();
        if (!$order) {
            return ResponseHelper::make(null, 'dev: id not found', false);
        }
        $address = Address::query()
            ->when(auth('sanctum')->check(), function ($query) use ($order) {
                return $query->where('user_id', $order->user_id);
            })->where('is_active', true)->first();

        $vat = setting('vat');
        $data = [
            'order_id' => $order->id,
            'date' => Carbon::parse($order->date)->format('d F Y'),
            'time' => Carbon::parse($order->time)->format('H:i a'),
            'company_phone' => $order->company->phone,
            'status' => $order->status ? __('dash.' . $order->status) : null,
            'company_title' => $order->company['title_' . lang()],
            'address' => $address ? AddressResource::make($address) : null,
            'sub_total' => $order->sub_total,
            'vat' => $vat ?? 0,
            'vat_ammount' => $order->vat_cost,
            'total_after_vat' => costformat($order->net_total),
            'payment_method_title' => $order->payment_method['name_' . lang()],
            'payment_method_image' => $order->payment_method['image'],
            'services' => OrderDetailsResource::collection($order->order_details),
        ];

        return ResponseHelper::make($data);

    }



}
