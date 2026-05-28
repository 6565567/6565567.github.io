<?php

/** @var \App\Model\Pizza[] $pizzas */
/** @var \App\Service\Router $router */

$title = 'Pizza List';
$bodyClass = 'index';

ob_start(); ?>
    <h1>Pizzas List</h1>

    <a href="<?= $router->generatePath('pizza-create') ?>">Create new</a>

    <ul class="index-list">
        <?php foreach ($pizzas as $pizza): ?>
            <li><h3><?= $pizza->getName() ?></h3>
                <ul class="action-list">
                    <li><a href="<?= $router->generatePath('pizza-show', ['id' => $pizza->getId()]) ?>">Details</a></li>
                    <li><a href="<?= $router->generatePath('pizza-edit', ['id' => $pizza->getId()]) ?>">Edit</a></li>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';