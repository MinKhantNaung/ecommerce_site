<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\VendorController;

/** Vendor Routes */
Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
