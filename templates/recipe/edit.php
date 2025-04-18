<?php if (!$recipe): ?>
    <p>Рецепт не найден</p>
<?php else: ?>
    <h2>Редактировать рецепт</h2>
    <form action="/edit" method="POST">
        <input type="hidden" name="id" value="<?= $recipe['id'] ?>">

        <label>Название:<br>
            <input type="text" name="title" value="<?= htmlspecialchars($recipe['title']) ?>" required>
        </label><br>

        <label>Категория (ID):<br>
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
