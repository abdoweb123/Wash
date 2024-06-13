<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductShowResource;
use App\Http\Resources\ProductsResource;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if($request->product_type_ids){
            $query->whereIn('product_type_id', $request->product_type_ids);
        }
        $products = $query->get();
        $data = [
            'products' => ProductsResource::collection($products),
        ];
        return ResponseHelper::make($data);
    }

    public function show($product_id)
    {
        $product = Product::where('id', $product_id)->with('images')->first();
        $similar = Product::where('id', '!=', $product->id)
            ->where('product_type_id', $product->product_type_id)
            ->take(3)->get();
            
        $data = [
            'product' => ProductShowResource::make($product),
            'similar_products' => ProductsResource::collection($similar)
        ];

        return ResponseHelper::make($data);
    }
}
