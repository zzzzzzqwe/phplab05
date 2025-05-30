# Лабораторная работа №5. Работа с базой данных

## Студент
**Gachayev Dmitrii I2302**  
**Выполнено 18.04.2025**  

## Цель работы
Освоить архитектуру с единой точкой входа, подключение шаблонов для визуализации страниц, а также переход от хранения данных в файле к использованию базы данных (PostgreSQL).

## Краткое видео с демонстрацией работы проекта

[screenshots/showcase.mp4video.mp4](https://github.com/user-attachments/assets/c8bda90b-a0f6-4445-8195-4d8eebe815c7)

## Инструкция к запуску
1. Клонировать проект на компьютер.

2. Запустить командную строку в папке phplab05.

3. Выполнить команду `php -S localhost:8000 -t public`.

4. Перейти на http://localhost:8000/.

**ВАЖНО!**

В проекте в качестве `СУБД` используется `PostgreSQL`<br>
Для того чтобы код работал, нужно изменить конфигурационный файл `php.ini` и раскомментировать строки:
- `extension=pdo_pgsql`
- `extension=pgsql`

А также убедиться, что путь `extension_dir = ...` соответсвует папке `ext` на компьютере


## Задание 1. Подготовка среды
Для работы буду использовать субд `PostgreSQL`. 

1. Создаю базу данных `recipe_book` через `pgAdmin`:

![image](screenshots/Screenshot_1.png)

2. Создаю таблицу `recipes` (запрос переделал под `PostgreSQL`)
```sql
CREATE TABLE recipes (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  category INT NOT NULL,
  ingredients TEXT,
  description TEXT,
  tags TEXT,
  steps TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category) REFERENCES categories(id) ON DELETE CASCADE
);
```

3. Далее создаю таблицу `categories` (запрос переделал под `PostgreSQL`)
```sql
CREATE TABLE categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Задание 2. Архитектура и шаблонизация
1. Создаю файл `public/index.php` и настраиваю простую маршрутизацию: 
```php

<?php
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case "/": // just in case
        require_once '../templates/index.php';
        break;
    case "/index":
        require_once '../templates/index.php';
        break;

    case '/create': 
        require_once '../templates/recipe/create.php';
        break;

    case '/recipe':
        require_once '../templates/recipe/recipe.php';
        break;

    default:
        http_response_code(404);
        echo '<h1>404 — Страница не найдена</h1>';
        break;
}
```

2. Создаю файл `templates/layout.php` который будет служить базовым шаблоном для всех страниц:

```html
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
```

3. Создаю шаблоны для: 
- `index.php` - отображение рецептов
- `create` - добавление рецепта
- `recipe` - подробная информация о рецепте

`index.php`:
```php
<?php
$title = 'Все рецепты';
ob_start();
?>

<h2>Список рецептов</h2>
<ul>
    <li><a href="/recipe?id=1">Борщ</a></li>
    <li><a href="/recipe?id=2">Плов</a></li>
</ul>

<?php
$content = ob_get_clean();
require 'layout.php';
```

`create.php`:
```php
<?php
$title = 'Добавить рецепт';
ob_start();
?>

<h2>Добавить рецепт</h2>
<form action="/save" method="POST">
    <label>Название:<br><input type="text" name="title" required></label><br>
    <label>Категория:<br><input type="text" name="category"></label><br>
    <label>Ингредиенты:<br><textarea name="ingredients"></textarea></label><br>
    <label>Описание:<br><textarea name="description"></textarea></label><br>
    <label>Шаги:<br><textarea name="steps"></textarea></label><br>
    <button type="submit">Сохранить</button>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
```

`recipe.php`:
```php
<?php
$title = 'Рецепт';
ob_start();

?>

<h2>Борщ</h2>
<p><strong>Категория:</strong> Первое блюдо</p>
<p><strong>Ингредиенты:</strong> Свёкла, капуста, мясо, картофель</p>
<p><strong>Описание:</strong> Классический украинский борщ</p>
<p><strong>Шаги:</strong></p>
<ol>
    <li>Отварить мясо</li>
    <li>Добавить овощи</li>
    <li>Тушить до готовности</li>
