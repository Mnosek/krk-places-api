<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Phalcon\Mvc\Micro;
use Core\Action\ListPlaces;
use Core\Action\GetPlace;


$app = new Micro();

$app->get('/places', function () use ($app) {
    $params = $app->request->get();
    unset($params['_url']);
    $action = new ListPlaces($params);
    return $action->getResponse();
});

$app->get('/places/{id:[0-9a-zA-Z\_\-]+}', function ($id) {
    $params = ['placeid' => $id];
    $action = new GetPlace($params);
    return $action->getResponse();
});

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")
                  ->setHeader("Content-Type", "application/json");
    return $app->response;
});

$app->handle();
