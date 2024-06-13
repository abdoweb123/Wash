<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends User
{
    use HasFactory, HasApiTokens;
    protected $guard = "admin";

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
