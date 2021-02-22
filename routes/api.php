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


Route::group([
    'namespace' => 'Test',
    'prefix' => 'test',
    'middleware' => 'apiAfter'
], function () {
    Route::any('select', 'IndexController@selectApi');

    Route::any('get', 'IndexController@getApi');

    Route::any('insert', 'IndexController@insertApi');

    Route::any('update', 'IndexController@updateApi');

    Route::any('delete', 'IndexController@deleteApi');

    Route::any('where', 'IndexController@whereApi');

    Route::any('page', 'IndexController@pageApi');

    Route::any('create', 'IndexController@createApi');

    Route::any('get2', 'IndexController@get2Api');

    Route::any('edit', 'IndexController@editApi');

    Route::any('select2', 'IndexController@select2Api');

    Route::any('action', 'IndexController@actionApi');

    Route::any('login', 'IndexController@loginApi');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::any('userinfo', 'IndexController@userInfoApi');
    });

    Route::any('httplogin', 'IndexController@httpLoginApi');

    Route::any('httpadvert', 'IndexController@httpAdvertApi');

    Route::any('httpupload', 'IndexController@httpUploadApi');

    Route::any('httpadvertinsert', 'IndexController@httpAdvertInsertApi');

    Route::any('httpadvertinfo', 'IndexController@httpAdvertInfoApi');

    Route::any('httpadvertupdate', 'IndexController@httpAdvertUpdateApi');

    Route::any('httpadvertdel', 'IndexController@httpAdvertDelApi');

    Route::any('formvalidate', 'IndexController@formValidateApi');

    Route::any('userlogin', 'UserController@loginApi');

    Route::any('userregister', 'UserController@registerApi');

    Route::middleware('auth:sanctum')->any('advertlist', 'UserController@advertListApi');
});
