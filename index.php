<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use wishlist\models\Item;

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

$app = new \Slim\App();
/*
$app->get('/hello/{name}[/]', function (Request $rq, Response $rs, array $args): Response {
    $name = $args['name'];
    $rs->getBody()->write("<h1>hello world, $name</h1>");
    return $rs;
});*/

$app->get('/lists', function () {
    $control = new \wishlist\controleur\Liste();
    $control->afficherListe();
});


$app->run();