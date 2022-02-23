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

    /**PRODUCT MODULE */
    Route::get('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('product');
    Route::post('/add_product', [App\Http\Controllers\ProductController::class, 'store'])->name('add_product');
    Route::get('/product/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('edit_product');
    Route::post('/update_product', [App\Http\Controllers\ProductController::class, 'update'])->name('update_product');
    Route::get('/product/delete', [App\Http\Controllers\ProductController::class, 'destroy'])->name('delete_product');
    /**END PRODUCT MODULE */
    /**GRN MODULE */
    Route::get('/grn', [App\Http\Controllers\GrnController::class, 'index'])->name('grn');
    Route::get('/grn/product', [App\Http\Controllers\GrnController::class, 'indget_productex']);
    Route::get('/grn/products', [App\Http\Controllers\GrnController::class, 'get_products']);
    Route::get('/grn/price', [App\Http\Controllers\GrnController::class, 'search_product_price']);
    Route::post('/add_grn', [App\Http\Controllers\GrnController::class, 'store'])->name('add_grn');

    Route::get('view_grn', [App\Http\Controllers\GrnController::class, 'view_grn']);
    Route::get('/grn/search', [App\Http\Controllers\GrnController::class, 'search']);
    Route::get('/display_grn/{id}', [App\Http\Controllers\GrnController::class, 'show']);
    /**END GRN MODULE */
    /**INVOICE MODULE */
    Route::get('test_print', [App\Http\Controllers\InvoiceController::class, 'test_print'])->name('test_print');
    Route::get('invoice', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoice');
    Route::get('/invoice/product', [App\Http\Controllers\InvoiceController::class, 'search_product']);
    Route::get('/invoice/price', [App\Http\Controllers\InvoiceController::class, 'search_product_price']);
    Route::get('/invoice/stock', [App\Http\Controllers\InvoiceController::class, 'search_product_stock']);
    Route::post('add_invoice', [App\Http\Controllers\InvoiceController::class, 'store']);

    Route::get('view_invoice', [App\Http\Controllers\InvoiceController::class, 'view_invoice']);
    Route::get('/invoice/search', [App\Http\Controllers\InvoiceController::class, 'search']);
    /**END INVOICE MODULE */
});
