<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 1/20/17
 * Time: 2:36 PM
 */

use App\Controllers\HelloController;

$app->get('/hello/{name}', HelloController::class . ':world');