<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PoleWeightController;
use App\Http\Controllers\PurchasePriceController;
use App\Http\Controllers\SellingPriceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContractController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    Route::resource('categories', CategoryController::class);
});

Route::get('customers/search',[CustomerController::class, 'search']);
Route::get('customers',[CustomerController::class]);

Route::resource('categories', CategoryController::class);

Route::get('products/search',[ProductController::class, 'search']);
Route::resource('products', ProductController::class);

Route::get('selling_prices/search',[SellingPriceController::class, 'search']);
Route::resource('selling_prices', SellingPriceController::class);

Route::get('contracts/search',[ContractController::class, 'search']);
Route::resource('contracts', ContractController::class);

Route::resource('pole_weights', PoleWeightController::class);
Route::resource('purchase_prices', PurchasePriceController::class);
Route::resource('selling_prices', SellingPriceController::class);
