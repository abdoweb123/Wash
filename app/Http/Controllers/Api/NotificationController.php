<?php

namespace App\Http\Controllers\Api;

use App\Functions\Notification;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\UserNotification;
use App\Models\AdminNotification;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        
        $notifications = UserNotification::where('user_id', auth('sanctum')->id())->latest()->take(20)->get();

        return ResponseHelper::make(NotificationResource::collection($notifications));
    }
    
    public function indexAdmin()
    {
        $notifications = AdminNotification::where('admin_id', auth('sanctum')->id())->latest()->take(20)->get();

        return ResponseHelper::make(NotificationResource::collection($notifications));
    }
}
