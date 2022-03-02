<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов для соединения с БД и файл с объектом Category
include_once '../config/database.php';
include_once '../objects/option.php';

// создание подключения к базе данных
$database = new Database();
$db = $database->getConnection();

// инициализация объекта
$option = new Option($db);

// запрос для категорий
$stmt = $option->read();
$num = $stmt->rowCount();

// проверяем, найдено ли больше 0 записей
if ($num>0) {

    // массив
    $option_arr=array();
    $option_arr["records"]=array();

    // получим содержимое нашей таблицы
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлекаем строку
        extract($row);

        $option_item=array(
            "id" => $id_option,
            "name_option" => $name_option,
            "ovalue"=>$ovalue,
            "code"=>$code
        );

        array_push($option_arr["records"], $option_item);
    }

    // код ответа - 200 OK
    http_response_code(200);

    // покажем данные категорий в формате json
    echo json_encode($option_arr);
} else {

    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // сообщим пользователю, что категории не найдены
    echo json_encode(array("message" => "Опции не найдены."), JSON_UNESCAPED_UNICODE);
}
?>