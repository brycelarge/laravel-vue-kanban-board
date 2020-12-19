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

Route::view('/', 'app');

Route::get('columns', \App\Http\Controllers\ApiController::class . '@index')->name('columns.index');
Route::post('columns/persist', \App\Http\Controllers\ApiController::class . '@persist')->name('columns.persist');
Route::get('db/dump', \App\Http\Controllers\ApiController::class . '@dumpDb')->name('db.dump');
