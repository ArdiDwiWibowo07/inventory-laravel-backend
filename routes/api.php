<?php

use Illuminate\Support\Facades\Route;


//route login
Route::post('/login', [App\Http\Controllers\Api\Auth\LoginController::class, 'index']);

//group route with middleware "auth"
Route::group(['middleware' => 'auth:api'], function() {

    //logout
    Route::post('/logout', [App\Http\Controllers\Api\Auth\LoginController::class, 'logout']);
    
});

//group route with prefix "admin"
Route::prefix('admin')->group(function () {
    //group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function () {
        
        //dashboard
        Route::get('/dashboard', App\Http\Controllers\Api\Admin\DashboardController::class);
        
        //permissions
        Route::get('/permissions', [\App\Http\Controllers\Api\Admin\PermissionController::class, 'index'])
        ->middleware('permission:permissions.index');

        //permissions all
        Route::get('/permissions/all', [\App\Http\Controllers\Api\Admin\PermissionController::class, 'all'])
        ->middleware('permission:permissions.index');

        //roles all
        Route::get('/roles/all', [\App\Http\Controllers\Api\Admin\RoleController::class, 'all'])
        ->middleware('permission:roles.index');

        //roles
        Route::apiResource('/roles', App\Http\Controllers\Api\Admin\RoleController::class)
        ->middleware('permission:roles.index|roles.store|roles.update|roles.delete');

        //users
        Route::apiResource('/users', App\Http\Controllers\Api\Admin\UserController::class)
        ->middleware('permission:users.index|users.store|users.update|users.delete');

        //categories all
        Route::get('/categories/all', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'all'])
        ->middleware('permission:categories.index');

        //Categories
        Route::apiResource('/categories', App\Http\Controllers\Api\Admin\CategoryController::class)
        ->middleware('permission:categories.index|categories.store|categories.update|categories.delete');
     
        //Products
        Route::apiResource('/products', App\Http\Controllers\Api\Admin\ProductController::class)
        ->middleware('permission:products.index|products.store|products.update|products.delete');
      
        //Suplliers
        Route::apiResource('/suppliers', App\Http\Controllers\Api\Admin\SupplierController::class)
        ->middleware('permission:suppliers.index|suppliers.store|suppliers.update|suppliers.delete');

        //Stock
        Route::get('/stocks', [\App\Http\Controllers\Api\Admin\StockController::class, 'index']);
        Route::post('/stocks/in/{product}', [\App\Http\Controllers\Api\Admin\StockController::class, 'stockIn'])
        ->middleware('permission:stock.in');
    
        Route::post('/stocks/out/{product}', [\App\Http\Controllers\Api\Admin\StockController::class, 'stockOut'])
        ->middleware('permission:stock.out');

        Route::get('/stocks/out/{product}', [\App\Http\Controllers\ReportController::class, 'download'])
        ->middleware('permission:stock.out');

        Route::controller(\App\Http\Controllers\ReportController::class)->as('reports.')->prefix('reports')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/download/{type}/{from_date}/{to_date}', 'download')->name('download');
        });
    });
});