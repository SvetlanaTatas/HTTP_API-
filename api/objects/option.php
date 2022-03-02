<?php
class Option{

    // соединение с БД и таблицей 'options'
    private $conn;
    private $table_name = "options";

    // свойства объекта
    public $id;
    public $name;

    public function __construct($db){
        $this->conn = $db;
    }

    // используем раскрывающийся список выбора
    public function readAll(){
        // выборка всех данных
        $query = "SELECT o.id_option, o.name_option, pov.value,o.code
                  FROM options o
                  INNER JOIN product_option_value pov ON (pov.options=o.id_option)
                  ORDER BY o.name_option ASC";
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }
    
    // используем раскрывающийся список выбора
public function read(){

    // выбираем все данные
    $query = "SELECT o.id_option, o.name_option, GROUP_CONCAT(pov.value SEPARATOR ';') as ovalue, o.code
              FROM options o
              INNER JOIN product_option_value pov ON (pov.options=o.id_option)
              GROUP BY o.name_option ASC";

    $stmt = $this->conn->prepare( $query );
    $stmt->execute();

    return $stmt;
}
}
?>