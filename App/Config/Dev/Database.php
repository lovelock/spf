<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 1/22/17
 * Time: 4:28 PM
 */

namespace App\Config\Dev;


use Spw\Config\ConfigInterface;

class Database implements ConfigInterface
{

    private $rmdbsName = 'mysql';
    private $host = '127.0.0.1';
    private $port = 3306;
    private $dbName = 'testspf';
    private $userName = 'testspf';
    private $pass = 'testspf';
    private $charset = 'utf8';
    /**
     * Get RMDBS name: mysql for default.
     *
     * @return string
     */
    public function getRMDBSName()
    {
        return $this->rmdbsName;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Database name.
     *
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->dbName;
    }

    /**
     * Character set.
     *
     * @return string
     */
    public function getDefaultCharset()
    {
        return $this->charset;
    }

    /**
     * User name.
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Database password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->pass;
    }
}