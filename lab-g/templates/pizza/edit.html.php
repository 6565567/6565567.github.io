<?php

/** @var \App\Model\Pizza $pizza */
/** @var \App\Service\Router $router */

$title = "Edit Pizza {$pizza->getName()} ({$pizza->getId()})";
$bodyClass = "edit";

ob_start(); ?>
    <h1><?= $title ?></h1>
    <form action="<?= $router->generatePath('pizza-edit') ?>" method="post" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
        <input type="hidden" name="action" value="pizza-edit">
        <input type="hidden" name="id" value="<?= $pizza->getId() ?>">
    </form>

    <ul class="action-list">
        <li>
            <a href="<?= $router->generatePath('pizza-index') ?>">Back to list</a></li>
        <li>
            <form action="<?= $router->generatePath('pizza-delete') ?>" method="post">
                <input type="submit" value="Delete" onclick="return confirm('Are you sure?')">
                <input type="hidden" name="action" value="pizza-delete">
                <input type="hidden" name="id" value="<?= $pizza->getId() ?>">
            </form>
        </li>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';