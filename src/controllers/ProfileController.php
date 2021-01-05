<?php


namespace MyWishlist\controllers;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProfileController extends BaseController
{

    public function getDashboard( ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->container->view->render($response, 'dashboard.twig', ['username' => $this->container->auth->getUsername()]);
    }

    public function disconnect( ServerRequestInterface $request, ResponseInterface $response)
    {
        unset($_SESSION['user']);
        return $response->withRedirect($this->container->router->pathFor('login'));
    }
}