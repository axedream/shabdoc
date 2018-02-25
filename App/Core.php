<?php
namespace App;
use App;

/**
 * Ядро приложения
 * Class Core
 * @package App
 */
class Core
{

    public $defaultController = 'Home';
    public $defaultAction = "index";

    /**
     * Обращаемся к роутеру и запускаем действие контроллера
     * кидаем исключение, если нет нужного контроллера или метода
     */
    public function run()
    {
        list($conteller,$acion,$params) = App::$router->parserParamRequest();
        echo $this->runAction($conteller, $acion, $params);
    }

    /**
     * Экстанцируем контроллер, выполняем метод с параметром
     * @param $controller
     * @param $action
     * @param $params
     * @return mixed
     */
    public function runAction($controller, $action=FALSE, $params=FALSE)
    {
        $controller = (empty($controller)) ? $this->defaultController : ucfirst($controller);

        //исключение если файа нет
        if(!file_exists(ROOTPATH.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.$controller.'.php')){
            $controller = $this->defaultController; //toDo нужно вызвать исклюение, додумать
        }

        //исключение если класс не существует
        if(!class_exists("\\Controllers\\".ucfirst($controller))){
            $controller = $this->defaultController;
            $action = $this->defaultAction;
            $params = NULL;
        }

        $controller = "\\Controllers\\".ucfirst($controller);
        $controller = new $controller;

        $action = empty($action) ? $this->defaultAction : $action;
        if (!method_exists($controller, $action)){
            $controller = $this->defaultController;
            $action = $this->defaultAction;
            $params = NULL;
        }

        return $controller->$action($params);

    }

}