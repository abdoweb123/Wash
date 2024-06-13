<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyService extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function favorate()
    {
        return $this->hasOne(Favorate::class);
    }

    public function favorates()
    {
        return $this->belongsToMany(User::class, 'favorates', 'company_service_id');
    }

    // public function authFav()
    // {
    //     return $this->hasOne(Favorate::class)->where('user_id', auth('sanctum')->id() ?? null);
    // }
}
