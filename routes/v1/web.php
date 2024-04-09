<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version() . 'v1';
});
$router->group([
    'namespace' => 'User',
], function () use ($router) {
    $router->get('/users', 'UserController@index');
});
$router->group([
    'namespace' => 'TipoDocumento',
], function () use ($router) {
    $router->get('/tipo-documentos', 'TipoDocumentoController@index');
});

$router->group([
    'namespace' => 'Indicador',
], function () use ($router) {
    $router->get('/indicadores/empresas-atendidas', 'IndicadorEmpresaAtentidaController@__invoke');
});


