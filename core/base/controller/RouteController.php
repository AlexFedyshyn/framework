<?php

namespace core\base\controller;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;
                                //Система маршрутизації.розбір адресної строки .....
class RouteController extends BaseController
{
    use Singleton;

    protected $routes;



    private function __construct()
    {
                 // получення адресної строки
        $address_str = $_SERVER['REQUEST_URI'];
                //перевірка чи це не корневий сайт ,якщо ні перенаправляєм його
        if(strrpos($address_str, '/') === strlen($address_str) - 1 && strrpos($address_str, '/') !== 0){
            $this->redirect(rtrim($address_str, '/'), 301);
        }
                //дії коли не виконається редірект
        $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));
        //перевірка настройки сервера
        if($path === PATH) {
            //получаєм властивість роут класу сетінгс
            $this->routes = Settings::get('routes');
            // якщо маршрути не описанні  виводимо виключення
            if(!$this->routes) throw new RouteException('Відсутні маршрути в базових настройках', 1);
            //перевірка чи не в адмінку заходить користувач
            $url = explode('/', substr($address_str, strlen(PATH)));

            if($url[0] && $url[0] === $this->routes['admin']['alias']){

                array_shift($url);

                if($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])){

                    $plugin = array_shift($url);

                    $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin . 'Settings');

                    if(file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')){
                        $pluginSettings = str_replace('/', '\\', $pluginSettings);
                        $this->routes = $pluginSettings::get('routes');
                    }


                    $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';
                    $dir = str_replace('//', '/' , $dir);

                    $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;

                    $hrUrl = $this->routes['plugins']['hrUrl'];

                    $route = 'plugins';

                }else{
                    $this->controller = $this->routes['admin']['path'];

                    $hrUrl = $this->routes['admin']['hrUrl'];

                    $route = 'admin';
                }

            }else{
                //якщо не адмін часть,то ми працюємо з контролерами користувача

                $hrUrl = $this->routes['user']['hrUrl'];
                //звідки підкл.чаємо контроллери
                $this->controller = $this->routes['user']['path'];

                $route = 'user';
            }
            $this->createRoute($route, $url);

            if($url[1]){
                $count = count($url);
                $key = '';

                if(!$hrUrl){
                    $i = 1;
                }else{
                    $this->parameters['alias'] = $url[1];
                    $i = 2;
                }

                for( ; $i < $count; $i++){
                    if(!$key){
                        $key = $url[$i];
                        $this->parameters[$key] = '';
                    }else{
                        $this->parameters[$key] = $url[$i];
                        $key = '';
                    }
                }
            }

        }else{
            //виключення при поганій настройці сервера "шлях !== шлях серв"
            throw new RouteException('не коректна директорія сайту', 1);


        }
    }
                //метод створення маршрутів
    private function createRoute($var, $arr){
        $route = [];
            // перевірка чи не пустий масив ,то це є контроллер
        if(!empty($arr[0])){
            if($this->routes[$var]['routes'][$arr[0]]){                                 //перевірка чи є еліас маршрутів
                $route = explode('/', $this->routes[$var]['routes'][$arr[0]]);

                $this->controller .= ucfirst($route[0].'Controller');
            }else{
                $this->controller .= ucfirst($arr[0].'Controller');              //якщо маршрут не описаний
            }
        }else{                                                                      //якщо арр пустий підключаємо дефолт
            $this->controller .= $this->routes['default']['controller'];
        }

        $this->inputMethod = $route[1] ? $route[1] : $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ? $route[2] : $this->routes['default']['outputMethod'];

        return;
    }

}