<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminRegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([ 'middleware'=>'api', 'prefix' => 'auth'], function () {
    Route::post('/register/admin',[AdminRegisterController::class,'registerAdmin']);
    Route::post('/login/admin',[AdminRegisterController::class,'loginAdmin']);
});

Route::middleware(['auth:sanctum',])->group(function () {
    Route::get('/profile/Admin',[AdminRegisterController::class, 'profileAdmin']);

    Route::post('/add/Category',[CategoryController::class,'AddCategory']);
    Route::get('/show/Category',[CategoryController::class,'ShowCategory']);
    Route::delete('/delete/Category',[CategoryController::class,'RemoveCategory']);

    Route::post('/add/Product',[ProductController::class,'AddProduct']);
    Route::get('/show/Product',[ProductController::class,'ShowProduct']);
    Route::delete('/delete/Product',[ProductController::class,'RemoveProduct']);
});
