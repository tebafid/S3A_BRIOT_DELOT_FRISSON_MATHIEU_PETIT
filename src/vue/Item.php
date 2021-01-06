<?php


namespace wishlist\vue;

class Item extends Vue{

    protected $liste,$item,$role;

    public function __construct($role,$liste,$item){
        $this->item-$item;
        $this->liste-$liste;
        $this->role-$role;
    }

    public static function creerItem(){
        return <<<ez
        <h3>Ajout Cadeau</h3>
        <form method ='post' action = ''>
        <p>Titre : <input type ='text' name ="titre"></p>
        <p> Description : <input type ='text' name = "descr"></p>
        <p> Prix : <input ="number" value ="prix"></p>
        <input type ="submit" value ="Ajouter">
        </form>
        ez;
    }

    public function afficherItem(){
        $app = \Slim\Slim::getInstance();

        if($this->role==OFFREUR) {
            $url = $app->urlFor("consulter_item", array("name" => $this->liste->token, "id" => $this->item->id));
        }
        else{
            $url = $app->urlFor("voir_item", array("name" => $this->liste->token, "id" => $this->item->id));
        }

        return <<<ez
        <div><h4><a href ="$url">{$this->item->nom}</a></h4>
        <p>{$this->item->descrip}</p>
        <p>{$this->item->tarif}</p>
        </div>
        ez;
    }

    public function afficherItemDetail(){
        $dispo = empty($this->item->acquereur);
        if($dispo){
            if($this->role==OFFREUR){
                $txt =<<<ez
                <form method = "post" action ="">
                <p>Votre nom::<input name="acquereur" type=""text"></p>
                <p>Message: >textarea name="message"></p>
                <input type=""submit" value="choisir">
                </form>
                ez;
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
        return <<<ez
        <div><h3>{$this->item->nom}</h3>
        <p>{$this->item->descriptif}</p>
        <p>{$this->item->tarif}</p>
        $txt
        </div>
        ez;
    }


    public function render(){
        $app = \Slim\Slim::getInstance();
        $url = $app->urlFor($this->role == OFFREUR ? 'consulter_liste' : 'voir_liste',array('name'=>$this->liste->token));
        $this->html = $this->afficherItemDetail();
        $this->menu ="<a href= '$url'>Liste{$this->liste->titre}</a>";
        return parent::render();
    }
}
