<?php
namespace Components;


/**
 * Ищем необходимую страницу
 * Class Find
 * @package Models
 */
class Find
{

    public $page_dir;
    public $model;
    public $input;


    public function __construct($input)
    {
        $input = ($input)? $input : $this->input;
        if ($this->validParam($input)) {
            $this->input = $input;
        }
    }

    /**
     * Проверяем на принадлежность в базе данных
     * @param $input
     * @return array
     */
    public function testPageDB()
    {
        if ($this->input) {
            $this->model->where(['link'=>$this->input]);
            $result = $this->model->getAll();
            if ($result) {
                return ['error'=>FALSE,'type'=>'table','page_id'=>$result[0]['page_id'],'ex'=>''];
            }
        }
        return ['error'=>TRUE];
    }


    /**
     * Проверяем на принадлежность к файлу
     * @param $input
     * @return array
     */
    public function testPageFile() {
        $files = scandir($this->page_dir);

        foreach ($files as $file) {
            if (!in_array($file,['.','..'])) {
                $_temp = explode('.',$file);
                if ($_temp[0]==$this->input) {
                    //toDo нужно продумать если будет и html и txt файл с одинаковым именем, кому отдать приоритет
                    return ['error'=>FALSE,'type'=>'file','page_id'=>'','ex'=>$_temp[1]];
                }
            }
        }
        return ['error'=>TRUE];
    }

    /**
     * Валидация параметра
     * @param $input
     * @return bool
     */
    public function validParam($input){
        if (preg_match('/^[a-zA-Z0-9]{1,100}$/',$input)) {
            return TRUE;
        }
        return FALSE;
    }

}
