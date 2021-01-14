<?php


namespace MyWishlist\controllers;

use MyWishlist\models\Item;
use MyWishlist\models\Liste;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class ListeController extends BaseController
{

    /**
     * Fonction permettant d'afficher la liste avec des informations de base
     * @param $request
     * @param $response
     * @param $args argument(s) passes grace a l'url, ici l'id de la liste
     * @return mixed rendu de l'affichage de la liste
     */
    public function afficherListe($request, $response, $args){

        $tokenliste = $args['token'];

        $liste = Liste::all()->where('token',"=",$args['token'])->first();
        $items = Item::all()->where('liste_id','=',$liste->no);
        $liste = $this->processList($liste);

        return $this->container->view->render($response, 'ListeItems.twig', [
            'liste' => $liste,
            'itemsListe' => $items->toArray(),
            'estProprietaire' => $this->estProprietaire($args),
            'isSigned' => $this->container->auth->isSigned(),
        ]);
    }

    /**
     * Fonction permettant de savoir si l'utilisateur consultant la liste est le proprietaire de cette derniere
     * (afin que le proprietaire ne voit pas qui a reserve le cadeau)
     * @param $args argument(s) passes dans l'url, ici l'id de la liste
     * @return bool true si l'utilisateur qui consulte est le proprietaire
     */
    public function estProprietaire($args){

        $tokenliste = (int)$args['token'];

        $liste = Liste::all()->where('token', '=', $tokenliste)->first();

        $estProprietaire = false;

        if($this->container->auth->isSigned()){
            if($liste->user_id == $this->container->auth->getUser()->id){
                $estProprietaire = true;
            }
        }

        return $estProprietaire;
    }

    /**
     * Permet de transformer une liste venant d'une BDD en array tout en lui ajoutant l'information de l'etat d'expiration
     * @param $inputList
     * @return mixed
     */
    public function processList($inputList){
        $liste = $inputList->toArray();
        $liste['isExpired'] = date("Y-m-d H:i:s") >= $inputList->expiration;
        return $liste;
    }

    /**
     * Fonction permettant un affichage detaille des items presents dans une liste en cliquant ces derniers
     * @param $request
     * @param $response
     * @param $args argument(s) passes dans l'url, ici l'id de l'item
     * @return mixed rendu de l'affichage des informations detaillees de l'item
     */
    public function afficherDetailItem($request, $response, $args){

        $idItem = (int)$args['item'];
        $liste = Liste::all()->where('token',"=",$args['token'])->first();
        $liste = $this->processList($liste);
        $detailItem = Item::find($idItem);


        return $this->container->view->render($response, 'detailItem.twig', [
            'liste' => $liste,
            'item' => $detailItem->toArray(),
            'estProprietaire' => $this->estProprietaire($args),
            'isSigned' => $this->container->auth->isSigned(),
        ]);
    }

    public function reserverItem(RequestInterface $request, $response, $args){

        $idItem = (int)$args['item'];

        $item = Item::find($idItem);

        if(! $this->container->auth->isSigned()){
            return $response->withRedirect($this->container->router->pathFor('login', [], ['redirect' => $this->container->router->pathFor('reservation',['token' => $args['token'], 'item' => $args['item']])]));
        }

        if($this->estProprietaire($args) || $item->reserve){
            return $response->withRedirect($this->container->router->pathFor('login'));
        }

        return $this->container->view->render($response, 'reserverItem.twig', [
            'item' => $item->toArray(),
            'isSigned' => $this->container->auth->isSigned(),
        ]);
        }

        public function getCreerListe(ServerRequestInterface $request, ResponseInterface $response){
            return $this->container->view->render($response, 'creerListe.twig');
        }

        public function postCreerListe($request, $response, $args){

        $idUtilisateur= $_SESSION['user'];

        $token = str_replace('.', "", uniqid("ls-", true));

        while (Liste::all()->where('token','=', $token)->first()){
            $token = str_replace('.', "", uniqid("ls-", true));
        }

        if($request->getParsedBody()['expiration'] <= date("Y-m-d H:i:s")){
            //TODO
        }

        if($request->getParsedBody()['titre'] ==""){
            return $this->container->view->render($response, 'creerListe.twig', ['error' => 'Veuillez mettre un titre']);
        }

        if($request->getParsedBody()['description'] ==""){
            return $this->container->view->render($response, 'creerListe.twig', ['error' => 'Veuillez mettre une description']);
        }

        if($request->getParsedBody()['expiration'] ==""){
            return $this->container->view->render($response, 'creerListe.twig', ['error' => 'Veuillez mettre une date d\'expiration']);
        }


        $nouvelleListe = liste::create([
            'user_id' => $idUtilisateur,
            'titre' => $request->getParsedBody()['titre'],
            'description' => $request->getParsedBody()['description'],
            'expiration' => $request->getParsedBody()['expiration'],
            'token' => $token,
        ]);


        return $response->withRedirect($this->container->router->pathFor('liste', [
                'token' => $token
            ]));
        }
}