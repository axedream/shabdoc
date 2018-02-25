<?php
namespace App;

/**
 * Парсит адрес из $_SERVER['REQUEST_URI']
 * сслыки вида http://loca.host/[controller]/[action]
 * Class Router
 * @package App
 */
class Router
{
    //массив строки запроса пользователя
    public $router = [
        'controller'=>FALSE,
        'action'=>FALSE,
        'params'=>FALSE];

    //массив запросов GET, POST
    public $request =[];

    public $url;    //переданный адресс

    /**
     * Прасим URI
     * @return array
     */
    public function __construct($url=FALSE)
    {

        $this->url = ($url) ? $url : $_SERVER; //можно было и ??, но для совместимост php5.6 оставим
        $this->getParamRequest();
    }

    /**
     * Метод получение параметро запроса пользователя и остальных данных (в случае apache)
     */
    public function getParamRequest()
    {
        $this->router['all']    = trim($this->url['REQUEST_URI']);            //строка после хоста
        $this->router['host']   = trim($this->url["HTTP_HOST"]);              //строка до параметров, напранная польователем
        $this->router['ip']     = trim($this->url["REMOTE_ADDR"]);            //ip адресс пользователя
    }


    /**
     * Регуляки для проверки контроллеров, акшинов и параметров
     * @return array
     */
    public function getReg(){
        return [
            'controller'    =>	'/^[a-zA-Z0-9+_\-]{2,20}$/',
            'action'        =>	'/^[a-zA-Z0-9+_\-]{2,20}$/',
            'params'        =>	'/^[a-zA-Z0-9+_\-\:\?\=\/]{1,40}$/',
        ];
    }

    /**
     * Метод парсинга запроса пользователя
     * предполагаем что максимальная длинна запроса /controller/action/params
     * @return array
     */
    public function parserParamRequest ()
    {
        $_temp = explode('/', $this->router['all']);

        //контроллер
        if (!empty($_temp[1])){
            if (preg_match($this->getReg()['controller'], trim($_temp[1]))) $this->router['controller']=$_temp[1];
        }

        //действие
        if (!empty($_temp[2])){
            if (preg_match($this->getReg()['action'], trim($_temp[2]))) $this->router['action']=$_temp[2];;
        }

        //ид (ID)
        if (!empty($_temp[3])){
            if (preg_match($this->getReg()['params'], trim($_temp[3]))) $this->router['params']=$_temp[3];;
        }

        return [$this->router['controller'],$this->router['action'],$this->router['params']];

    }

}