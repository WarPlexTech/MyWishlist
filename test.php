<?php
require_once "vendor/autoload.php";
use mywishlist\models\Item;
use mywishlist\models\Liste;

print "<br>Item correspondant a l'id passé en parametre<br>=================<br>";
echo $itemkey = Item::all()->where('id', "=", $_GET["id"])->first()['nom'];

print "<br><br>Liste items<br>=================";
foreach (Item::all() as $item){
    print "<br>".$item->id." ".$item->nom." ".$item->reserve;
}
print "<br><br>Liste listes<br>=================";
foreach (Liste::all() as $item){
    print "<br>".$item->no." ".$item->titre." ".$item->reserve;
}