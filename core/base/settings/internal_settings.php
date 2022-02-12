<?php
defined('VG_ACCESS') or die('Access denied');                               //перевірка безпеки

const TEMPLATE = 'templates/default/';                                                  //шаблони інтерфейса корист.
const ADMIN_TEMPLATE = 'core/admin/view/';                                              //шлях шаблону адмін панелі
                  // конст безпеки
const COOKIE_VERSION = '1.0.0';
const CRYPT_KEY = '';
const COOKIE_TIME = 60;
const BLOCK_TIME = 3;

                // конст посторінкової навігації
const QTY = 8;
const QTY_LINKS = 3;
              // шлях до адмін стилів
const ADMIN_CSS_JS = [
    'styles' => [],
    'scripts' => []
];
              //шлях до юзер стилів
const USER_CSS_JS = [
    'styles' => [],
    'scripts' => []
];

use core\base\exceptions\RouteException;

        // функція авто загрузки класів

function autoloadMainClasses($class_name){

    $class_name = str_replace('\\','/', $class_name);

    if(!@include_once $class_name . '.php'){
        throw new RouteException('wrong file name - ' .$class_name);
    }
}
       //................................
spl_autoload_register('autoloadMainClasses');