<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
/* On utilise une route::resource pour lui dire de rechercher toutes les fonctions de CRUD basique create store etc) */
Route::resource('commandes', 'CommandesController');
Route::resource('client', 'ClientController');
Route::auth();
/* Route normales avec GET */
Route::get('dashboard', [
    'middleware' => 'auth',
    'uses' => 'CommandesController@RecupCommandes'
]);
Route::get('compte', [
    'middleware' => 'auth',
    'uses' => 'ClientController@ClientEdit'
]);
Route::get('reservation', [
    'middleware' => 'auth',
    'uses' => 'CommandesController@priseReservation'
]);
Route::get('cloture', [
    'middleware' => 'auth',
    'uses' => 'ClientController@warningSupprClient'
]);
Route::get('supprClient', [
    'middleware' => 'auth',
    'uses' => 'ClientController@supprClient'
]);
Route::get('/home', 'HomeController@index');
