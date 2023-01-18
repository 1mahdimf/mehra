<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/import',[\App\Http\Controllers\Api\Product\ProductController::class,'importJson']);
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('user-datatables', function () {
    return view('admin.users.index');
});
