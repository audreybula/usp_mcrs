<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| never return a view from your route
| what you should do is create a controller set the route to go
| a controller function  then return the view
|
*/

Route::get('/', 'PagesController@index');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
