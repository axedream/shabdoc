<?php
namespace Controllers;
use \App\Controller;

class Test extends Controller
{

    public function doo(){
        return $this->render('Doo');
    }

}