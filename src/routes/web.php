<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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


Route::get('/', [ItemController::class, 'index'])->name('home');

Route::get('/item/{item}', [ItemController::class, 'show']);
Route::post('/items/{item}/favorite', [ItemController::class, 'favorite'])->name('items.favorite');
Route::post('/items/{item}/comment', [ItemController::class, 'comment'])->name('items.comment');

Route::get('/purchase/{item}', [ItemController::class, 'purchaseform'])->name('purchaseform');
Route::post('/purchase/{item}', [ItemController::class, 'purchase']);
Route::get('/success', [ItemController::class, 'success'])->name('purchase.success');
Route::get('/cancel', [ItemController::class, 'cancel'])->name('purchase.cancel');
Route::get('/purchase/address/{item}', [ItemController::class, 'purchaseaddressform']);
Route::post('/purchase/address/{item}', [ItemController::class, 'addressUpdate'])->name('purchase.address.update');

Route::get('/sell', [ItemController::class, 'sellform']);
Route::post('/sell', [ItemController::class, 'sell']);

Route::get('/mypage', [UserController::class, 'mypage']);
Route::get('/mypage/profile', [UserController::class, 'mypage_profileform']);
Route::post('/mypage/profile', [UserController::class, 'mypage_profile']);

Route::get('/register', [AuthController::class, 'registerform']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/email', [AuthController::class, 'emailform'])->name('email');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'certification'])->middleware(['signed'])->name('email.certification');
Route::post('/email/resend', [AuthController::class, 'resend'])->name('email.resend');
Route::get('/login', [AuthController::class, 'loginform'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);