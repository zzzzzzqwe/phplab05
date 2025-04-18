<?php
function getPDO() {
    $config = require __DIR__ . '/../../config/db.php';


    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

    try {
        $pdo = new PDO($dsn, $config['user'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo($e->getMessage());
    }
}
