<?php


namespace MyWishlist\controllers;

use MyWishlist\models\Item;
use MyWishlist\models\Liste;


class ListeController extends BaseController
{

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

    public function estProprietaire($args){

        $idListe = (int)$args['token'];

        $liste = Liste::find($idListe);

        $estProprietaire = false;

        if($this->container->auth->isSigned()){
            if($liste->user_id = $this->container->auth->getUser()->id){
                $estProprietaire = true;
            }
        }

        return $estProprietaire;
    }

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