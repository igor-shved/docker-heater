<?php

use App\Http\Controllers\api\DataFilesDebugController;
use App\Http\Controllers\MainPageController;
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

//Route::get('/', function () { return view('welcome'); });
//Route::get('{any}', function () { return view('index'); })->where('any', '.*');
Route::get('/', function () {
    return view('index');
});
Route::get('/debug', function () {
    return view('debug');
});



Route::match(['get', 'post'], '/test', [DataFilesDebugController::class, 'getDataFiles']);

//Auth::routes();


