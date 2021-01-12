<?php


namespace MyWishlist\utils;


use MyWishlist\models\Account;

/**
 * Class Authentication
 * @package MyWishlist\utils
 */
class Authentication
{

    /**
     * Permet de savoir si l'utilisateur est connecté ou non
     * @return bool
     */
    public function isSigned(): bool
    {
        if(isset($_SESSION['user'])) return true;
        return false;
    }

    /**
     * Permet de retourner le nom d'utilisateur
     */
    public function getUsername()
    {
        if(!$this->isSigned()) return null;
        return $this->getUser()->username;
    }

    /**
     * Permet de verifier si un mot de passe donne est le bon
     * @param $password
     * @return bool
     */
    public function checkPassword($password)
    {
        return password_verify($password, $this->getUser()->password);
    }

    /**
     * Permet de retourner l'utilisateur
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|Account|Account[]|object|null
     */
    public function getUser()
    {
        if(!$this->isSigned()) return null;

        return Account::find($_SESSION['user']);
    }

    /**
     * Permet de convertir un id de reservation en une chaine lisible
     */
    public function translateItemReserve($id, $listExpired = false)
    {
        if(!$listExpired)
        {
            if($id == 0) return 'Cadeau non reservé';
            return 'Réservé par '.Account::find($id)->username;
        }
        if($id == 0) return 'Ce cadeau n\'à pas été offert';
        return "Ce cadeau a été offert par ".Account::find($id)->username;
    }

}