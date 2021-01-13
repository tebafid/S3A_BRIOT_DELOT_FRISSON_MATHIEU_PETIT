<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use wishlist\models\Item;

use wishlist\controleur\MainControleur;
use wishlist\controleur\UtilisateurControleur;
use wishlist\controleur\ListeControleur;

session_start();

$db = new DB();
$config = parse_ini_file("src/conf/conf.ini");
if($config) $db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

$conf = ['settings' => [
    'displayErrorDetails' => true,
]];

$container = new \Slim\Container($conf);
$app = new \Slim\App($container);

$app->get('/', MainControleur::class . ':accueil')->setName('accueil');

$app->get('/formConnexion', UtilisateurControleur::class . ':connexion')->setName('formConnexion');
$app->post('/connexion', UtilisateurControleur::class . ':seConnecter')->setName('connexion');

$app->get('/formCreationCompte', UtilisateurControleur::class . ':creationCompte')->setName('formCreationCompte');
$app->post('/creationCompte', UtilisateurControleur::class . ':creerCompte')->setName('creationCompte');

$app->get('/deconnexion', UtilisateurControleur::class . ':deconnexion')->setName('deconnexion');

$app->get('/listesPubliques', ListeControleur::class . ':afficherlistesPubliques')->setName('listesPubliques');

$app->get('/liste/{token}', ListeControleur::class . ':afficherListe')->setName('liste');

$app->get('/formCreationListe', ListeControleur::class . ':creationListe')->setName('formCreationListe');
$app->post('/creationListe', ListeControleur::class . ':creerListe')->setName('creationListe');

$app->run();