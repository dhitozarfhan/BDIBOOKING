<?php

namespace App\Providers;

use App\Enums\NavigationType;
use App\Models\Navigation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['layouts.partials.header'], function ($view) {
            $view->with('headers', Navigation::scoped(['navigation_type_id' => NavigationType::Header->value])
            ->where('is_active', true)
            ->defaultOrder()
            ->get()->toTree());
        });

        View::composer(['layouts.partials.footer'], function ($view) {
            $view->with('footers', Navigation::scoped(['navigation_type_id' => NavigationType::Footer->value])
            ->where('is_active', true)
            ->defaultOrder()
            ->withDepth()
            ->get()->toTree());
        });
    }
}
