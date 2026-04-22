<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

$conn = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

if ($conn->connect_error) {
    die("❌ Błąd: " . $conn->connect_error);
}