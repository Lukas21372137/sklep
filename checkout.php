<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    header("Location: koszyk.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$conn->query("INSERT INTO zamowienia (klient_id) VALUES ($user_id)");
$zamowienie_id = $conn->insert_id;

foreach ($_SESSION['cart'] as $id => $ilosc) {

    $conn->query("
        INSERT INTO zamowienia_pozycje (zamowienie_id, produkt_id, ilosc)
        VALUES ($zamowienie_id, $id, $ilosc)
    ");

    $conn->query("
        UPDATE produkty 
        SET stan_magazynowy = stan_magazynowy - $ilosc
        WHERE id = $id
    ");
}

$_SESSION['cart'] = [];

header("Location: success.php?id=$zamowienie_id");
exit;