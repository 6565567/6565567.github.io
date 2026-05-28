<?php
namespace App\Controller;
use App\Exception\NotFoundException;
use App\Model\Pizza;
use App\Service\Router;
use App\Service\Templating;

class PizzaController
{
    public function indexAction(Templating $templating, Router $router): ?string {
        $pizzas = Pizza::findAll();
        $html = $templating->render('pizza/index.html.php', [
            'pizzas' => $pizzas,
            'router' => $router,
        ]);
        return $html;
    }

    public function createAction(?array $requestPost, Templating $templating, Router $router): ?string {
        if ($requestPost) {
            $pizza = Pizza::fromArray($requestPost);
            $pizza->save();
            $path = $router->generatePath('pizza-index');
            $router->redirect($path);
            return null;
        } else {
            $pizza = new Pizza();
        }
        $html = $templating->render('pizza/create.html.php', [
            'pizza' => $pizza,
            'router' => $router,
        ]);
        return $html;
    }

    public function editAction(int $pizzaId, ?array $requestPost, Templating $templating, Router $router): ?string {
        $pizza = Pizza::find($pizzaId);
        if (! $pizza) {
            throw new NotFoundException("Missing pizza with id $pizzaId");
        }
        if ($requestPost) {
            $pizza->fill($requestPost);
            $pizza->save();
            $path = $router->generatePath('pizza-index');
            $router->redirect($path);
            return null;
        }
        $html = $templating->render('pizza/edit.html.php', [
            'pizza' => $pizza,
            'router' => $router,
        ]);
        return $html;
    }

    public function showAction(int $pizzaId, Templating $templating, Router $router): ?string {
        $pizza = Pizza::find($pizzaId);
        if (! $pizza) {
            throw new NotFoundException("Missing pizza with id $pizzaId");
        }
        $html = $templating->render('pizza/show.html.php', [
            'pizza' => $pizza,
            'router' => $router,
        ]);
        return $html;
    }

    public function deleteAction(int $pizzaId, Router $router): ?string {
        $pizza = Pizza::find($pizzaId);
        if (! $pizza) {
            throw new NotFoundException("Missing pizza with id $pizzaId");
        }
        $pizza->delete();
        $path = $router->generatePath('pizza-index');
        $router->redirect($path);
        return null;
    }
}