<?php
namespace App;
use App;

/**
 * Базовый класс конторллера
 * Class Controller
 * @package App
 */
class Controller
{

    public $no_layout = FALSE;

    public $layoutFile = 'Views/Layout.php';


    /**
     * Рендерим шаблон
     * @param $content
     * @return string
     */
    public function renderLayout ($content)
    {

        ob_start();
            require ROOTPATH.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.'Layout'.DIRECTORY_SEPARATOR."Layout.php";
        return ob_get_clean();

    }

    /**
     * Рендерим в буфер файл и отдаем его в шаблон
     * @param $viewName
     * @param array $params
     * @return string
     */
    public function render ($viewName, array $params = [])
    {

        $viewFile = ROOTPATH.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.$viewName.'.php';
        extract($params);
        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        ob_end_clean();
        if ($this->no_layout){
            return $content;
        }
        return $this->renderLayout($content);

    }

}