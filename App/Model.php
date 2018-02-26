<?php
namespace App;
use App;

/**
 * Работа с моделями
 * Class Model
 * @package App
 */
class Model
{

    public $db;             //объект базы данных
    public $table;          //наименование наблицы
    public $_where;         //условие
    public $_order='';      //сортировка
    private $sql_true = 0;  //наличие таблицы успешно выполненный запрос по получению полей таблицы 1
    public $array_value = [];


    /**
     * Инициализируем
     * Model constructor.
     */
    public function __construct()
    {
        $this->db = App::$db;
        $this->table = $this->table ?? lcfirst(explode("\\",get_class($this))[1]);
        $this->setValues();
    }

    /**
     * Реализуем свойства модели (из полей таблицы)
     */
    private function setValues()
    {
        if ($this->table) {
            foreach ($this->db->query('SHOW COLUMNS FROM '.$this->table) as $results) {

            //размещаем полученный свойсва в массив
            $this->array_value[] = $results['Field'];

            //устанавливаем ключ успешного запроса в базу
            if (!$this->sql_true) {
                $this->sql_true = 1;
                }
            }//end foreach
        }
    }

    /**
     * Условие
     * @param array $array
     */
    public function where($array=[])
    {
        if (is_array($array) && count($array)>0) {
            foreach ($array as $key => $value) {
                if (empty($this->_where)) {
                    $this->_where = $key." = '".$value."'";
                } else {
                    $this->_where .= " AND ".$key." = '".$value."'";
                }
            }
        }
    }

    /**
     * Сортировка
     * toDo делать проверку входящего параметра
     * @param $type
     * @param int $w
     */
    public function bsort($type,$w = 0)
    {
        if ($type) {
            if ($w) {
                $w = ' DESC ';
            }  else {
                $w = '';
            }
            $this->_order = " ORDER BY " .$type . $w;
        }
    }

    /**
     * Удаление записи по
     * либо установленному свойству ID
     * либо переданному в метод ID
     * либо по WHERE
     * @param int $id
     * @return bool
     */
    public function delete($id=0)
    {
        if ($this->table) {
            $query = "DELETE FROM ".$this->table." WHERE ";
            if(isset($this->id)) {
                if (!empty($this->id) && is_numeric($this->id)) {
                    if (!empty($this->_where)) {
                        $query .= $this->_where;
                    } else {
                        $query .= " id = '".$this->id."'";
                    }

                    return($this->db->query($query));
                }
            } else {
                if (is_numeric($id) && $id) {
                    $query .= " id = '".$id."'";

                    return($this->db->query($query));
                }
            }

        }

        return FALSE;
    }


    /**
    * Добавляем запись
    * @toDo продумать что бы автоматически выбирать на save добавление или обновление записи
    * @return FALSE|resource
    */
    public function  insert() {
        if ($this->sql_true) {
            $query= 'INSERT INTO '.$this->table;

            $key = '';
            $val = '';
            $i=0;

            foreach ($this->array_value as $value) {
                $i ++;
                if (!empty($this->$value)) {

                    if (empty($key)) {
                        $key .= '('.$value;
                    } else {
                        $key .= ' ,'.$value;
                    }

                    if (empty($val)) {
                        $val .=" VALUES ('".$this->$value."'";
                    } else {
                        $val .= ",'".$this->$value."'";
                    }

                    if (count($this->array_value) == $i) {
                        $key .= ') ';
                        $val .= ') ';
                    }

                } //ENDempty
            } //ENDforeach

            $query .= $key . $val;

            return($this->db->query($query));

        }
    }

    /**
     * Сохранение свойств объекта
     * @param array $array
     * @param array $inj
     * @return bool
     */
    public function update($array=[],$inj=[])
    {

        if (count($array)>0) {
            $this->where($array);
        }

        if ($this->sql_true) {
            $query = '';
            foreach ($this->array_value as $value) {
                if (!in_array($value,$inj)) {
                    if (empty($query)) {
                        $query = " SET ".$value." = '".$this->$value."'";
                    } else {
                        $query .= " , ".$value." = '".$this->$value."'";
                    }
                }
            }

            if ($this->_where) {
                $query .= " WHERE ".$this->_where;
            }

            $final_query = "UPDATE ".$this->table." ".$query;

            return ($this->db->query($final_query));
        }

        return FALSE;
    }

    /**
     * Получаем все данные из модели либо по ID
     * @param bool $id
     * @return bool
     */
    public function getAll($id = FALSE) {
        if ($this->sql_true) {
        //если ID не пустой
            if (!empty($id)) {
            //есди ID цифра
                if (is_numeric((int)$id)) {
                    $result = $this->db->query("SELECT * FROM " . $this->table . " WHERE id =" . $id . $this->_order );
                //если ID не цифра
                } else {
                    //если ID массив
                    if (is_array($id)) {
                        //разворачиваем ID массив
                        $this->where($id);
                        //получаем результат
                        $result = $this->db->query("SELECT * FROM " . $this->table . " WHERE ".$this->_where . $this->_order);
                        //если ID текст
                    } else {
                        //если свободный запрос
                        $result = $this->db->query("SELECT * FROM " . $this->table . " WHERE ".$id . $this->_order);
                    }
                }

            //если не передан ID
            } else {
                if ( !empty($this->_where)) {
                $result = $this->db->query("SELECT * FROM " . $this->table . " WHERE ".$this->_where . $this->_order);
                } else {
                    $result = $this->db->query("SELECT * FROM " . $this->table . $this->_order);
                }
            }

            if ($result) {
                if (is_array($result)) {
                    if (count($result)==1) {
                        foreach ($this->array_value as $value) {
                            $this->$value = $result[0][$value];
                        }
                    }
                }
            }

            if ($result) {
                return $result;
            }

        }

        return FALSE;
    }


    //полумаем свойства полей моедил
    public function __get($name)
    {
        //return ($this->db->getAll('SHOW COLUMNS FROM '.$this->table));
        return 'Нет такого поля';
    }

}