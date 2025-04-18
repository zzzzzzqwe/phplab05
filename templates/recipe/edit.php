<?php
$title = 'Редактировать рецепт';
ob_start();
?>

<?php if (!$recipe): ?>
    <p>Рецепт не найден</p>
<?php else: ?>

    <h2>Редактировать рецепт</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="/edit" method="POST">
        <input type="hidden" name="id" value="<?= $recipe['id'] ?>">

        <label>Название:<br>
            <input type="text" name="title" value="<?= htmlspecialchars($recipe['title']) ?>" required>
        </label><br>

        <label>Категория:<br>
            <input type="number" name="category" value="<?= htmlspecialchars($recipe['category']) ?>">
        </label><br>

        <label>Ингредиенты:<br>
            <textarea name="ingredients"><?= htmlspecialchars($recipe['ingredients']) ?></textarea>
        </label><br>

        <label>Описание:<br>
            <textarea name="description"><?= htmlspecialchars($recipe['description']) ?></textarea>
        </label><br>

        <label>Теги:<br>
            <input type="text" name="tags" value="<?= htmlspecialchars($recipe['tags']) ?>">
        </label><br>

        <label>Шаги:<br>
            <textarea name="steps"><?= htmlspecialchars($recipe['steps']) ?></textarea>
        </label><br><br>

        <button type="submit">Сохранить изменения</button>
    </form>

<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
