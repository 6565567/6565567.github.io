<?php

/** @var \App\Model\Pizza $pizza */
/** @var \App\Service\Router $router */

$title = 'Create Pizza';
$bodyClass = "edit";

ob_start(); ?>
    <h1>Create Pizza</h1>
    <form action="<?= $router->generatePath('pizza-create') ?>" method="post" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
        <input type="hidden" name="action" value="pizza-create">
    </form>

    <a href="<?= $router->generatePath('pizza-index') ?>">Back to list</a>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';