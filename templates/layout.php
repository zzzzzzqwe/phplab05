<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Рецепты' ?></title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header>
        <h1>Книга рецептов</h1>
        <nav>
            <a href="/">Главная</a> |
            <a href="/create">Добавить рецепт</a>
        </nav>
    </header>
    <main>
        <?= $content ?>
    </main>
</body>
</html>
