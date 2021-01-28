<?php


namespace core\base\settings;
use core\base\controllers\Singleton;
use core\base\settings\Settings;


class ShopSettings
{
    private $baseSettings;

    use Singleton;

    private $routes = [
        'plugins'=>[
            'path'=>'core/plugins/',
            'hrUrl'=>false,
            'dir' => 'controller',
            'routes' => [
                'product' => 'goods'
            ]
        ],
    ];

    private $templateArr = [
        'text'=>['price', 'short'],
        'textarea'=>['content', 'goods_content']
    ];



    /**
     * @param $property
     * @return mixed
     */
    static public function get($property)
    {
        return self::instance()->$property;
    }

    /**
     * @return mixed
     */
    static private function instance()
    {
        self::getInstance()->baseSettings = Settings::getInstance();// ссылка на объект SEttings
        $baseProperties = self::$_instance->baseSettings->clueProperties(get_class()); // склеивание свйств
        self::$_instance->setProperty($baseProperties);

        return self::$_instance;
    }

    /**
     * @param $properties
     */
    protected function setProperty($properties){
        if ($properties){
            foreach ($properties as $name=>$property){
                $this->$name = $property;
            }
        }
    }

}