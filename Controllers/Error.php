<?php
namespace Controllers;

use App\Controller;

class Error extends Controller
{
    /**
     * Внутренняя ошибка
     * @return string
     */
    public function error500()
    {
        return $this->render('error'.DIRECTORY_SEPARATOR.'error');
    }

    /**
     * Не найден контроллер
     * @return string
     */
    public function classNotFound()
    {
        return $this->render('error'.DIRECTORY_SEPARATOR.'classNotFound');
    }

    public function errorDB(){
        return $this->render('error'.DIRECTORY_SEPARATOR.'dbNotFound');
    }

    /**
     * Общее икслючение
     * toDo пропработвать до 500б 404 и т.д.
     * @param $input
     * @return string
     */
    public function exeption($input)
    {
        return $this->render('error'.DIRECTORY_SEPARATOR.'exeption',$input);
    }
}