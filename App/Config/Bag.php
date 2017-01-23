<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 1/22/17
 * Time: 4:23 PM
 */

namespace App\Config;


class Bag
{
    public static function get($configName)
    {
        $configClassName = '\App\Config\\' . ucfirst(ENV) . '\\' . ucfirst($configName);
        return new $configClassName;
    }
}