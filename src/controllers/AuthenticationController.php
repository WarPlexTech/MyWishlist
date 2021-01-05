<?php

namespace MyWishlist\controllers;

use MyWishlist\models\Account;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class HomePageController
 * @package MyWishlist\controllers
 */
class AuthenticationController extends BaseController
{
    public function get(ServerRequestInterface $request, ResponseInterface $response){
        //Si la personne est deja authentifie, la redirige vers son dashboard
        if($this->container->auth->isSigned()) return $response->withRedirect($this->container->router->pathFor('profile.dashboard'));

        return $this->container->view->render($response, 'login.twig');
    }

    public function post(ServerRequestInterface $request, ResponseInterface $response){

        //On verifie que des informations sont bien passe
        if($request->getParsedBody()['username'] == ""){
            return self::errorLoginRedirect($response, 'Veuillez au minimum entrer un nom d\'utilisateur');
        }

        //Recherche d'un eventuel compte dans la base de donnees
        $useraccount = Account::all()->where('username', '=', $request->getParsedBody()['username'])->first();

        //Si le compte est inexistant, on le creer avant de continuer
        if(is_null($useraccount))
        {
            $useraccount = Account::create([
                'username' => $request->getParsedBody()['username'],
                'password' => password_hash($request->getParsedBody()['password'], PASSWORD_DEFAULT)
            ]);
        }
        //Si le compte existe
        else
        {
            //Si le mot de passe est invalide on renvoie une erreur
            if(!password_verify($request->getParsedBody()['password'], $useraccount['password'])){
                return $this->errorLoginRedirect($response, "Mot de passe incorrect (compte existant)");
            }
        }

        $_SESSION['user'] = $useraccount['id'];

        return $response->withRedirect($this->container->router->pathFor('profile.dashboard'));
    }

    private function errorLoginRedirect($response, string $message = 'Une erreur s\'est produite :/'){

        return $this->container->view->render($response, 'login.twig', ['error' => $message]);
    }
}