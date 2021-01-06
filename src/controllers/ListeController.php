<?php


namespace MyWishlist\controllers;

use MyWishlist\models\Item;
use MyWishlist\models\Liste;


class ListeController extends BaseController
{

    protected $idListe;

    public function afficherListe($request, $response, $args){
        $this->idListe = $args['token'];

        $this->idListe = 1;

        $liste = Liste::find($this->idListe);
        $items = Item::all()->where('liste_id','=',$this->idListe);

        $estProprietaire = false;

        if($this->container->auth->isSigned()){
            if($liste->user_id = $this->container->auth->getUser()->id){
                $estProprietaire = true;
            }
        }


        return $this->container->view->render($response, 'ListeItems.twig', [
            'titreListe' => $liste->titre,
            'descriptionListe' => $liste->description,
            'itemsListe' => $items->toArray(),
            'estProprietaire' => $estProprietaire,
            'isSigned' => $this->container->auth->isSigned(),
        ]);
    }
}