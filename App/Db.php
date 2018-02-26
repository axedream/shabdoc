<?php
namespace App;
use App;
use Controllers\Error;

/**
 * Класс для работы с базой данных
 * Class Db
 */
class Db
{

    public $pdo;

    public function __construct()
    {
        $settings = $this->getPDOSettings();
        try {
            $this->pdo = new \PDO($settings['dsn'], $settings['user'], $settings['pass'], null);
        } catch (\Exception $e) {
            echo App::$core->runAction('Error', 'errorDB', [$e]);
            die();
        }
    }

    /**
     * Получаем параметры соединения
     * @return mixed
     */
    protected function getPDOSettings()
    {
        $config = include ROOTPATH.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.'Db.php';
        $result['dsn'] = "{$config['type']}:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $result['user'] = $config['user'];
        $result['pass'] = $config['pass'];
        return $result;
    }

    /**
     * Выполняем произвольный запрос
     * @param $query
     * @param array|null $params
     * @return array
     */
    public function query($query, array $params=null)
    {
        if(is_null($params)){
            $stmt = $this->pdo->query($query);
            return $stmt->fetchAll();
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

}