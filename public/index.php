
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
