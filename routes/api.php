<?php
use Illuminate\Support\Facades\Route;
use Kalimeromk\Crm\Http\Controllers\SoapController;

Route::prefix('api/v1')->middleware('api')->group(function () {
    Route::post('/leoss-current-view', [SoapController::class, 'LEOSSCurrentView']);
    Route::post('/aa-listing', [SoapController::class, 'AAListing']);
});