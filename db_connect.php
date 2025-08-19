<?php

$host = 'sql303.infinityfree.com';
$db   = 'if0_39743407_casinha_cafe'; // Altere para o nome do seu banco de dados
$user = 'if0_39743407';      // Altere para o seu usuário do banco de dados
$pass = '22320133';      // Altere para a sua senha do banco de dados
$charset = 'utf8mb4';

$port = '3306';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

?>