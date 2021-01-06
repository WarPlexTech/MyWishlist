<?php
/**
 * @var \Slim\App $app
 * @var \Slim\Container $container
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

$app->get('/', 'AuthenticationController:get')->setName('login');
$app->post('/', 'AuthenticationController:post');

$app->get('/liste/{token}', 'ListeController:afficherListe')->setName('liste');

$app->group('/profile', function () use ($app) {
    $app->redirect('/','/profile');
    $app->redirect('', 'profile/dashboard');
    $app->get('/disconnect', 'ProfileController:disconnect')->setName('profile.disconnect');
    $app->get('/dashboard', 'ProfileController:getDashboard')->setName('profile.dashboard');
})->add(new \MyWishlist\middleware\AuthMiddleware($container));