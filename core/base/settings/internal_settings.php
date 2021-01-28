<?php
use core\base\exceptions\RouteException;
defined('VG_ACCESS') or die ('access denied'); //установленна ли константа


const TEMPLATE = 'templates/default/';
const ADMIN_TEMPLATE = 'core/admin/views/';
const UPLOAD_DIR = 'userfiles/';

const COOKIE_VERSION = '1.0.0';
const CRYPT_KEY = "";
const COOKIE_TIME = 60;
const BLOCK_TIME = 3; // на какое время блокировать взломщика
const QTY = 8; // отображатьсколько товаров
const QTY_LINKS = 3; // сколько ссылок отображать

const ADMIN_CSS_JS = [
    'styles'=>['css/main.css'],
    'scripts'=>[]
];
const USER_CSS_JS = [
    'styles'=>['css/main.css'],
    'scripts'=>[]
];



function autoloadMainClasses($class_name){
    $class_name = str_replace('\\','/',$class_name);
    if (!@include_once $class_name . '.php'){
        throw new RouteException('Не верное имя файла для подключения - ' . $class_name);
    }

}
spl_autoload_register('autoloadMainClasses');