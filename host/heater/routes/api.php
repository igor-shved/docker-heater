<?php

//namespace \App\Http\Controllers;
use App\Http\Controllers\api\RoomsDataController;
use App\Http\Controllers\api\DataFilesController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/get_rooms_data', [RoomsDataController::class, 'getRoomsData']);
Route::post('/get_data_files', [DataFilesController::class, 'getDataFiles']);
Route::match('get,post', '/save_setting_to_files', [DataFilesController::class, 'saveSettingToFiles']);
//[DataFilesController::class, 'saveSettingToFiles']
//Route::get('/demo', [DemoController::class, 'getData']);
