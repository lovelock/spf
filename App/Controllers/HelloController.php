<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 1/20/17
 * Time: 3:06 PM
 */

namespace App\Controllers;


use App\View\Api;
use App\View\Text;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * @property Api apiView
 * @property Text textView
 */
class HelloController extends BaseController
{
    public function world(Request $request, Response $response)
    {
//        return $this->apiView->respond([
//            'a' => 'b',
//            'c' => 'd',
//        ], 500, 'error message');
        $this->logger->info('hello ' . $request->getAttribute('name'));

        return $this->textView->render('test.twig', [
            'name' => $request->getAttribute('name'),
        ]);
    }
}