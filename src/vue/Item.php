<?php


namespace projet\Vue;

class Item extends Vue{

    protected $liste,$item,$role;

    public function __construct($role,$liste,$item){
        $this->item-$item;
        $this->liste-$liste;
        $this->role-$role;
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
     * fonction qui utilise l'affichage des items
     * @return string
     */
    public function render(){
        $app = \Slim\Slim::getInstance();
        $url = $app->urlFor($this->role == OFFREUR ? 'consulter_liste' : 'voir_liste',array('name'=>$this->liste->token));
        $this->html = $this->afficherItemDetail();
        $this->menu ="<a href= '$url'>Liste{$this->liste->titre}</a>";
        return parent::render();
    }
    //Il manque le non affichage des items
    // appartenant à aucune liste validée
    // (par son créateur) ne peut pas être affiché

}
