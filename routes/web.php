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

/*
Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('welcome');
});*/

//Route::get('/', 'productoController@index');
// Rutas que incluye todos los métodos HTTP
Route::resource('/', 'productoController');
Route::resource('/registro', 'productoController');
Route::resource('/editar', 'productoController');
Route::resource('/editar/{id}', 'productoController');

//Route::resource('/', 'productoController');