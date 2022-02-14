<?php

define('VG_ACCESS', true);                                                      // константа безпеки

header('Content-Type:text/html;charset-utf8');                           // відпр. користувачу заголовків
session_start();                                                               //старт сесії

require_once 'config.php';                                                    //  файл настройки для розміщення на хоті
require_once 'core/base/settings/internal_settings.php';                      //розширені внутрішні настройки

use core\base\exceptions\RouteException;                                      //імпорт виключень
use core\base\controller\RouteController;
          // обробка виключень
try{
    RouteController::getInstance()->route();                         //виклик статичного метода у класа роут контроллер

}
catch(RouteException $e){
    exit($e->getMessage());
}