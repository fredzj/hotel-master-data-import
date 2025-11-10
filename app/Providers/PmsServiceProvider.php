<?php

namespace App\Providers;

use App\Contracts\PmsAdapterInterface;
use App\Services\PmsAdapters\ApaleoAdapter;
use App\Services\PmsAdapters\MewsAdapter;
use Illuminate\Support\ServiceProvider;

class PmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register PMS Adapters
        $this->app->bind('pms.apaleo', ApaleoAdapter::class);
        $this->app->bind('pms.mews', MewsAdapter::class);
        
        // Add more PMS adapters here in the future
        // $this->app->bind('pms.pms2', Pms2Adapter::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Get available PMS adapters
     */
    public static function getAvailablePmsAdapters(): array
    {
        return [
            'apaleo' => [
                'name' => 'Apaleo',
                'class' => ApaleoAdapter::class,
                'enabled' => !empty(config('services.apaleo.client_id')),
            ],
            'mews' => [
                'name' => 'Mews',
                'class' => MewsAdapter::class,
                'enabled' => !empty(config('services.mews.client_token')),
            ],
            // Add more PMS systems here
            // 'pms2' => [
            //     'name' => 'PMS2',
            //     'class' => Pms2Adapter::class,
            //     'enabled' => !empty(config('services.pms2.client_id')),
            // ],
        ];
    }
}
