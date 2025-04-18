<?php
ob_start();
require_once __DIR__ . '/../src/handlers/db.php';
$pdo = getPDO();

$stmt = $pdo->query("SELECT * FROM recipes ORDER BY id DESC");
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Рецепты</h1>
<a href="/create">Добавить новый</a>
<ul>
    <?php foreach ($recipes as $recipe): ?>
        <li>
            <a href="/recipe?id=<?= $recipe['id'] ?>"><?= htmlspecialchars($recipe['title']) ?></a>
            — <a href="/edit?id=<?= $recipe['id'] ?>">Редактировать</a>
            — <a href="/delete?id=<?= $recipe['id'] ?>" onclick="return confirm('Удалить рецепт?')">Удалить</a>
        </li>
    <?php endforeach; ?>
</ul>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
