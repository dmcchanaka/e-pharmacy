<?php

use App\Http\Controllers\Reports\DailySalesSummaryReportController;
use App\Http\Controllers\Reports\DoctorPaymentReportController;
use App\Http\Controllers\Reports\ExpiryProductReportController;
use App\Http\Controllers\Reports\FastMovingItemsReportController;
use App\Http\Controllers\Reports\ProductWiseStockReportController;
use App\Http\Controllers\SendDailySummaryEmailController;
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
    Route::get('print_invoice/{id}', [App\Http\Controllers\InvoiceController::class, 'print_invoice'])->name('print_invoice');

    Route::get('view_invoice', [App\Http\Controllers\InvoiceController::class, 'view_invoice']);
    Route::get('/invoice/search', [App\Http\Controllers\InvoiceController::class, 'search']);
    Route::get('/display_invoice/{id}', [App\Http\Controllers\InvoiceController::class, 'show']);

    Route::get('/invoice/other_fees', [App\Http\Controllers\InvoiceController::class, 'search_other_fee']);
    Route::get('/invoice/other_fees_by_id', [App\Http\Controllers\InvoiceController::class, 'search_other_fee_by_id']);
    /**END INVOICE MODULE */

    /**EXPENSES MODULE */
    Route::get('/expenses', [App\Http\Controllers\ExpensesController::class, 'index'])->name('expenses');
    Route::post('/add_expenses', [App\Http\Controllers\ExpensesController::class, 'store'])->name('add_expenses');
    Route::get('view_expenses', [App\Http\Controllers\ExpensesController::class, 'view_expenses']);
    Route::get('/expenses/search', [App\Http\Controllers\ExpensesController::class, 'search']);
    /**END EXPENSES MODULE */

    /**REPORT MODULE */
    Route::group(['prefix' => 'reports'], function () {
        Route::group(['prefix' => 'stock'], function () {
            Route::get('load', [ProductWiseStockReportController::class, 'index']);
            Route::post('search', [ProductWiseStockReportController::class, 'search']);
        });
        /**EXPIRY STOCK REPORT */
        Route::group(['prefix' => 'expiry-product'], function () {
            Route::get('load', [ExpiryProductReportController::class, 'index']);
            Route::post('search', [ExpiryProductReportController::class, 'search']);
        });
        /**DAILY SALES REPORT */
        Route::group(['prefix' => 'daily-sales-summary'], function () {
            Route::get('load', [DailySalesSummaryReportController::class, 'index']);
            Route::post('search', [DailySalesSummaryReportController::class, 'search']);
        });
        /**DOCTOR PAYMENT REPORT */
        Route::group(['prefix' => 'doctor-payments'], function () {
            Route::get('load', [DoctorPaymentReportController::class, 'index']);
            Route::post('search', [DoctorPaymentReportController::class, 'search']);
        });
        /**FAST MOVING ITEMS REPORT */
        Route::group(['prefix' => 'fast-moving-items'], function () {
            Route::get('load', [FastMovingItemsReportController::class, 'index']);
            Route::post('search', [FastMovingItemsReportController::class, 'search']);
        });
    });
    /**END REPORT MODULE */
});
