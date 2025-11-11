<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Dashboard routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/import/{pms}', [DashboardController::class, 'importPmsData'])->name('dashboard.import');
    Route::get('/dashboard/import-status/{pms}', [DashboardController::class, 'importStatus'])->name('dashboard.import-status');
    Route::post('/dashboard/transform', [DashboardController::class, 'transformPmsData'])->name('dashboard.transform');
    
    // CRUD operations
    Route::resource('hotels', App\Http\Controllers\HotelController::class);
    Route::resource('room-types', App\Http\Controllers\RoomTypeController::class);
    Route::resource('rooms', App\Http\Controllers\RoomController::class);
    Route::resource('room-attributes', App\Http\Controllers\RoomAttributeController::class);
    
    // Apaleo CRUD operations
    Route::resource('apaleo-properties', App\Http\Controllers\ApaleoPropertiesController::class);
    Route::resource('apaleo-unit-groups', App\Http\Controllers\ApaleoUnitGroupsController::class);
    Route::resource('apaleo-units', App\Http\Controllers\ApaleoUnitsController::class);
    Route::resource('apaleo-unit-attributes', App\Http\Controllers\ApaleoUnitAttributesController::class);
    Route::resource('services', App\Http\Controllers\ServiceController::class);
    
    // AJAX route for getting unit groups by property
    Route::get('/api/unit-groups-by-property', [App\Http\Controllers\ApaleoUnitsController::class, 'getUnitGroupsByProperty'])
        ->name('api.unit-groups-by-property');
    
    // Mews CRUD routes
    Route::resource('mews-enterprises', App\Http\Controllers\MewsEnterpriseController::class);
    Route::resource('mews-services', App\Http\Controllers\MewsServicesController::class);
    Route::resource('mews-resource-categories', App\Http\Controllers\MewsResourceCategoriesController::class);
    Route::resource('mews-resources', App\Http\Controllers\MewsResourcesController::class);
    Route::resource('mews-resource-features', App\Http\Controllers\MewsResourceFeaturesController::class);
    
    // Placeholder routes for other CRUD operations - to be implemented
    Route::get('/buildings', function() { return 'Buildings CRUD - Coming soon'; })->name('buildings.index');
    Route::get('/floors', function() { return 'Floors CRUD - Coming soon'; })->name('floors.index');
    Route::get('/sunbed-areas', function() { return 'Sunbed Areas CRUD - Coming soon'; })->name('sunbed-areas.index');
    Route::get('/sunbeds', function() { return 'Sunbeds CRUD - Coming soon'; })->name('sunbeds.index');
});

Route::get('/cron', function () {
    Artisan::call('schedule:run');
    return response('Scheduler executed', 200);
})->middleware('throttle:60,1'); // Limit to 60 requests per minute
