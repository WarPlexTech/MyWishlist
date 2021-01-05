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
     * Permet de retourner l'utilisateur
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|Account|Account[]|object|null
     */
    public function getUser()
    {
        if(!$this->isSigned()) return null;

        return Account::find($_SESSION['user']);
    }

}