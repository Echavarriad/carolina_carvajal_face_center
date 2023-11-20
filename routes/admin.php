<?php
/*
|--------------------------------------------------------------------------
| Panel Administrativo
|--------------------------------------------------------------------------
 */
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\ArticleController;


Route::get('/', [AdminLoginController::class , 'showLoginForm'])->name('admin.login');
Route::match(['get','post'],'/forgot', [AdminLoginController::class ,'forgot'])->name('admin.forgot');
Route::post('/', [AdminLoginController:: class, 'login'])->name('admin.login.submit');
Route::get('/logout', [AdminLoginController::class ,'logout'])->name('admin.logout');
Route::get('/reset-password/{token}', [AdminLoginController::class, 'reset'])->name('forgot.admin.reset');
Route::post('/reset-password', [AdminLoginController::class,'reset'])->name('forgot.admin.post');



Route::get('/inicio', [HomeController::class ,'index'])->name('home.index');
Route::resource('/sections',SectionController::class);
Route::resource('sliders', SliderController::class);
Route::resource('sections', SectionController::class);
Route::resource('contents', ContentController::class);
Route::resource('admins', AdminController::class);
Route::resource('articles', ArticleController::class);
Route::resource('users', UserController::class);


Route::resource('products', ProductController::class);
Route::resource('status', StatusController::class);
Route::resource('shippings', ShippingController::class);
Route::get('orders', [OrderController::class ,'index'])->name('order.index');
Route::get('show/{id}', [OrderController::class , 'show'])->name('order.show');
Route::get('order-sales', [OrderController::class, 'index_sales'])->name('order.index_sales');
Route::get('order-download/{id}', [OrderController::class ,'download'])->name('order.download');
Route::post('export-orders', [OrderController::class, 'export'])->name('order.export');
