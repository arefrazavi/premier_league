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
*/

// GET requests
Route::get('/', 'TeamController@viewList');

// POST requests
Route::post('/play-week', 'MatchController@playWeek');
Route::post('/play-all', 'MatchController@playAllWeeks');
Route::post('/predict-champion', 'TeamController@predictChampion');
