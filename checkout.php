<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("❌ Nie jesteś zalogowany");
}

if (empty($_SESSION['cart'])) {
    die("Koszyk pusty");
}

$user_id = $_SESSION['user_id'];

$conn->begin_transaction();

try {

    // tworzymy zamówienie
    $conn->query("
        INSERT INTO zamowienia (klient_id)
        VALUES ($user_id)
    ");

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

    $conn->commit();
    $_SESSION['cart'] = [];

    echo "✅ Zamówienie złożone!";

} catch (Exception $e) {
    $conn->rollback();
    echo "❌ Błąd: " . $e->getMessage();
}
?>
<br><a href="index.php">Powrót</a>