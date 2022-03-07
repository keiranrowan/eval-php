<?php
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/app.service.php';
require __DIR__ . '/../src/app.controller.php';
$app = AppFactory::create();

$controller = new AppController();

$app->get('/', function (Request $request, Response $response, array $args) use($controller) {
    return $response->write('Hello world. Try a different route.');
});

$app->get('/search/author/[{author_name}]', function (Request $request, Response $response, array $args) use($controller) {
    $res = $controller->find($args['author_name']);
    return $response->withJson($res);
});

$app->post('/create', function (Request $request, Response $response, array $args) use($controller) {
    ['error' => $error, 'data' => $data] = $controller->create($request->getParsedBody());

    if (!empty($error)) {
        return $response->withJson(['error' => $error], 400);        
    }
    
    return $response->withJson($data);
});

$app->post('/reset', function (Request $request, Response $response, array $args) use($controller) {
    $controller->reset();
    return $response->withJson(true);
});

$app->run();
