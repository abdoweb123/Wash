<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Company;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\CompanyService;
use App\Functions\ResponseHelper;
use App\Http\Requests\CartRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Http\Resources\CartItemsResource;
use App\Http\Resources\OtherServiceResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\ServiceResource;
use App\Models\Address;
use App\Models\PaymentMethod;

class CartController extends Controller
{
    public function getCart()
    {
        $cart_items = Cart::where('user_id', auth('sanctum')->id())
            ->with('company_service', function($q){
                $q->with('service', 'company');
            })->get();
        $address = Address::where('user_id', auth('sanctum')->id())->where('is_active', true)->first();
        $payment_methods = PaymentMethod::query()->where('display',1)->get();
        $total = $this->getCartTotal();
        $vat = (setting('vat') / 100) ?? "0";
        $data = [
            'company_title' => $cart_items->first()?->company_service->company['title_'.lang()],
            'company_image' => asset($cart_items->first()?->company_service->company['logo']),
            'total' => costformat($total),
            'vat' => intval(setting('vat')) ?? 0,
            'vat_ammount' => floatval($total) * floatval($vat),
            'total_after_vat' => costformat(floatval($total) + (floatval($total) * floatval($vat))),
            'address' => $address ? AddressResource::make($address) : null,
            'payment_methods' => PaymentResource::collection($payment_methods),
            'cart_items' => CartItemsResource::collection($cart_items),
        ];

        return ResponseHelper::make($data);
    }

    public function add(CartRequest $request)
    {
        $has_cart = Cart::where('user_id', auth('sanctum')->id())->first();
        if($has_cart){
            $exist_service = CompanyService::where('id', $has_cart->company_service_id)->first();
            $new_service = CompanyService::where('id', $request->company_service_id)->first();

            if($exist_service->company_id != $new_service->company_id){
                return ResponseHelper::make(['type' => 1], __('dash.another_company_in_cart'), false);
            }

            $carts = Cart::where('user_id', auth('sanctum')->id())->get();
            foreach($carts as $cartt){
                if($cartt->company_service_id == $request->company_service_id){
                    return ResponseHelper::make(['type' => 2], __('dash.service_exists'), false);
                    break;
                }
            }
        }

        $cart = new Cart();
        $cart->user_id = auth('sanctum')->id();
        $cart->company_service_id = $request->company_service_id;
        $cart->standard_quantity = $request->standard_quantity;
        $cart->need_materials = $request->need_materials;
        $cart->note = $request->note;
        $cart->save();

        return ResponseHelper::make(null, __('dash.alert_add'));
    }

    public function delete($cart_id)
    {
        $cart = Cart::find($cart_id);
        if(!$cart){
            return ResponseHelper::make(null, 'dev: id not found', false);
        }
        $cart->delete();
        return ResponseHelper::make(null, __('dash.alert_delete'));
    }

    public function delete_all()
    {
        Cart::where('user_id', auth('sanctum')->id())->delete();
        return ResponseHelper::make(null, __('dash.alert_delete'));
    }

    public function other_services()
    {
        $cart = Cart::where('user_id', auth('sanctum')->id());
        $cart_company_services_ids = $cart->pluck('company_service_id');
        $company = $cart->first()->company_service->company;



        $data = [
            'company_title' => $company['title_'.lang()],
            'company_logo' => asset($company->logo),
            'total' => $this->getCartTotal(),
            'services' => OtherServiceResource::collection(CompanyService::where('company_id', $company->id)
                ->whereNotIn('id', $cart_company_services_ids)->get()),
        ];
        return ResponseHelper::make($data);
    }

    public static function getCartTotal()
    {
        $carts = Cart::where('user_id', auth('sanctum')->id())->get();
        $total = 0;
        foreach ($carts as $cart) {
            $price = $cart->company_service->price;
            $materials = $cart->need_materials == true ? $cart->company_service->cleaning_materials_cost : 0;
            $total += ($price + $materials) * $cart->standard_quantity;
        }
        return number_format($total, 2);
    }
}
