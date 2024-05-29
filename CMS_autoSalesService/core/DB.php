<?php

namespace core;

class DB
{
    public $pdo;
    public function __construct()
    {
        $host = Config::get()->dbHost;
        $name = Config::get()->dbName;
        $login = Config::get()->dbLogin;
        $password = Config::get()->dbPass;
        $this->pdo = new \PDO("mysql:localhost");
    }
}