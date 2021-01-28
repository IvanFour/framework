<?php


namespace core\base\controllers;


use core\base\exceptions\RouteException;
use core\base\settings\Settings;
abstract class BaseController
{
    protected $header;
    protected $content;
    protected $footer;
    protected $page;

    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;
    protected $errors;

    protected $template;
    protected $styles;
    protected $scripts;

    protected $userId;

    use BaseMethods;

    /**
     * @throws RouteException
     */
    public function route()
    {

        $controller = str_replace('/', '\\', $this->controller);

        try {
            //через рефлекшон вызывается метод request и передаются аргументы
            $object  = new \ReflectionMethod($controller, 'request');

            $args = [
                'parameters'   => $this->parameters,
                'inputMethod'  => $this->inputMethod,
                'outputMethod' => $this->outputMethod,
            ];

            $object->invoke(new $controller, $args);

        }
        catch (\ReflectionException $e){
            throw new RouteException($e->getMessage());
        }

    }


    /**
     * @param $args
     */
    public function request($args)
    {

        $this->parameters = $args['parameters'];
        $inputData = $args['inputMethod'];
        $outputData = $args['outputMethod'];


        $data = $this->$inputData();

        if (method_exists($this, $outputData)){
            //готовая страница
            $page = $this->$outputData($data);
            if($page) $this->page = $page;

        }
        elseif ($data) {
            $this->page = $data;
        }

        if ($this->errors){
            $this->writeLog($this->errors);
        }

        $this->getPage();
    }


    /**
     * @param string $path
     * @param array $parameters
     * @return false|string
     * @throws RouteException
     * @throws \ReflectionException
     */
    protected function render($path = '', $parameters = [])
    {
        //превращ массив параметр в переменные
        extract($parameters);

        if (!$path){

            $class = new \ReflectionClass($this);
            $namespace = str_replace('\\', '/', $class->getNamespaceName() . '\\' );
            $routes = Settings::get('routes');

            if ($namespace === $routes['user']['path']){
                $template = TEMPLATE;
            }
            else {
                $template = ADMIN_TEMPLATE;
            }

            //путь до шаблона
            // ReflectionClass($this))->getShortName() получаем имя контроллера
            $path = $template . explode('controller', strtolower($class->getShortName()))[0];
        }

        //буфер обмена
        ob_start();
        if (!@include_once $path . '.php') throw new RouteException('No view exists - ' . $path);

        return ob_get_clean();
    }



    /**
     *
     */
    protected function getPage()
    {
        if (is_array($this->page)){
            foreach ($this->page as $block){
                echo $block;
            }
        } else {
            echo $this->page;
        }
        exit();
    }



}