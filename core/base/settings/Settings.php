<?php


namespace core\base\settings;

use core\base\controllers\Singleton;
use core\base\settings\ShopSettings;


class Settings
{
    use Singleton;

    private $routes = [
    'admin' => [
        'alias'=>'admin',
        'path'=>'core/admin/controllers/',
        'hrUrl'=>false,
        'routes'=>[

        ],
    ],
    'setting'=>[
        'path'=>'core/base/settings/'
    ],
    'plugins'=>[
        'path'=>'core/plugins/',
        'hrUrl'=>false,
        'dir' => '',
    ],
    'user'=>[
        'path' => 'core/user/controllers/',
        'hrUrl'=>true,
        'routes'=>[
            'catalog'=>'site/index/by',
            '/' => 'index/hello'
        ]
   ],
    'default' => [
        'controller'=>'IndexController',
        'inputMethod'=>'inputData',
        'outputMethod'=>'outputData',
        ],
    ];

    private $templateArr=[
        'text'=>['name', 'phone', 'address'],
        'textarea'=>['content', 'keyword']
    ];

    private $defaultTable = 'teachers';

    private $blockNeedle = [
        'vg-rows' => [],
        'vg-img' =>[],
        'vg-content' => []
    ];

    private $translate = [
        'name' => ['Название', " Не более 100 символов"],
        'content' =>['Контент']
    ];

    private $rootItems = [
        'name' => 'Корневая',
        'tables' => ['teachers'],
    ];

    private $radio = [
        'visible' => ['Нет', 'Да', 'default' => 'Да']
    ];


    private $projectTables = [
        'teachers' => [
            'name' => 'Учителя',
            'img'  => 'pages.png',
        ],
        'students' => [
            'name' => 'Ученики',
        ],
        'student_teacher' => [
            'name' => 'Ученики-Учителя',
        ],
    ];

    private $expansion = 'core/admin/expansion/';


    static public function get($property)
    {
        return self::getInstance()->$property;
    }


    /**
     * @param $class
     * @return array
     */
    public function clueProperties($class)
    {
        $baseProperties = [];//сюда попадают свойства объекта ShopSettings
        foreach ($this as $name => $item)  //здесь свойства текущего класса
        {
            $property = $class::get($name);

            if (is_array($property) && is_array($item)){

                $baseProperties[$name] = $this->arrayMergeRecursive($this->$name, $property);
                continue;
            }
            if (!$property) $baseProperties[$name] = $this->$name;

        }
        return $baseProperties;
    }

    //функция для склеивания массивов
    public function arrayMergeRecursive()
    {
        $arrays = func_get_args();
        $base = array_shift($arrays);

        foreach ($arrays as $array){
            foreach ($array as $key=>$value){
                if (is_array($value) && is_array($base[$key])){
                    $base[$key]=$this->arrayMergeRecursive($base[$key], $value);
                }
                else {
                    if (is_int($key)){
                        if (!in_array($value, $base)) array_push($base, $value);
                        continue;
                    }
                    $base [$key] = $value;
                }
            }
        }
        return $base;
    }

}