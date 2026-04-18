<?php

declare(strict_types=1);

use App\Core\Router;

/**
 * Web Routes - Define all application routes here
 */

return function (Router $router) {
    // Home routes
    $router->get('/', 'HomeController@index');
    $router->get('/about', 'HomeController@about');

    // Post routes
    $router->get('/posts', 'PostController@index');
    $router->get('/posts/{slug}', 'PostController@show');
    $router->post('/posts', 'PostController@create');
    $router->post('/posts/{id}', 'PostController@update');
    $router->post('/posts/{id}/delete', 'PostController@delete');
    $router->get('/api/posts/{id}', 'PostController@getById');

    // Category routes
    $router->get('/categories', 'CategoryController@index');
    $router->get('/categories/{slug}', 'CategoryController@show');
    $router->post('/categories', 'CategoryController@create');
    $router->post('/categories/{id}', 'CategoryController@update');
    $router->post('/categories/{id}/delete', 'CategoryController@delete');

    $router->get('/test', 'TestController@index');
};
