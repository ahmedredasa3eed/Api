<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\User\AuthUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware'=>['api','checkApiPassword','changeApiLang'],'namespace'=>'Api',],function (){
   Route::post('/getCategories', [CategoriesController::class, 'getCategories']);
   Route::post('/getCategoryById', [CategoriesController::class, 'getCategoryById']);
   Route::post('/changeCategoryStatus', [CategoriesController::class, 'changeCategoryStatus']);

    Route::group(['namespace'=>'Api\Admin','prefix'=>'admin'],function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('checkJwtApiToken:admin_api');
        Route::post('/profile', [AuthController::class, 'adminProfile'])->middleware('checkJwtApiToken:admin_api');
    });

    Route::group(['namespace'=>'Api\User','prefix'=>'user'],function () {
        Route::post('/login', [AuthUserController::class, 'login']);
        Route::post('/register', [AuthUserController::class, 'register']);
        Route::post('/profile', [AuthUserController::class, 'userProfile'])->middleware('checkJwtApiToken:user_api');
    });
});
