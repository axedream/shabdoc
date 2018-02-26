<?php
namespace Controllers;
use \App\Controller;
use Components\Find;
use Components\Content;
use Components\Shabdoc;
use Models\Link;
use Models\Page as Pages;

/**
 * Точка вхождения искомых страниц
 * Class Page
 * @package Controllers
 */
class Page extends  Controller
{
    const PAGEDIR = ROOTPATH.DIRECTORY_SEPARATOR."Views".DIRECTORY_SEPARATOR."page".DIRECTORY_SEPARATOR;

    public $param;
    /**
     * Точка входа для преобразования и получений данных
     * @param bool $param
     * @return string
     */
    public function show($param=FALSE){
        $start = microtime(true);
        set_time_limit(0);
        $this->param = $param;
        $result = $this->getPageContent();
        if ($result) {
            if ($result['type']!='html') {
                $result['content'] = Shabdoc::getCovertContent($result['content']);
            }
            return $this->render('page',['content'=>$result['content'],'title'=>$result['title'],'time'=>(microtime(true) - $start)*1000000]);
        }
        return $this->render('doo');
    }

    /**
     * Получаем содержимое страницы (raw)
     * @return bool|array
     */
    public function getPageContent()
    {
        $typePage = $this->getPageType();
        $content = new Content();
        $content->mimeType = $typePage['type'];
        $content->dir = STATIC::PAGEDIR;
        $content->page_id = $typePage['page_id'];
        $content->ex = $typePage['ex'];
        $content->file_name = $this->param;
        $out = $content->getContent();
        if ($out) {
            return $out;
        }

        return FALSE;
    }


    /**
     * Возвращает тип страницы
     * @param $params
     */
    public function getPageType($param=FALSE)
    {
        $params = ($param) ? $param : $this->param;
        $modelFind = new Find($params);

        $modelFind->model = new Link();
        $result = $modelFind->testPageDB();

        if ($result['error']) {
            $modelFind->page_dir = static::PAGEDIR;
            $result = $modelFind->testPageFile();
        }
        return $result;
    }
}