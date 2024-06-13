<?php

namespace App\Http\Controllers\Dash;

use App\Models\User;
use App\Models\Image;
use App\Models\Order;
use App\Models\Company;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\Contactus;
use App\Models\CompanyEmail;
use App\Models\Notification;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $company_id = auth('admin')->user()->company_id;
        if($company_id == null){
            $home = [
                'companies' => Company::count(),
                'services' => Service::count(),
                'orders' => Order::count(),
                'users' => User::count(),
                'contacts' => Company::count(),
                'notifications' => Notification::where('company_id', null)->count(),
            ];    
        }else{
            $home = [
                'companies' => Company::count(),
                'services' => Service::count(),
                'orders' => Order::where('company_id', $company_id)->count(),
                'notifications' => Notification::where('company_id', $company_id)->count(),
                'users' => User::count(),
            ];    
        }

        return view('admin.index', compact('home'));
    }

    public function order_show($order_id)
    {
        // dd($order_id);
        $order= Order::where('id', $order_id)->first();
        // $company_id = auth('admin')->user()->company_id;
        // $order = Order::where('id', $order_id)->where(function($q) use ($company_id){
        //     $q->where('company_id',$company_id)
        //     ->orWhere('company_id', null); //admin
        // })->first();
        // if(!$order){
        //     abort(403);
        // }

        return view('admin.show_order', compact('order'));
    }
        
}
