<?php
$title = 'Добавить рецепт';
ob_start();
?>

<h2>Добавить рецепт</h2>

<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="/create" method="POST">
    <label>Название:<br>
        <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
    </label><br>

    <label>Категория:<br>
        <input type="number" name="category" value="<?= htmlspecialchars($_POST['category'] ?? '') ?>">
    </label><br>

    <label>Ингредиенты:<br>
        <textarea name="ingredients"><?= htmlspecialchars($_POST['ingredients'] ?? '') ?></textarea>
    </label><br>

    <label>Описание:<br>
        <textarea name="description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
    </label><br>

    <label>Шаги:<br>
        <textarea name="steps"><?= htmlspecialchars($_POST['steps'] ?? '') ?></textarea>
    </label><br>

    <label>Теги:<br>
        <input type="text" name="tags" value="<?= htmlspecialchars($_POST['tags'] ?? '') ?>">
    </label><br><br>

    <button type="submit">Сохранить</button>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
