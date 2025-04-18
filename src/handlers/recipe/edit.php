<?php
/**
 * Позволяет редактировать рецепт.
 *
 * Загружает рецепт по ID и сохраняет изменения из POST-запроса.
 */
require_once __DIR__ . '/../db.php';

$pdo = getPDO();
$errors = [];

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
    $steps = trim($_POST['steps'] ?? '');
    $tags = trim($_POST['tags'] ?? '');


    if ($title === '') {
        $errors[] = 'Название обязательно';
    }

    if (!in_array((int)$category, [1, 2, 3])) {
        $errors[] = 'Категория должна быть: 1 — Завтрак, 2 — Обед, 3 — Ужин';
    }

    if ($ingredients === '') {
        $errors[] = 'Ингредиенты обязательны';
    }

    if ($description === '') {
        $errors[] = 'Описание обязательно';
    }

    if ($steps === '') {
        $errors[] = 'Шаги приготовления обязательны';
    }

    if (empty($errors) && $id) {
        $stmt = $pdo->prepare("UPDATE recipes 
            SET title = :title, category = :category, ingredients = :ingredients, 
                description = :description, tags = :tags, steps = :steps 
            WHERE id = :id");

        $stmt->execute([
            ':title' => $title,
            ':category' => $category,
            ':ingredients' => $ingredients,
            ':description' => $description,
            ':tags' => $tags,
            ':steps' => $steps,
            ':id' => $id
        ]);

        header('Location: /');
        exit;
    } else {
        $recipe = [
            'id' => $id,
            'title' => $title,
            'category' => $category,
            'ingredients' => $ingredients,
            'description' => $description,
            'tags' => $tags,
            'steps' => $steps
        ];
        include __DIR__ . '/../../../templates/recipe/edit.php';
    }
}
