<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('migrate', function () {
    \Artisan::call('migrate --force');
    //    \Artisan::call('db:seed');
    // \Artisan::call('storage:link');
    // \Artisan::call('clear-compiled');
    echo 'success';

});

Route::get('/', [AdminController::class, 'index'])->middleware(['auth'])->name('dashboard');

Auth::routes(['register' => false]);
