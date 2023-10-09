<?php

namespace core;

use core\base\Database;
use core\base\Result;

class Application
{
    public Database $db;

    public Result $result;

    public static Application $app;

    public function __construct()
    {
        $this->db = new Database();
        $this->result = new Result();

        $this->app = (new self);
    }


    public function init()
    {

    }
}