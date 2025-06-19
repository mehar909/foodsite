<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('home');
// });
Route::view('/', 'home');
Route::view('/login', 'login');
Route::view('/register', 'register');
Route::view('/menu', 'menu');
Route::view('/orders', 'orders');
Route::view('/admin', 'admin.login');
Route::view('/admin/menu', 'admin.menu');
Route::view('/admin/orders', 'admin.orders');
