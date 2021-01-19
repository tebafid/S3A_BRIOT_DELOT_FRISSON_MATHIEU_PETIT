<?php


namespace wishlist\vue;


class MainVue
{
    protected $container;
    protected static $content;

    public function __construct($c){
        $this->container = $c;
    }

    private function getHtmlAccueil(){
        $refCreationListe = $this->container->router->pathFor('formCreationListe');

        $html = "<h1> Bienvenue sur my MyWishList </h1>";
        $html .= <<<END
<div><h3>
MyWishList.app est une application en ligne pour créer, partager et gérer des listes de cadeaux. <br/>
L'application permet à un utilisateur de créer une liste de souhaits à l'occasion d'un événement
particulier (anniversaire, fin d'année, mariage, retraite …) et lui permet de diffuser cette liste de
souhaits à un ensemble de personnes concernées. <br/><br/>
Ces personnes peuvent alors consulter cette liste et s'engager à offrir 1 élément de la liste. <br/>
Cet élément est alors marqué comme réservé dans cette liste.<br/> <br/>
Vous pouvez commencer tout de suite en créant une liste ci-dessous :
</h3></div>
END;
        $html .= "<div><a class='button' href='$refCreationListe'>Créer une liste</a></div>";

        return $html;
    }

    /**
     * rendu vue
     * @param int $i
     * @return String
     */
    public function render(int $i) : String {
        MainVue::$content = $this->getHtmlAccueil();

        return substr(include ("html/index.php"), 0,-1);
    }

    public static function getContent(): String {
        return MainVue::$content;
    }
}