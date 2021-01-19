<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use wishlist\models\Item;

use wishlist\controleur\MainControleur;
use wishlist\controleur\UtilisateurControleur;
use wishlist\controleur\ListeControleur;
use wishlist\controleur\ItemControleur;

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

$app->get('/infoListe/{tokenModif}', ListeControleur::class . ':donneInfoListe')->setName('infoListe');

$app->get('/formModificationListe/{tokenModif}', ListeControleur::class . ':modificationListe')->setName('formModificationListe');
$app->post('/modificationListe/{tokenModif}', ListeControleur::class . ':modifierListe')->setName('modificationListe');

$app->get('/supprimerListe/{tokenModif}', ListeControleur::class . ':supprimerListe')->setName('supprimerListe');

$app->post('/ajouterCommentaire/{token}', ListeControleur::class . ':ajouterCommentaire')->setName('ajouterCommentaire');

$app->get('/mesListes', ListeControleur::class . ':afficherMesListes')->setName('mesListes');
$app->get('/mesListesNonExpire', ListeControleur::class . ':afficherMesListesNonExpire')->setName('mesListesNonExpire');
$app->get('/mesListesExpire', ListeControleur::class . ':afficherMesListesExpire')->setName('mesListesExpire');

$app->get('/formReservation/{token}/{id}', ItemControleur::class . ':reservation')->setName('formReservation');
$app->post('/reservation/{token}/{id}', ItemControleur::class . ':reserver')->setName('reservation');

$app->get('/formAjoutItem/{tokenModif}/{id}', ItemControleur::class . ':ajouterItem')->setName('formAjoutItem');
$app->post('/ajoutItem/{tokenModif}/{id}', ItemControleur::class . ':ajoutItem')->setName('ajoutItem');

$app->get('/formModificationItem/{tokenModif}/{id}', ItemControleur::class . ':modificationItem')->setName('formModificationItem');
$app->post('/modificationItem/{tokenModif}/{id}', ItemControleur::class . ':modifierItem')->setName('modificationItem');

$app->get('/supprimerItem/{tokenModif}/{id}', ItemControleur::class . ':supprimerItem')->setName('supprimerItem');



$app->run();