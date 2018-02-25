<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 25.02.2018
 * Time: 21:44
 */

define('ROOTPATH', __DIR__);

require __DIR__ . '/App/App.php';

App::init();
App::$core->run();