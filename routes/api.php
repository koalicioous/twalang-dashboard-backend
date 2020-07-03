<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/locations','LocationController@index');
Route::get('/location/{location}','LocationController@location');

Route::get('/user/count','UserController@count');
Route::get('/user/genderDemographic','UserController@genderDemographic');
Route::get('/user/ageDemographic', 'UserController@ageDemographic');

Route::get('/category','CategoryController@index');
Route::get('/category/{category}','CategoryController@test');
