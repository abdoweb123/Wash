<?php

namespace App\Http\Middleware;

use App\Functions\ResponseHelper;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? ResponseHelper::make(null, 'Unauthorized user', false, 401) : route('dashboard.login');
    }
}
