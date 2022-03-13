<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\RoleAndPermissionHelper;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Access by all before login
Route::group(['prefix' => 'v1', 'middleware' => []], function () {
    Route::post('/login', 'App\Http\Controllers\ApiAuthController@login')->name('login.api');
});

Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum', 'ability:' . RoleAndPermissionHelper::ACCESS_ALL_USER_API_ABILITY]], function () {
    Route::get('users', 'App\Http\Controllers\UserController@index');
    Route::get('users/{user_id}', 'App\Http\Controllers\UserController@show');
    Route::post('users', 'App\Http\Controllers\UserController@store');
    Route::put('users/{user_id}', 'App\Http\Controllers\UserController@update');
    Route::patch('users/{user_id}', 'App\Http\Controllers\UserController@patch');
    Route::delete('users/{user_id}', 'App\Http\Controllers\UserController@destroy');
});

//access by all after login
Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum']], function () {
    Route::get('tasks', 'App\Http\Controllers\TaskController@index');
    Route::get('tasks/{task_id}', 'App\Http\Controllers\TaskController@show');
    Route::get('projects', 'App\Http\Controllers\ProjectController@index');
    Route::get('projects/{project_id}', 'App\Http\Controllers\ProjectController@show');
    Route::post('/logout', 'App\Http\Controllers\ApiAuthController@logout')->name('logout.api');
});
//access by all product owners after login 
Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum', 'ability:' . RoleAndPermissionHelper::ACCESS_ALL_TASK_API_ABILITY]], function () {
    Route::post('tasks', 'App\Http\Controllers\TaskController@store');
    Route::delete('tasks/{task_id}', 'App\Http\Controllers\TaskController@destroy');
});

Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum', 'ability:' . RoleAndPermissionHelper::ACCESS_ALL_PROJECT_API_ABILITY]], function () {
    Route::post('projects', 'App\Http\Controllers\ProjectController@store');
    Route::put('projects/{project_id}', 'App\Http\Controllers\ProjectController@update');
    Route::delete('projects/{project_id}', 'App\Http\Controllers\ProjectController@destroy');
    Route::patch('projects/{project_id}', 'App\Http\Controllers\ProjectController@patch');
});


//access by all product owners & team member after login
Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum', 'ability:' . RoleAndPermissionHelper::ACCESS_ALL_TASK_API_ABILITY . ',' . RoleAndPermissionHelper::PARTIAL_ACCESS_TASK_API_ABILITY]], function () {
    Route::patch('tasks/{task_id}', 'App\Http\Controllers\TaskController@patch');
    Route::put('tasks/{task_id}', 'App\Http\Controllers\TaskController@update');
});
