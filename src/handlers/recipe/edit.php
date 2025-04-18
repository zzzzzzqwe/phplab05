<?php
require_once __DIR__ . '/../db.php';

$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = :id");
    $stmt->execute([':id' => $_GET['id']]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recipe) {
        include __DIR__ . '/../../../templates/recipe/edit.php';
    } else {
        echo "Рецепт не найден.";
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $category = $_POST['category'] ?? null;
    $ingredients = trim($_POST['ingredients'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $tags = trim($_POST['tags'] ?? '');
    $steps = trim($_POST['steps'] ?? '');

    if ($id && $title) {
        $stmt = $pdo->prepare("UPDATE recipes SET title = :title, category = :category, ingredients = :ingredients,
            description = :description, tags = :tags, steps = :steps WHERE id = :id");

        $stmt->execute([
            ':title' => $title,
            ':category' => ($category === '' ? null : $category),
            ':ingredients' => $ingredients,
            ':description' => $description,
            ':tags' => $tags,
            ':steps' => $steps,
            ':id' => $id
        ]);
    }

    header('Location: /');
    exit;
}
