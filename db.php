<?php

function loadEnv($path)
{
    if (!file_exists($path)) {
        die(".env not found");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);

        $_ENV[trim($name)] = trim($value);
    }
}

loadEnv(__DIR__ . '/.env');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $conn = new mysqli(

        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']

    );

    $conn->set_charset("utf8mb4");

} catch (Exception $e) {

    die("Błąd połączenia: " . $e->getMessage());

}