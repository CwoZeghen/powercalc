<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\AddonsController;
use App\Http\Controllers\API\DataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('new-request', [DataController::class, 'new_request'])->name('new_request');
Route::post('not-listed', [DataController::class, 'not_listed'])->name('not_listed');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('save-appliance', [DataController::class, 'save_appliance'])->name('data.appliance.save');
    Route::post('update-appliance', [DataController::class, 'update_appliance'])->name('data.appliance.update');
    Route::post('save-appliance2', [DataController::class, 'save_appliance2'])->name('data.appliance.save2');
    Route::post('delete-appliance', [DataController::class, 'delete_appliance'])->name('data.appliance.delete');
    Route::post('contact-update', [DataController::class, 'contact_update'])->name('data.contact.update');
    Route::post('save-cost', [DataController::class, 'save_cost'])->name('data.cost.save');

    Route::resource('addons', AddonsController::class);
});
