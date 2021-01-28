<?php
namespace core\user\controllers;

use core\base\controllers\BaseController;

class IndexController extends BaseController
{

    protected $name;

    public function inputData()
    {
        /*$name = 'Sem';
        $content = $this->render('', compact('name'));
        $header = $this->render(TEMPLATE . 'header');
        $footer = $this->render(TEMPLATE . 'footer');

        $this->who();
        return compact('header', 'content', 'footer');*/

        exit();
    }


/*
    public function outputData()
    {
        //возвращает 1 аргумент этой функции(передается в баз контроллере)
        $vars = func_get_arg(0);
        return $this->render(TEMPLATE. 'template', $vars);
    }*/

}