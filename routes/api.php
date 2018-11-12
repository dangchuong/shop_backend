<?php

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function(){
	Route::post('register', 'AuthController@register');
	Route::post('login', 'AuthController@login');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});