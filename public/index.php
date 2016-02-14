<?php
require_once __DIR__ . '/../vendor/autoload.php';

$app = new Slim\App([
    'settings' => [
        'determineRouteBeforeAppMiddleware' => true,
        'middleware' => [
            'authentication' => [
                'filter_mode' => \DashTec\Middleware\AbstractFilterableMiddleware::EXCLUSION,
                'route_names' => [],
            ],
            'authorization' => [
                'filter_mode' => \DashTec\Middleware\AbstractFilterableMiddleware::INCLUSION,
                'route_names' => [],
            ],
        ],
    ],
]);

$app->add(new \DashTec\Middleware\AuthenticationMiddleware($app));

$app->get('/', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    return $response->write('aloha');
})->setName('index');

$app->run();