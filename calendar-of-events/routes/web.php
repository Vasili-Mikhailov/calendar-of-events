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

Route::resource('events', 'EventController')->middleware('auth');

Route::post('events/create/form', 'EventController@checkFormFirstStep')
->name('CheckFormFirstStep')->middleware('auth');

Route::get('events/create/form', 'EventController@getFormSecondStep')
->name('GetFormSecondStep')->middleware('auth');

Route::resource('companies', 'CompanyController')->middleware('auth');
