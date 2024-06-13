<?php

namespace App\Http\Controllers\Api;

use App\Functions\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\CompanyService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResultResource;
use App\Models\Company;
use App\Models\Service;

class SearchController extends Controller
{
    public function index(SearchRequest $request)
    {
        $results = CompanyService::addSelect([
            'avg_rating' => Company::select('avg_rating')->whereColumn('id', 'company_services.company_id')->limit(1)
            ])->whereHas('service', function($q) use ($request){
                $q->where('title_ar', 'like', '%' . $request->word . '%')->orWhere('title_en', 'like', '%' . $request->word . '%');
            });
    
        if ($request->sort_by == 'review') {
            $results = $results->orderBy('avg_rating', 'desc');
        } elseif ($request->sort_by == 'highest_price') {
            $results = $results->orderBy('price', 'desc');
        } elseif ($request->sort_by == 'lowest_price') {
            $results = $results->orderBy('price', 'asc');
        }

        $results = $results->get();
    
        $data = [
            'quantity_of_results' => $results->count(),
            'company_services' => SearchResultResource::collection($results)
        ];

        return ResponseHelper::make($data);
    }
}
