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
