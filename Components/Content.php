<?php
namespace Components;

use Models\Page;

class Content
{
    public $dir;            //дирестория хранения файлов
    public $mimeType;       //типы страниц
    public $page_id;        //поисковый параметр таблица
    public $ex;             //расширение файла
    public $file_name;      //имя файла (без расширения)


    /**
     * Содержимое из файла
     * @param $file_name
     * @return array|bool
     */
    public function getFileContent($file_name)
    {
        $fullFileName = $this->dir.$this->file_name.'.'.$this->ex;
        if (file_exists($fullFileName)) {
            $content = file_get_contents($fullFileName);
            if ($this->ex=='html') {
                $type='html';
            }
            if ($this->ex=='txt') {
                $type ='plain';
            }
            return ['type'=>$type, 'title'=>$file_name,'content'=>$content];
        }
        return FALSE;
    }

    /**
     * Содержимое из базы
     * @param $page_id
     * @return array|bool
     */
    public function getDBContent($page_id)
    {
        $model = new Page();
        $result = $model->getAll($page_id)[0];

        if ($result['mime']== 'text/html') {
            $type = 'html';
        }
        if ($result['mime']== 'text/plain') {
            $type = 'plain';
        }

        if ($result) {
            return ['type'=>'html', 'title'=>$result['title'],'content'=>$result['text']];
        }

        return FALSE;
    }


    /**
     * Получаем опорные данные и возвращаем содержимое
     * @param $input
     * @return bool
     */
    public function getContent()
    {
        //если из базы данных
        if ($this->mimeType=='table' && is_numeric($this->page_id)) {
            return $this->getDBContent($this->page_id);
        }
        //если из файла
        if ($this->mimeType=='file') {
            return $this->getFileContent($this->file_name);
        }
        return FALSE;
    }
}