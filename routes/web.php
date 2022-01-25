<?php

use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [App\Http\Controllers\HomeController::class, 'login'])->name('login');
Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('product');
    Route::post('/add_product', [App\Http\Controllers\ProductController::class, 'store'])->name('add_product');
    Route::get('/product/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('edit_product');
    Route::post('/update_product', [App\Http\Controllers\ProductController::class, 'update'])->name('update_product');
    Route::get('/product/delete', [App\Http\Controllers\ProductController::class, 'destroy'])->name('delete_product');
});
