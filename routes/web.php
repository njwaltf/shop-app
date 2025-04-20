<?php

use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\CashierDashboard;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DontHaveAccess;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TransactionController;
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
Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'auth']);
    Route::get('/register', [RegisterController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    // admin
    Route::get('/dashboard-admin', [AdminDashboard::class, 'index'])->middleware('userAccess:admin');
    // product
    Route::get('/product-management', [ProductController::class, 'index'])->middleware('userAccess:admin')->name('admin_product');
    Route::get('/product-management/create', [ProductController::class, 'create'])->middleware('userAccess:admin')->name('admin_product_create');
    Route::post('/product-management/create', [ProductController::class, 'store'])->middleware('userAccess:admin')->name('admin_product_store');
    Route::get('/product-management/{id}', [ProductController::class, 'show'])->middleware('userAccess:admin');
    Route::post('/product-management-delete/{id}', [ProductController::class, 'destroy'])->middleware('userAccess:admin');
    Route::get('/product-management/{product}/edit', [ProductController::class, 'edit'])->middleware('userAccess:admin');
    Route::post('/product-management/{id}/update', [ProductController::class, 'update'])->middleware('userAccess:admin');
    Route::get('/product/report', [ProductController::class, 'report'])->name('admin_product_report');
    Route::get('/product-report-pdf', [ProductController::class, 'reportPdf'])->name('product.report.pdf');


    // category
    Route::resource('/category', CategoryController::class)->middleware('userAccess:admin');


    // admin transaction cashier and admin
    Route::get('/transaction-management', [TransactionController::class, 'index'])->name('admin_transaction');
    Route::get('/transaction-management/report', [TransactionController::class, 'report'])->name('transaction.report');
    Route::get('/transaction-report-pdf', [TransactionController::class, 'reportPdf'])->name('transaction.report.pdf');
    Route::get('/transaction/{id}', [TransactionController::class, 'show'])->name('transaction.show');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transaction.create');
    Route::get('/transaction/{transaction}/print', [TransactionController::class, 'print'])->name('admin_transaction.print');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transaction.store');
    Route::get('/products/search', [ProductController::class, 'search']); // Untuk AJAX pencarian produk


    // kasir
    Route::get('/dashboard-cashier', [CashierDashboard::class, 'index'])->middleware('userAccess:cashier')->name('dashboard.cashier');
});

Route::get('/home', function () {
    return redirect('/');
});
// you dont have access
Route::get('/you-dont-have-access', [DontHaveAccess::class, 'index'])->name('you-dont-have-access');
