<?php

use Ramsey\Uuid\Uuid;
use Slim\App;
use Spe\Env;
use Spf\Framework\Middleware\TrailingSlash;

define('WEB_ROOT', __DIR__ . '/..');

require WEB_ROOT . '/vendor/autoload.php';

define('ENV', (new Env())->get());
define('REQUEST_ID', Uuid::uuid4());

$container = require WEB_ROOT . '/App/bootstrap.php';

$app = new App($container);
$app->add(new TrailingSlash());

require WEB_ROOT . '/App/routes.php';

$app->run();