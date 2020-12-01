<?php

require "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as BD;

$db = new DB();

print("Eloquent est installé !\n");

$config = parse_ini_file('conf.ini');

$db->addConnection($config);

$db->setAsGlobal();
$db->bootEloquent();

print("Connecté à la base de données\n");

