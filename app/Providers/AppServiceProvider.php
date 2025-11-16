<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ApaleoProperty;
use App\Models\ApaleoUnitGroup;
use App\Models\ApaleoUnit;
use App\Models\ApaleoUnitAttribute;
use App\Models\MewsEnterprise;
use App\Models\MewsService;
use App\Models\MewsResourceCategory;
use App\Models\MewsResource;
use App\Models\MewsResourceFeature;

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
        // Share PMS data availability flags with all views
        View::composer('*', function ($view) {
            $apaleoHasData = ApaleoProperty::count() > 0 
                || ApaleoUnitGroup::count() > 0 
                || ApaleoUnit::count() > 0 
                || ApaleoUnitAttribute::count() > 0;

            $mewsHasData = MewsEnterprise::count() > 0 
                || MewsService::count() > 0 
                || MewsResourceCategory::count() > 0 
                || MewsResource::count() > 0 
                || MewsResourceFeature::count() > 0;

            $view->with('apaleoHasData', $apaleoHasData);
            $view->with('mewsHasData', $mewsHasData);
        });
    }
}