</ol>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
```

Итоговая структура проекта:

![image](screenshots/Screenshot_2.png)

## Задание 3. Подключение к базе данных
1. В файле `src/db.php` реализую функцию подключения к бд через PDO:

```PHP
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
```

2. Данные для подключения к бд храню в `config/db.php`:
```php
<?php
return [
    'host' => 'localhost',
    'port' => '5432',
    'dbname' => 'recipe_book',
    'user' => "postgres",
    'password' => 'password'
];
```

3. Функция подключения `getPDO()` возвращает экземпляр `PDO` и выбрасывает искключения `PDO::ERRMODE_EXCEPTION`

4. На этом этапе добавил в бд несколько категорий в таблицу `categories`, чтобы для добавления рецептов можно было указывать уже существующие в бд категории:
```sql
INSERT INTO categories (name) VALUES
  ('Завтрак'),
  ('Обед'),
  ('Ужин');
```

Теперь их можно использовать для добавления рецептов.

## Задание 4. Реализация CRUD-функциональности
Реализую следующие обработчики: 
- Добавление рецепта (handlers/recipe/create.php); 
- Редактирование рецепта(handlers/recipe/edit.php); 
- Удаление рецепта (handlers/recipe/delete.php).


`handlers/recipe/create.php`:
```php
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
```

`handlers/recipe/edit.php`:
```php
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
```

`handlers/recipe/delete.php`:
```php
<?php
require_once __DIR__ . '/../db.php';

if (isset($_GET['id'])) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("DELETE FROM recipes WHERE id = :id");
    $stmt->execute([':id' => $_GET['id']]);
}

header('Location: /');
exit;
```

Все данные добавляются и берутся из базы данных. 

Также добавилей css стилей, чтобы слегка украсить страницу.

### Добавил базовую валидацию данных на стороне сервера:

Пример:
```php
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
}
```

## Задание 5. Защита от SQL-инъекций
1. В моей лабораторной работе уже используются подготовленные (prepared) выражения:
```php
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
```

Они обеспечивают экранирование всех входных данных перед выполнением запросов.

SQL-инъекции были бы возможны при использовании `query`:
```php
// bad practice
$pdo->query("SELECT * FROM recipes WHERE id = $id");
```

При таком подходе злоумышленник может ввести `id = 1 OR 1=1` и получить все данные из таблицы.

Чтобы избежать этого рекоммендуется использовать `prepare() + execute()`, как в лабораторной работе.

## Задание 6. Документация
Код был задокументирован используя стандарт `PHPDoc`. В каждом файле присутствует входные и выходные параметры, описание функционала. Комментарии были сделаны с упором на прозрачность и четкость для обеспечения понимания работы кода другим разработчикам.

## Контрольные вопросы
1. Какие преимущества даёт использование единой точки входа в веб-приложении?
- Централизованная маршрутизация
- Упрощённая обработка ошибок
- Единая настройка (сессии, подключение к БД, авторизация)
- Безопасность: можно закрыть прямой доступ к другим файлам

2. Какие преимущества даёт использование шаблонов?
- Разделение логики и представления (`MVC`)
- Повторное использование структуры (`layout`, `header`, `footer`)
- Упрощение поддержки и редизайна
- Читаемость и чистота кода

3. Какие преимущества даёт хранение данных в базе по сравнению с хранением в файлах?
- Быстрый поиск и фильтрация (через `SQL`)
- Безопасность и контроль доступа
- Масштабируемость (большие объёмы данных)
- Поддержка транзакций и связей между таблицами

4. Что такое SQL-инъекция? Придумайте пример SQL-инъекции и объясните, как её предотвратить.

`SQL-инъекция` — это атака, при которой злоумышленник внедряет вредоносный SQL-код через пользовательский ввод.

Пример: 
```php
$id = $_GET['id'];
$pdo->query("SELECT * FROM users WHERE id = $id");
```
Если в запрос подставить `?id=1 OR 1=1`

запрос станет:

```sql
SELECT * FROM users WHERE id = 1 OR 1=1
```

и вернет всех пользователей базы данных.

Предотвратить можно с помощью `prepare() и execute()`:
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $id]);
```