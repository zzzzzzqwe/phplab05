<?php
/**
 * Обрабатывает создание нового рецепта.
 *
 * Получает данные из POST-запроса и сохраняет их в базу данных.
 */
require_once __DIR__ . '/../db.php';

$title = trim($_POST['title'] ?? '');
$category = $_POST['category'] ?? null;
$ingredients = trim($_POST['ingredients'] ?? '');
$description = trim($_POST['description'] ?? '');
$steps = trim($_POST['steps'] ?? '');
$tags = trim($_POST['tags'] ?? '');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if (empty($errors)) {
        try {
            $pdo = getPDO();

            $stmt = $pdo->prepare("INSERT INTO recipes (title, category, ingredients, description, tags, steps)
                                   VALUES (:title, :category, :ingredients, :description, :tags, :steps)");

            $stmt->execute([
                ':title' => $title,
                ':category' => ($category === '' ? null : $category),
                ':ingredients' => $ingredients,
                ':description' => $description,
                ':tags' => $tags,
                ':steps' => $steps
            ]);

            header('Location: /');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Ошибка БД: ' . $e->getMessage();
        }
    }
}

require __DIR__ . '/../../../templates/recipe/create.php';
