<?php

namespace core\base\settings;
use core\base\settings\Settings;

class ShopSettings
{
    static private $_instance;
    private $baseSettings;

    private $routes = [
        'plugins' => [
          'dir' => false,
          'routes' => [


          ]
      ],
    ];
    private $templateArr = [
        'text' => ['price', 'short'],
        'textarea' => ['goods_content']
    ];

// получ властивостей
    static public function get($property){
        return self::instance()->$property;
    }
// сінгл тон ,створення обєкту даного класу
    static public function instance(){
        if(self::$_instance instanceof self){
            return self::$_instance;
        }
// створення обєкту класу сетінгс
        self::$_instance = new self;
        self::$_instance->baseSettings = Settings::instance();
        $baseProperties = self::$_instance->baseSettings->clueProperties(get_class()); //виклик методу склеювання властивостей
        self::$_instance->setProperty($baseProperties);
//.................................
        return self::$_instance;
    }
//метод запису того що прийшло в масив бейспроперті і створить властивості в ньому
    protected function setProperty($properties){
        if ($properties){
            foreach ($properties as $name => $property){
                $this->$name = $property;
            }
        }
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}