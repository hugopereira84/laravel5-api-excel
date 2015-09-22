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


Route::group(array('prefix' => 'api/'), function()
{
    Route::get('recipe/{id}', 'RecipeController@show');
    Route::any('recipe', 'RecipeController@storeInfo');
    //Route::resource('recipe', 'RecipeController');

    /*Route::put('foo/bar', function () {
        //
    });

    Route::delete('foo/bar', function () {
        //
    });*/
    //Route::resource('recipe', 'RecipeController');

});