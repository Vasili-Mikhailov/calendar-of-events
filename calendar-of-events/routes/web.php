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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('events', 'EventController');

Route::post('events/create/form', 'EventController@createCheckFirstPart')
->name('events.CheckFirstPart');

Route::get('events/create/form', 'EventController@createGetSecondPart')
->name('events.GetSecondPart');
