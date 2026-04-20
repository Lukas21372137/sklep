<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include 'db.php';

if (!isset($_POST['produkt_id'], $_POST['klient_id'], $_POST['ilosc'])) {
    die("❌ Brak danych formularza");
}

$produkt_id = (int) $_POST['produkt_id'];
$klient_id  = (int) $_POST['klient_id'];
$ilosc      = (int) $_POST['ilosc'];

if ($ilosc <= 0) {
    die("❌ Nieprawidłowa ilość");
}

try {

    // 1. sprawdzenie stanu
    $stmt = $conn->prepare("SELECT stan_magazynowy FROM produkty WHERE id = ?");
    $stmt->bind_param("i", $produkt_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("❌ Produkt nie istnieje");
    }

    $row = $result->fetch_assoc();

    if ($row['stan_magazynowy'] < $ilosc) {
        die("❌ Brak wystarczającej ilości na magazynie");
    }

    // 2. TRANSAKCJA (ważne!)
    $conn->begin_transaction();

    // INSERT zakupu
    $stmt = $conn->prepare("
        INSERT INTO zakupy (klient_id, produkt_id, ilosc)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iii", $klient_id, $produkt_id, $ilosc);
    $stmt->execute();

    // UPDATE magazynu
    $stmt = $conn->prepare("
        UPDATE produkty
        SET stan_magazynowy = stan_magazynowy - ?
        WHERE id = ?
    ");
    $stmt->bind_param("ii", $ilosc, $produkt_id);
    $stmt->execute();

    $conn->commit();

    echo "✅ Zakup udany!";

} catch (Exception $e) {
    $conn->rollback();
    echo "❌ Błąd zakupu: " . $e->getMessage();
}

echo "<br><a href='index.php'>Powrót</a>";
?>