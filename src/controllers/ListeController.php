<?php


namespace MyWishlist\controllers;

use MyWishlist\models\Item;
use MyWishlist\models\Liste;


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

        $liste = Liste::all()->where('token', '=', $tokenliste)->first();
        $items = Item::all()->where('liste_id','=',$liste->no);

        return $this->container->view->render($response, 'ListeItems.twig', [
            'tokenliste' => $tokenliste,
            'titreListe' => $liste->titre,
            'descriptionListe' => $liste->description,
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

        $idListe = (int)$args['token'];

        $liste = Liste::find($idListe);

        $estProprietaire = false;

        if($this->container->auth->isSigned()){
            if($liste->user_id == $this->container->auth->getUser()->id){
                $estProprietaire = true;
            }
        }

        return $estProprietaire;
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

        $detailItem = Item::find($idItem);


        return $this->container->view->render($response, 'detailItem.twig', [
            'item' => $detailItem->toArray(),
            'estProprietaire' => $this->estProprietaire($args),
            'isSigned' => $this->container->auth->isSigned(),
        ]);
    }
}