<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {return inertia('Index'); });

Route::middleware(['auth'])->group(function () {
    Route::resource('/home', App\Http\Controllers\HomeController::class);

    Route::prefix('staffs')->group(function(){
        Route::resource('/lists', App\Http\Controllers\Staff\ListController::class);
        Route::resource('/roles', App\Http\Controllers\Staff\RoleController::class);
    });
});

require __DIR__.'/authentication.php';
require __DIR__.'/installation.php';
require __DIR__.'/lists.php';
