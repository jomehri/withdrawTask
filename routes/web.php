<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tax\TaxController;

/**
 * Tax Calculation Routes
 */
Route::group(['prefix' => '/'], function() {

	Route::get('/', [TaxController::class, 'item'])->name('tax.item');
	Route::post('/store', [TaxController::class, 'store'])->name('tax.store');

});
