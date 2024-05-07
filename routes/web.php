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

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    echo 'Cache succussfully cleared.';
    exit();
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('city', App\Http\Controllers\CityController::class);

Route::post('/get-city', [App\Http\Controllers\CityController::class, 'get_city']);

Route::match(['get','post'],'/getCity', [App\Http\Controllers\CityController::class, 'getCity']);

Route::match(['get','post'],'/user', [App\Http\Controllers\HomeController::class, 'userView'])->name('userView');
Route::match(['get','post'],'/user/store', [App\Http\Controllers\HomeController::class, 'userUpdate']);
// Route::match(['get','post'],'/updatePasswordView', [App\Http\Controllers\HomeController::class, 'updatePasswordView']);
// Route::match(['get','post'],'password/store', [App\Http\Controllers\HomeController::class, 'updatePassword']);

Route::resource('/world-city', App\Http\Controllers\WorldCityController::class);

//Forgot Password - Email Send
Route::get('forget-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ForgetPassword'])->name('ForgetPasswordGet');
Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ResetPassword'])->name('ResetPasswordGet');
Route::post('ResetPasswordPost', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ResetPasswordStore'])->name('ResetPasswordPost');

Route::post('forget-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'ForgetPasswordStore'])->name('ForgetPasswordPost'); 