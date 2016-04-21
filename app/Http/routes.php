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
    return "I'm still here!";
});

$app->group([
    'prefix' => '/gitlab',
    'namespace' => 'App\Http\Controllers'
], function () use ($app) {
    $app->bind(\App\VCS\PublishRequestInterface::class, \App\VCS\GitLab\PublishRequest::class);

    $app->post('/publish', 'PublishController@publish');
});