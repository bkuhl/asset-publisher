<?php

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

$app->get('/', function () use ($app) {
    return response('');
});

$app->group([
    'namespace' => 'App\Http\Controllers'
], function () use ($app) {
    $app->post('/webhook/{token}', [
        'middleware' => 'validateToken',
        'uses' => 'RepositoryEventController@event'
    ]);
});

$app->get('/healthcheck', function () use ($app) {
    return response('');
});