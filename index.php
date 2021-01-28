<?php
define('VG_ACCESS', 1); //костанта безопасности

header('Content-Type:text/html;charset=utf-8');
session_start();
require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';
require_once  'userfiles/function/dump.php';


use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\base\controllers\RouteController;



try {
    RouteController::getInstance()->route();
}
catch (RouteException $e){
    exit($e->getMessage());
}
catch (DbException $e) {
    exit($e->getMessage());
}
