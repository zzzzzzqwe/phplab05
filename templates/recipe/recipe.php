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
