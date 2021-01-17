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

$app->get('/liste/{token}/ajouterItem','ListeController:getAjouterItem')->setName('ajouterItem');
$app->post('/liste/{token}/ajouterItem', 'ListeController:postAjouterItem');

$app->get('/liste/{token}/{item}/supprimer', 'ListeController:getSupprimerItem')->setName('supprimerItem');

$app->get('/liste/{token}', 'ListeController:afficherListe')->setName('liste');
$app->get('/liste/{token}/{item}', 'ListeController:afficherDetailItem')->setName('detailItem');
$app->get('/liste/{token}/{item}/reservation', 'ListeController:reserverItem')->setName('reservation');



$app->group('/profile', function () use ($app) {
    $app->redirect('/','/profile');
    $app->redirect('', 'profile/dashboard');
    $app->get('/disconnect', 'ProfileController:disconnect')->setName('profile.disconnect');
    $app->get('/dashboard', 'ProfileController:getDashboard')->setName('profile.dashboard');
    $app->get('/settings', 'ProfileController:getSettings')->setName('profile.settings');
    $app->post('/settings', 'ProfileController:postSettings');
    $app->get('/creerliste', 'ListeController:getCreerListe')->setName('creerListe');
    $app->post('/creerliste', 'ListeController:postCreerListe');

})->add(new \MyWishlist\middleware\AuthMiddleware($container));