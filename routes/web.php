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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function(){
    dd("ใในใ");
});
Route::get('/medicine', 'App\Http\Controllers\MedicineController@index');
Route::post('/medicine/extract', 'App\Http\Controllers\MedicineController@extract');
// Route::get('/search', 'App\Http\Controllers\SearchController@getRakutenItems');
