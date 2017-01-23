<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 1/20/17
 * Time: 5:21 PM
 */

use App\Config\Bag;
use App\View\Api;
use App\View\Text;
use App\Wrapper\HttpClient;
use Monolog\Handler\StreamHandler;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Spe\Env;
use Spw\Connection\Connection;
use App\Wrapper\Logger as LoggerWrapper;

$container = new Container([
    'settings' => [
        'displayErrorDetails' => true,

        'logger' => [
            'name' => 'slim-app',
            'level' => Monolog\Logger::DEBUG,
            'path' => WEB_ROOT . '/logs/app.log',
        ]
    ],
]);


$container['rawLogger'] = function ($c) {
    $logger = new \Monolog\Logger($c->get('settings')['logger']['name']);
    $fileHandler = new StreamHandler($c->get('settings')['logger']['path']);
    $logger->pushHandler($fileHandler);
    return $logger;
};

$container['phpErrorHandler'] = function ($c) {
    return function (Request $request, Response $response, Error $e) {
        $config = [
            'code' => $e->getCode(),
            'msg' => $e->getMessage(),
        ];
        if (ENV === Env::DEV) {
            $config['stack'] = $e->getTrace();
        }
        return $response->withStatus(200)
            ->withJson($config);
    };
};

$container['errorHandler'] = function ($c) {
    return function (Request $request, Response $response, Exception $e) {
        $config = [
            'code' => $e->getCode(),
            'msg' => $e->getMessage(),
        ];
        if (ENV === Env::DEV) {
            $config['stack'] = $e->getTrace();
        }
        return $response->withStatus(200)
            ->withJson($config);
    };
};

$container['notFoundHandler'] = function ($c) {
    return function (Request $request, Response $response) {
        return $response->withStatus(200)
            ->withJson([
                'code' => 404,
                'msg' => 'Not found',
            ]);
    };
};

$container['notAllowedHandler'] = function ($c) {
    return function (Request $request, Response $response) {
        return $response->withStatus(200)
            ->withJson([
                'code' => 405,
                'msg' => 'Your request is not allowed.',
            ]);
    };
};

$container['db'] = function ($c) {
    return new Connection(Bag::get('database'));
};

$container['client'] = function ($c) {
    return new HttpClient();
};

$container['apiView'] = function ($c) {
    return new Api($c->get('response'));
};

$container['textView'] = function ($c) {
    return new Text($c);
};

$container['logger'] = function ($c) {
    return new LoggerWrapper($c);
};

return $container;