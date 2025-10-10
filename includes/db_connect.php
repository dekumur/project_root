<?php
require_once __DIR__ . '/config.php';
$connect = new mysqli("127.0.0.1", "root", "", "financial_center");
if($connect->connect_error){
    die("Ошибка: " . $connect->connect_error);
}
?>
