<?php
use App\Http\Controllers\Tfa7\CustomerController;
use App\Http\Controllers\Tfa7\UserController;
use Illuminate\Support\Facades\Route;

// Loaded from web.php, so sessions, validation errors and CSRF protection apply.
// Put its require statement inside your existing staff/admin middleware group
// when integrating with a login-protected POS.
Route::name('tfa7.')->group(function () {
    Route::resource('customers', CustomerController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
});
