<?php


namespace core\base\controllers;


trait Singleton
{
    static private $instance;

    private function __construct()
    {

    }

    private function __clone()
    {

    }


    static public function getInstance(){
        if (self::$instance instanceof self){
            return self::$instance;
        }
        self::$instance = new self;

        if (method_exists(self::$instance, 'connect')) self::$instance->connect();
        return self::$instance;
    }
}