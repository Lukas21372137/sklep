<?php

$conn = new mysqli(
    "mysql-cl-7.cyberadmin.cyberfolks.pl",
    "db100092904",
    "Mikolaj1234!",
    "db100092904"
);

if ($conn->connect_error) {
    die("FAIL: " . $conn->connect_error);
}

echo "OK CONNECTION";
?>