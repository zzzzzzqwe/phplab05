<?php
/**
 * Удаляет рецепт по ID из базы данных.
 *
 * Осуществляет редирект на главную страницу после удаления.
 */
require_once __DIR__ . '/../db.php';

if (isset($_GET['id'])) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("DELETE FROM recipes WHERE id = :id");
    $stmt->execute([':id' => $_GET['id']]);
}

header('Location: /');
exit;
