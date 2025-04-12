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
