<?php

use App\Http\Controllers\Controller;
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



Route::get('/',function () {
    return view('Home');
})->name('Home');

Route::get('/about_us',function () {
    return view('About_us');
})->name('about_us');

Route::get('/treatments',function () {
    return view('Treatments');
})->name('treatments');

Route::get('/blogs',function () {
    return view('Blogs');
})->name('blogs');

Route::get('/blog_expansion',function () {
    return view('Blogs_enlargement');
})->name('blog_expansion');

Route::get('/products',function () {
    return view('Products');
})->name('products');

Route::get('/products_expansion',function () {
    return view('Products_enlargement');
})->name('products_expansion');

Route::get('/contact',function () {
    return view('Contact');
})->name('contact');
