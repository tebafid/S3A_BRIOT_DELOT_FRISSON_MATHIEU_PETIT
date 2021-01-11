<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use wishlist\models\Item;

session_start();

$db = new DB();
$config = parse_ini_file("src/conf/conf.ini");
if($config) $db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();
/*
$items = Item::all();

foreach ($items as $item) echo $item . "<br>";

$id = $_GET['id'];

if($id){
    echo "<br>l'id est $id<br>";
    $item = Item::query()->where("id", "=", $id)->get();
    echo $item;
}*/

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$conf = ['settings' => [
    'displayErrorDetails' => true,
]];

$container = new \Slim\Container($conf);
$app = new \Slim\App($container);

$app->get('/', \wishlist\controleur\MainControleur::class . ':accueil')->setName('accueil');


$app->run();