<?php

require_once __DIR__."/../vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();

$config = parse_ini_file('conf/conf.ini');

$db->addConnection($config);

$db->setAsGlobal();
$db->bootEloquent();
