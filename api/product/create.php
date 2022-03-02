<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// получаем соединение с базой данных
include_once '../config/database.php';

// создание объекта товара
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
// убеждаемся, что данные не пусты
if (
    !empty($_POST['name']) &&
    !empty($_POST['quantity']) &&
    !empty($_POST['option'])
) {
$product_id=0;
    // устанавливаем значения свойств товара
    $product->name = $_POST['name'];
    $product->quantity = $_POST['quantity'];
    $product->option = $_POST['option'];

    // создание товара
    $stmt=$product->create();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлекаем строку
        extract($row);
        $product_id=$row['last'];
    }
    
    
    if($product->create_option_value($product_id)){
        // установим код ответа - 201 создано
        http_response_code(201);

        // сообщим пользователю
        echo json_encode(array("message" => "Товар был создан."), JSON_UNESCAPED_UNICODE);
    }

    // если не удается создать товар, сообщим пользователю
    else {

        // установим код ответа - 503 сервис недоступен
        http_response_code(503);

        // сообщим пользователю
        echo json_encode(array("message" => "Невозможно создать товар."), JSON_UNESCAPED_UNICODE);
    }
}

// сообщим пользователю что данные неполные
else {

    // установим код ответа - 400 неверный запрос
    http_response_code(400);

    // сообщим пользователю
    echo json_encode(array("message" => "Невозможно создать товар. Данные неполные."), JSON_UNESCAPED_UNICODE);
}
?>