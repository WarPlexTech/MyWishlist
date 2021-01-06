<?php

session_start();

use MyWishlist\utils\Authentication;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

require_once __DIR__."/../vendor/autoload.php";

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => parse_ini_file(__DIR__.'/conf/conf.ini'),
    ],
]);

$container = $app->getContainer();

//Chargement de eloquent
$eloquentcapsule = new \Illuminate\Database\Capsule\Manager;
$eloquentcapsule->addConnection($container['settings']['db']);
$eloquentcapsule->setAsGlobal();
$eloquentcapsule->bootEloquent();

//Initialisation des differents elements necessaires
$container['db'] = function ($container) use ($eloquentcapsule) {
    return $eloquentcapsule;
};

$container['auth'] = function () {
    return new Authentication();
};

$container['view'] = function ($container) {
    $view = new Twig(__DIR__.'/../templates', ['cache' => false]);
    $router = $container->router;
    $uri = $container->request->getUri();
    $view->addExtension(new TwigExtension($router, $uri));
    $view->getEnvironment()->addGlobal('auth', $container->auth);
    return $view;
};


//Controllers
$container['AuthenticationController'] = function ($container){
    return new \MyWishlist\controllers\AuthenticationController($container);
};

$container['ProfileController'] = function ($container){
    return new \MyWishlist\controllers\ProfileController($container);
};

$container['ListeController'] = function ($container){
    return new \MyWishlist\controllers\ListeController($container);
};

require __DIR__.'/slimroutes.php';