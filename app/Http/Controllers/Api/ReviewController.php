<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Company;

class ReviewController extends Controller
{
    public function index($company_id)
    {
        $company = Company::find($company_id);
        if(!$company){
            return ResponseHelper::make(null, 'dev:company not found', false);
        }
        $check = Review::where('company_id', $company_id)->where('user_id', auth('sanctum')->id())->first();

        $reviews = Review::where('company_id', $company_id);

        $data = [
            'title' => $company['title_'.lang()],
            'image' => asset($company->logo),
            'user_reviewd' => $check ? true : false,
            'reviews_count' => $reviews->count(),
            'reviews_avg' => number_format($reviews->avg('star'), 1),
            'reviews' => ReviewResource::collection($reviews->paginate(20))->response()->getData(true)
        ];

        return ResponseHelper::make($data);
    }

    public function store(ReviewRequest $request)
    {
        $check = Review::where('company_id', $request->company_id)->where('user_id', auth('sanctum')->id())->first();
        if($check){
            return ResponseHelper::make(null, 'dev:user already reviewed', false);
        }
        $review = new Review();
        $review->user_id = auth('sanctum')->id();
        $review->company_id = $request->company_id;
        $review->star = $request->star;
        $review->disc = $request->disc;
        $review->save();

        $company = Company::find($request->company_id);
        $company->avg_rating = $company->reviews->avg('star');
        $company->save();

        return ResponseHelper::make(null, __('dash.alert_add'));
    }

    public function show($company_id)
    {
        $review = Review::where('user_id', auth('sanctum')->id())->where('company_id', $company_id)
            ->first();
        if(!$review){
            return ResponseHelper::make(null, 'dev:no review for this user', false);
        }

        $data = [
            'id' => $review->id,
            'user_name' => $review->user->name,
            'star' => $review->star,
            'disc' => $review->disc,
            'created_at' => Carbon::parse($review->created_at)->diffForHumans(),
        ];

        return ResponseHelper::make($data);
    }

    public function update(Request $request)
    {
        $review = Review::where('user_id', auth('sanctum')->id())->where('id', $request->id)
        ->first();
        if(!$review){
            return ResponseHelper::make(null, 'dev:no review for this user', false);
        }
        $review->star = $request->star;
        $review->disc = $request->disc;
        $review->save();

        $company = Company::find($review->company_id);
        $company->avg_rating = $company->reviews->avg('star');
        $company->save();

        $data = [
            'id' => $review->id,
            'user_name' => $review->user->name,
            'star' => $review->star,
            'disc' => $review->disc,
            'created_at' => Carbon::parse($review->created_at)->diffForHumans(),
        ];

        return ResponseHelper::make($data, __('dash.alert_update'));
    }
}
