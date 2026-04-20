<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "error" => "not_logged"
    ]);
    exit;
}

$id = (int)($_POST['produkt_id'] ?? 0);
$ilosc = (int)($_POST['ilosc'] ?? 1);

if ($id <= 0 || $ilosc <= 0) {
    echo json_encode(["success"=>false]);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $ilosc;

echo json_encode([
    "success" => true,
    "count" => array_sum($_SESSION['cart'])
]);