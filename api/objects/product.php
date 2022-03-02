<?php
class Product {

    // подключение к базе данных и таблице 'product'
    private $conn;
    private $table_name = "product";

    // свойства объекта
    public $id;
    public $name;
    public $quantity;
    public $option;
    public $operation;

    // конструктор для соединения с базой данных
    public function __construct($db){
        $this->conn = $db;
    }

    // здесь будет метод read()
    // метод read() - получение товаров
function read(){

    // выбираем все записи
    $query = "SELECT p.id, p.name, p.quantity,GROUP_CONCAT(o.code,':',o.name_option,':',pov.value SEPARATOR ';') as optionn
              FROM product p
              LEFT JOIN product_option_value pov ON (pov.product=p.id)
              LEFT JOIN options o ON (pov.options=o.id_option)
              GROUP BY pov.product";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // выполняем запрос
    $stmt->execute();

    return $stmt;
}

// метод create - создание товаров
function create(){

    // запрос для вставки (создания) записей
    $query = "INSERT INTO " . $this->table_name . " SET name=:name, quantity=:quantity";
    $query1 = "SELECT LAST_INSERT_ID() as last;";
    

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // очистка
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->quantity=htmlspecialchars(strip_tags($this->quantity));

    // привязка значений
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":quantity", $this->quantity);

    // выполняем запрос
    $stmt->execute();
    $last_row = $this->conn->prepare($query1);
    $last_row->execute();

    if($last_row){
         
        return $last_row;
    }

    return false;
}

function create_option_value($row){
    $ar=array();
    
    foreach($this->option as $op){
     $ar[]="('".$row."','".$op['id']."','".$op['value']."')";
    }
    $ar=implode(',',$ar);
    
    // запрос для вставки (создания) записей
    $query = "INSERT INTO product_option_value (product, options, value) VALUES ".$ar;
    //file_put_contents('/home/users/w/wizi/domains/lagunaexpo.su/log_files.txt',$query);
         // подготовка запроса
    $stmt = $this->conn->prepare($query);
     if($stmt->execute()){
         
         return true; 
      }
    return false;
}

function outcome(){
   $query = "SELECT * FROM " . $this->table_name." WHERE id = :id";
   
    // подготовка запроса
    $stmt = $this->conn->prepare($query);
    $this->id=htmlspecialchars(strip_tags($this->id));
    $stmt->bindParam(':id', $this->id);

    // выполняем запрос
    $stmt->execute();
    return $stmt;
}

// метод update() - обновление товара
function update($product_quantity){
    // запрос для обновления записи (товара)
    $query = "UPDATE
                " . $this->table_name . "
            SET
                quantity = :quantity
            WHERE
                id = :id";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);
    
    // очистка
    $this->operation=htmlspecialchars(strip_tags($this->operation));
    $this->quantity=htmlspecialchars(strip_tags($this->quantity));
    
    if($this->operation=='more'){
       $this->quantity=(int)$this->quantity+(int)$product_quantity; 
    } else {
       if((int)$this->quantity<(int)$product_quantity) {
          $this->quantity=(int)$product_quantity-(int)$this->quantity; 
       }
    }
    $this->id=htmlspecialchars(strip_tags($this->id));

    // привязываем значения
    $stmt->bindParam(':quantity', $this->quantity);
    $stmt->bindParam(':id', $this->id);

    // выполняем запрос
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// метод search - поиск товаров
function search($keywords){
    
$keywords_arr= unserialize($keywords);    
$operation=$keywords_arr['operation'];
unset($keywords_arr['operation']);

if($operation=='equal'){
    $q1=array();
    foreach($keywords_arr as $k=>$kwa){
      $q1[]="(o.code='".$k."' AND pov.value='".$kwa."')";  
    }
   // выборка по всем записям
    $query = "SELECT p.id, 
       p.name, 
       p.quantity, 
       c.name_category,
       (SELECT GROUP_CONCAT(o1.name_option,':',pov1.value SEPARATOR ';')
                      FROM product_option_value pov1
                      LEFT JOIN options o1 ON (pov1.options=o1.id_option) 
                      WHERE pov1.product=p.id) as optionn
              FROM product p
              LEFT JOIN product_category pc ON (p.id=pc.product)
              LEFT JOIN categories c ON (pc.category=c.id_category)
              LEFT JOIN product_option_value pov ON (pov.product=p.id)
              LEFT JOIN options o ON (pov.options=o.id_option) ";
    $query.="WHERE ";
    $query.=implode(' OR ',$q1);
    $query.=" GROUP BY pov.product"; 
file_put_contents('/home/users/w/wizi/domains/lagunaexpo.su/log_files.txt',$query);
} else if ($operation=='lessThan'){
    foreach($keywords_arr as $k=>$kwa){
      $q2="(o.code='".$k."' AND pov.value<'".$kwa."')";  
    }
    // выборка по всем записям
    $query = "SELECT p.id, p.name, p.quantity, c.name_category,
                      (SELECT GROUP_CONCAT(o1.name_option,':',pov1.value SEPARATOR ';')
                      FROM product_option_value pov1
                      LEFT JOIN options o1 ON (pov1.options=o1.id_option) 
                      WHERE pov1.product=p.id) as optionn
              FROM product p
              LEFT JOIN product_category pc ON (p.id=pc.product)
              LEFT JOIN categories c ON (pc.category=c.id_category)
              LEFT JOIN product_option_value pov ON (pov.product=p.id)
              LEFT JOIN options o ON (pov.options=o.id_option) 
              WHERE ".$q2."
               GROUP BY pov.product";

} else {
   foreach($keywords_arr as $k=>$kwa){
      $q3="(o.code='".$k."' AND pov.value>'".$kwa."')";  
    }
    // выборка по всем записям
    $query = "SELECT p.id, p.name, p.quantity, c.name_category,
                     (SELECT GROUP_CONCAT(o1.name_option,':',pov1.value SEPARATOR ';')
                      FROM product_option_value pov1
                      LEFT JOIN options o1 ON (pov1.options=o1.id_option) 
                      WHERE pov1.product=p.id) as optionn
              FROM product p
              LEFT JOIN product_category pc ON (p.id=pc.product)
              LEFT JOIN categories c ON (pc.category=c.id_category)
              LEFT JOIN product_option_value pov ON (pov.product=p.id)
              LEFT JOIN options o ON (pov.options=o.id_option) 
              WHERE ".$q3."
              GROUP BY pov.product"; 
}

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // выполняем запрос
    $stmt->execute();
    
    return $stmt;
}
}
?>