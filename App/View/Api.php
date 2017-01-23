<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 1/22/17
 * Time: 6:11 PM
 */

namespace App\View;


use Ramsey\Uuid\Uuid;
use Slim\Http\Response;

class Api
{
    const CODE = 'code';
    const MESSAGE = 'msg';
    const STACK = 'stack';
    const REQUEST_ID = 'request_id';

    private $response;
    private $ret;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function respond($data, $statusCode = 200, $message = 'success')
    {
        $this->ret[self::CODE] = $statusCode;
        $this->ret[self::MESSAGE] = $message;
        $this->ret['data'] = $data;
        $this->ret[self::REQUEST_ID] = Uuid::uuid4();

        return $this->response->withJson($this->ret);
    }
}