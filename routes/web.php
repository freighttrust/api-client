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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/register', 'AuthController@postSignUp');
$router->post('/login', 'AuthController@postSignIn');
$router->post('/forgot', 'AuthController@postForgot');
$router->post('/reset', 'AuthController@postReset');
$router->post('/upload', 'UtilityController@postUpload');
$router->get('/carriercompanies', 'API\CompanyController@getCarrierCompanies');

$router->post('/notification', 'API\NotificationController@store');

resource($router, '/company', 'API\CompanyController');
resource($router, '/member', 'API\MemberController');
resource($router, '/bill', 'API\BillController');

function resource($router, $uri, $controller) {
	$router->get($uri, [ 'uses' => $controller . '@index' ]);
	$router->post($uri, [ 'uses' => $controller . '@store' ]);
	$router->get($uri.'/{id}', [ 'uses' => $controller . '@show' ]);
	$router->put($uri.'/{id}', [ 'uses' => $controller . '@update' ]);
	$router->patch($uri.'/{id}', [ 'uses' => $controller . '@update' ]);
	$router->delete($uri.'/{id}', [ 'uses' => $controller . '@destroy' ]);
}