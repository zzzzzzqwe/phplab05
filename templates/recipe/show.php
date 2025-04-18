<?php
require_once __DIR__ . '/../../src/handlers/db.php';

$pdo = getPDO();
$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$recipe) {
    echo "<p>Рецепт не найден.</p>";
    return;
}
?>

<h2><?= htmlspecialchars($recipe['title']) ?></h2>
<p><strong>Категория:</strong> <?= $recipe['category'] ?></p>
<p><strong>Ингредиенты:</strong><br><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
<p><strong>Описание:</strong><br><?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
<p><strong>Шаги:</strong><br><?= nl2br(htmlspecialchars($recipe['steps'])) ?></p>
<p><strong>Теги:</strong> <?= htmlspecialchars($recipe['tags']) ?></p>
