<?php
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = $_POST['category'] ?? null;
    $ingredients = trim($_POST['ingredients'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $steps = trim($_POST['steps'] ?? '');

    $errors = [];

    if ($title === '') {
        $errors[] = 'Название обязательно';
    }

    if (empty($errors)) {
        try {
            $pdo = getPDO();
            $tags = trim($_POST['tags'] ?? '');


    $stmt = $pdo->prepare("INSERT INTO recipes (title, category, ingredients, description, tags, steps)
                       VALUES (:title, :category, :ingredients, :description, :tags, :steps)");


if (!$stmt->execute([
    ':title' => $title,
    ':category' => ($category === '' ? null : $category),
    ':ingredients' => $ingredients,
    ':description' => $description,
    ':tags' => $tags,
    ':steps' => $steps
])) {
    print_r($stmt->errorInfo());
    exit;
}

           header('Location: /');
            exit;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    } else {
        foreach ($errors as $err) {
            echo "<p style='color:red'>$err</p>";
        }
    }
} else {
    echo "ошибка";
}

header('Location: /');
exit;

