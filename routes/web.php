<?php

use App\Http\Controllers\Admin\CashSessionController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', fn () => view('welcome'));
Route::redirect('/dashboard', '/admin');

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'view')->name('login')->middleware('guest');
    Route::post('/login', 'login')->name('login.process')->middleware('guest');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        $role = strtolower((string) Auth::user()->role);

        return $role === 'kasir'
            ? redirect()->route('admin.pos.index')
            : redirect()->route('admin.cash-sessions.index');
    })->name('home');

   
    Route::middleware(['role:kasir'])->group(function () {
        Route::controller(CashSessionController::class)->prefix('cash-sessions')->name('cash-sessions.')->group(function () {
            Route::get('/open', 'openForm')->name('open-form');
            Route::post('/open', 'open')->name('open');
            Route::get('/{cashSession}/close', 'closeForm')->whereNumber('cashSession')->name('close-form');
            Route::post('/{cashSession}/close', 'close')->whereNumber('cashSession')->name('close');
        });

        Route::controller(PosController::class)->prefix('pos')->name('pos.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/check-member', 'checkMember')->name('check-member');
            Route::post('/', 'store')->name('store');
            Route::get('/receipt/{sale}', 'receipt')->name('receipt');
        });
    });

  
    Route::middleware(['role:admin'])->group(function () {
    
        Route::get('/dashboard/export', 'App\\Http\\Controllers\\Admin\\DashboardController@export')->name('dashboard.export');

        Route::controller(CashSessionController::class)->prefix('cash-sessions')->name('cash-sessions.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::delete('/{cashSession}', 'destroy')->whereNumber('cashSession')->name('destroy');
        });

        Route::resource('products', ProductController::class)->except(['show']);
        Route::patch('products/{product}/activate', [ProductController::class, 'activate'])->name('products.activate');
        Route::patch('products/{product}/deactivate', [ProductController::class, 'deactivate'])->name('products.deactivate');
        Route::get('products/{product}/restock', [ProductController::class, 'restockForm'])->name('products.restock.form');
        Route::post('products/{product}/restock', [ProductController::class, 'restockProcess'])->name('products.restock.process');

        Route::resource('suppliers', SupplierController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::patch('suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');

        Route::patch('employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
        Route::resource('employees', EmployeeController::class);
    });

    
    Route::middleware(['role:admin,kasir'])->controller(CashSessionController::class)
        ->prefix('cash-sessions')->name('cash-sessions.')
        ->group(function () {
            Route::get('/{cashSession}', 'show')->whereNumber('cashSession')->name('show');
        });
});