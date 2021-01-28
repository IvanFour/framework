<?php


namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class RouteController extends BaseController
{

    use Singleton;

    public $routes;



    private function  __construct()
    {
        $address_str = $_SERVER['REQUEST_URI'];
        var_dump($_SERVER['QUERY_STRING']);

        if($_SERVER['QUERY_STRING']){
            $address_str = substr($address_str,0, strpos($address_str, $_SERVER['QUERY_STRING'])-1);
        }


       //здесь должен быть "/"
       $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'],'index.php'));

       if ($path === PATH){
           //если слеш стоит в конце строки (strrpos позиция последнего вхождения) и не корень сайта
           if (strrpos($address_str, '/') === strlen($address_str) - 1 &&
               strrpos($address_str, PATH) !== strlen(PATH) - 1)
           {

               $this->redirect(rtrim($address_str, '/'),301);
           }

           $this->routes = Settings::get('routes');

           //если не нашли роуты выбрасываем исключение
           if (!$this->routes) throw new RouteException('Отсутствуют маршруты в базовых настройках',1);

           $url = explode('/',  substr($address_str, strlen(PATH)));

           //админ часть//
           //если после корня идет админка а не что то другое
           if ($url[0] && $url[0] === $this->routes['admin']['alias']){

               //обрезаем слово админ и возвращаем массив контр/экшон
               array_shift($url);

               //нет ли обращения к плагину после слова админ
               if($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])){
                   //если есть вырезаем, из юрл имя плагина
                   $plugin = array_shift($url);
                   $pluginSettingPath = $this->routes['setting']['path']  . ucfirst($plugin . 'Settings');

                   if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettingPath . '.php')){
                       $pluginSettingPath = str_replace('/', '\\', $pluginSettingPath);

                       $this->routes = $pluginSettingPath::get('routes');
                   }
                   $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';
                   $dir = str_replace('//', '/', $dir);

                   $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;
                   $hrUrl = $this->routes['plugins']['hrUrl'];
                   $route = 'plugins';


               } else {
                   $this->controller = $this->routes['admin']['path'];
                   $hrUrl = $this->routes['admin']['hrUrl'];
                   $route = 'admin';
               }
           } else{
               //пользовательская часть //
               //вырез подстроку после главного юрл и разбить в массив
              // $url = explode('/',  substr($address_str, strlen(PATH)));

               //человекопонятн юрл
               $hrUrl = $this->routes['user']['hrUrl'];
               $this->controller = $this->routes['user']['path'];
               $route = 'user';
           }

           $this->createRoute($route, $url);
           if ($url[1]){
               $count = count($url);
               $key = '';

               if (!$hrUrl){
                   $i = 1;
               } else {
                   $this->parameters['alias'] = $url[1];
                   $i = 2;
               }

               for ( ; $i < $count; $i++) {
                   if (!$key){
                       $key = $url[$i];
                       $this->parameters[$key] = '';
                   } else {
                       $this->parameters[$key] = $url[$i];
                       $key = '';
                   }
               }
           }
       } else{
           throw new  RouteException('Не корректная дирректоря сайта',1);
       }
    }


    //роуты

    /**
     * @param $role
     * @param $url
     * @return bool
     */
    private function createRoute($role, $url){
        //$var = user или admin
        $route = [];
        if (!empty($url[0])){
            //если в маршрутах существует псевдоним контроллера
            if ($this->routes[$role]['routes'][$url[0]]){

                //попадает имя контроллера[0] (например site) и метод [1]
                $route = explode('/',$this->routes[$role]['routes'][$url[0]]);
                //к пути присовокупляем имя контроллера
                $this->controller .= ucfirst($route[0] . 'Controller');
            }else {
                $this->controller .= ucfirst($url[0] . 'Controller');
            }
        }
        else {
            $this->controller .= $this->routes['default']['controller'];
        }

        //дефолтные методы если в роутах есть метод выбираем его если нет дефолтный
        $this->inputMethod  = $route[1] ? $route[1] : $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ? $route[2] : $this->routes['default']['outputMethod'];
        return true;
    }

}