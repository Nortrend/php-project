<?php
$dsn = "pgsql:host='127.127.126.39';dbname='postgres'";
$user = 'postgres';
$password = 'password';

try {
    $dbh = new PDO($dsn, $user, $password);
    echo "Connected to PostgreSQL database successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}