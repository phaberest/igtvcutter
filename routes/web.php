<?php

use Illuminate\Support\Facades\View;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    return View::make('home');
});

$router->get('/download/{id}', ['as' => 'download', function ($id) {
    return response()->download(storage_path("videos/{$id}"));
}]);

$router->post('/get-link', 'InstagramController@getDownloadLink');

$router->post('/cut', 'InstagramController@cut');
