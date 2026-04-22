<?php
session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){

echo json_encode([
"success"=>false
]);

exit;
}

$id = (int)$_POST['produkt_id'];
$ilosc = (int)$_POST['ilosc'];

if(!isset($_SESSION['cart'])){
$_SESSION['cart'] = [];
}

$_SESSION['cart'][$id] =
($_SESSION['cart'][$id] ?? 0)
+ $ilosc;

echo json_encode([
"success"=>true,
"count"=>array_sum($_SESSION['cart'])
]);