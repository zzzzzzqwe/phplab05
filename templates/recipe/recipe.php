<?php
require_once __DIR__ . '/../db.php';

$title = 'Рецепт';
ob_start();

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "<p>Рецепт не найден.</p>";
} else {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->execute([$id]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recipe) {
        ?>
        <h2><?= htmlspecialchars($recipe['title']) ?></h2>
        <p><strong>Категория:</strong> <?= htmlspecialchars($recipe['category']) ?></p>
        <p><strong>Ингредиенты:</strong> <?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
        <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
        <p><strong>Шаги:</strong><br><?= nl2br(htmlspecialchars($recipe['steps'])) ?></p>

        <form action="/handlers/recipe/delete.php" method="POST" onsubmit="return confirm('Удалить рецепт?');">
            <input type="hidden" name="id" value="<?= $recipe['id'] ?>">
            <button type="submit" style="color: red;">Удалить</button>
        </form>

        <a href="/edit?id=<?= $recipe['id'] ?>">Редактировать</a>
        <?php
    } else {
        echo "<p>Рецепт не найден.</p>";
    }
}

$content = ob_get_clean();
require __DIR__ . '/../layout.php';
