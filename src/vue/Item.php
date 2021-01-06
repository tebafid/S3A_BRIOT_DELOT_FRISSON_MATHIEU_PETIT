<?php


namespace projet\Vue;

class Item extends Vue{

    protected $liste,$item,$role;

    public function __construct($role,$liste,$item){
        $this->item-$item;
        $this->liste-$liste;
        $this->role-$role;
    }

    private function menuParticipations() : String {

        $url_items = $this -> container -> router -> pathFor ( 'afficheritems' );
        $url_itemsexpire = $this -> container -> router -> pathFor ( 'afficheritemsexpire' );

        $html = <<<end
    <div class="vertical-menu">
    <a class="active">Mes Participations</a>
    <a href="$url_items">Mes cadeaux à achetés</a>
    <a href="$url_itemsexpire">Mes cadeaux passées</a>
    </div>
end;
        return $html;
    }
    /**
     * affiche la création de l'item
     * @return string
     */
    public static function creerItem(){
        return <<<end
        <h3>Ajout Cadeau</h3>
        <form method ='post' action = ''>
        <p>Titre : <input type ='text' name ="titre"></p>
        <p> Description : <input type ='text' name = "descr"></p>
        <p> Prix : <input ="number" value ="prix"></p>
        <input type ="submit" value ="Ajouter">
        </form>
        end;
    }

    /**
     * affiche item sans détail
     * @return string
     *
     */
    public function afficherItem(){
        $app = \Slim\Slim::getInstance();

        if($this->role==OFFREUR) {
            $url = $app->urlFor("consulter_item", array("name" => $this->liste->token, "id" => $this->item->id));
        }
        else{
            $url = $app->urlFor("voir_item", array("name" => $this->liste->token, "id" => $this->item->id));
        }

        return <<<end
        <div><h4><a href ="$url">{$this->item->nom}</a></h4>
        <p>{$this->item->descrip}</p>
        <p>{$this->item->tarif}</p>
        </div>
        end;
    }

    /**
     * L'affichage d'un item présente toutes ses
     * informations détaillées, son image,
     * et l'état de la réservation
     * (nom du participant sans message)
     * @return string
     */
    public function afficherItemDetail(){
        $dispo = empty($this->item->acquereur);
        if($dispo){
            if($this->role==OFFREUR){
                $txt =<<<end
                <form method = "post" action ="">
                <p>Votre nom::<input name="acquereur" type=""text"></p>
                <p>Message: >textarea name="message"></p>
                <input type=""submit" value="choisir">
                </form>
                end;
            }
        }
        else{
            if($this->role==OFFREUR){
                $txt ="<p>Cet item a été choisi par {$this->item->acquereur}</p>";
            }
            else{
                $txt="<p>Cet item a été choisi!</p>";
            }
        }
        return <<<end
        <div><h3>{$this->item->nom}</h3>
        <p>{$this->item->descriptif}</p>
        <p>{$this->item->tarif}</p>
        $txt
        </div>
        end;
    }

    /**
     * permet d'ajouter un item a la liste
     * @return string
     */
    public function ajouterItem(){
        $url = $this -> container -> router -> pathFor ( 'ajouteritem', ['tokenModif' => Liste::find($this->tab['no'])->tokenModif, 'no' => $this->tab['no']] );
        $html = "<h1>Ajouter un item a la liste {$this->tab['titre']}</h1>";
        $html .= <<<end
    <form method="POST" action="$url">
	<label>Nom :<br> <input type="text" name="nom"/></label><br>
	<label>Description : <br><input type="text" name="descr"/></label><br>
	<label>Tarif : <br><input type="text" name="tarif"/></label><br>
	<label>Url (site) : <br><input type="text" name="url"/></label><br>
	<button class="button" type="submit">Ajouter l'item</button>
    </form>	
end;
        return $html;

    }






    /**
     * fonction qui fait le rendu en fonction de l'action de l'utilisateur
     * @return string
     */
    public function render(int $select)
    {
        switch ($select) {
            case 0 :
            {
                $content = $this->reserver();
                break;
            }
            case 1:
            {
                $content = $this->afficherItem();
                break;
            }
            case 2:
            {
                $content = $this->afficherItemDetail();
                break;
            }

        }
        Vue::$inMenu = $this->menuParticipations();
        Vue::$content = $content;

        return substr(include("html/index.php"), 1, -1);

    }



    //Il manque le non affichage des items
    // appartenant à aucune liste validée
    // (par son créateur) ne peut pas être affiché

}
