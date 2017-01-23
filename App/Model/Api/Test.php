<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 1/22/17
 * Time: 5:28 PM
 */

namespace App\Model\Api;


use App\HttpClient;
use App\IoC;

/**
 * @property HttpClient client
 */
class Test extends IoC
{
    public function world()
    {
        return $this->client->get('http://gank.io/api/history/content/2/1');
    }
}