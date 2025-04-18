<?php
/**
 * Основной роутер приложения.
 *
 * Обрабатывает маршруты по URL и методам HTTP.
*/
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

switch (true) {
    case $request === '/' || $request === '/index':
        require_once '../templates/index.php';
        break;

    case $request === '/create' && $method === 'GET':
        require_once '../templates/recipe/create.php';
        break;

    case $request === '/create' && $method === 'POST':
        require_once '../src/handlers/recipe/create.php';
        break;

    case str_starts_with($request, '/edit'):
        require_once '../src/handlers/recipe/edit.php';
        break;

    case str_starts_with($request, '/delete'):
        require_once '../src/handlers/recipe/delete.php';
        break;

    case str_starts_with($request, '/recipe'):
        require_once '../templates/recipe/show.php';
        break;

    default:
        http_response_code(404);
        echo '<h1>404 — Страница не найдена</h1>';
        break;
}
