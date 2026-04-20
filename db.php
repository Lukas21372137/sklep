<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $conn = new mysqli(
        "mysql-cl-7.cyberadmin.cyberfolks.pl",
        "db100092904",
        "Mikolaj1234!",
        "db100092904"
    );

    $conn->set_charset("utf8mb4");

} catch (Exception $e) {
    die("❌ BŁĄD POŁĄCZENIA: " . $e->getMessage());
}
?>