<?php

$link = 'mysql:host=localhost;dbname=ht;charset=utf8';
$usuario = 'root';
$pass = '';

try {
    $pdo = new PDO($link, $usuario, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
