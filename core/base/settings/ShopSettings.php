<?php

namespace core\base\settings;
use core\base\controller\Singleton;
use core\base\settings\Settings;

class ShopSettings
{
    use Singleton;

    private $baseSettings;

    private $routes = [
        'plugins' => [
          'dir' => false,
          'routes' => [


          ]
      ],
        'p' => [4,5,6]
    ];
    private $templateArr = [
        'text' => ['price', 'short'],
        'textarea' => ['goods_content']
    ];

// получ властивостей
    static public function get($property){
        return self::getInstance()->$property;
    }
// сінгл тон ,створення обєкту даного класу
    static private function getInstance(){
        if(self::$_instance instanceof self){
            return self::$_instance;
        }
// створення обєкту класу сетінгс

        self::instance()->baseSettings = Settings::instance();
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


}