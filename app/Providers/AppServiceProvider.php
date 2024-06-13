<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::macro('search', function($attributes, $searchTerm) {
            foreach($attributes as $attribute) {
                $this->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
            }
            return $this;
        });
                     
    }
}
