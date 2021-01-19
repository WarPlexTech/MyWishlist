<?php


namespace MyWishlist\controllers;


use MyWishlist\models\Account;
use MyWishlist\models\Liste;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProfileController extends BaseController
{

    public function getDashboard( ServerRequestInterface $request, ResponseInterface $response)
    {
        $id = $_SESSION['user'];
        $lists = Liste::all()->where('user_id','=', $id);

        return $this->container->view->render($response, 'profile/dashboard.twig', [
            'lists' => $lists->toArray(),
        ]);
    }

    public function disconnect( ServerRequestInterface $request, ResponseInterface $response)
    {
        unset($_SESSION['user']);
        return $response->withRedirect($this->container->router->pathFor('login'));
    }

    public function getSettings(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->container->view->render($response, 'profile/settings.twig', ['username' => $this->container->auth->getUsername()]);
    }

    public function postSettings(ServerRequestInterface  $request, ResponseInterface $response){
        if(isset($request->getParsedBody()['deleteAccount']))
        {
            Account::find($_SESSION['user'])->delete();
            return $this->disconnect($request, $response);
            //TODO: SUPPRIMER LES LISTES ET ITEMS LIES (n'est pas demandé dans les consignes, cette fonctionnalité a donc été abandonné afin de se concentrer sur d'autres jugées plus pertinantes)
        }
        elseif(isset($request->getParsedBody()['changePassword']))
        {
            //Verifie que l'ancien mot de passe est valide
            if(!$this->container->auth->checkPassword($request->getParsedBody()['password_old'])){
                return $this->container->view->render($response, 'profile/settings.twig', ['username' => $this->container->auth->getUsername(), 'passerror' => 'Le mot de passe actuel est incorrect']);
            }

            //Verifie que les deux nouveau mots de passes sont bien les memes
            if($request->getParsedBody()['password_new1'] != $request->getParsedBody()['password_new2']){
                return $this->container->view->render($response, 'profile/settings.twig', ['username' => $this->container->auth->getUsername(), 'passerror' => 'La comfirmation du mot de passe n\'est pas valide']);
            }

            //On recupere le compte a modifier
            $temp = Account::find($_SESSION['user']);

            $temp->password = password_hash($request->getParsedBody()['password_new1'], PASSWORD_DEFAULT);

            $temp->save();
        }
        return $this->container->view->render($response, 'profile/settings.twig', ['username' => $this->container->auth->getUsername(), 'passsuccess' => 'Mot de passe changé avec succès']);
    }
}