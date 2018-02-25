<?php
namespace Controllers;
use \App\Controller;

/**
 * Дефолтный контроллер
 * Class Home
 * @package Controllers
 */
class Home extends Controller
{

    public function index ()
    {
        return $this->render('Home');
    }

}