<?php


namespace wishlist\vue;

class UtilisateurVue extends MainVue
{
    public function __construct($c){
        $this->container = $c;
    }

    /**
     * affiche la création de compte
     * @return string
     */
    private function getHtmlCreationCompte(){
        $actionCreation = $this->container->router->pathFor('creationCompte');
        $html = <<<END
<form method="POST" action="$actionCreation">
    <div>
        <label for="nom">Nom : </label>
        <input type="text" name="nom" required/>
    </div>
    <div>
        <label for="prenom">Prenom : </label>
        <input type="text" name="prenom" required/>
    </div>
    <div>
        <label for="login">Identifiant : </label>
        <input type="text" name="login" required/>
    </div>
    <div>
        <label for="password">Mot de passe : </label>
        <input type="password" name="password" required/>
    </div>
    <div>
	    <button class="button" type="submit">S'inscrire</button>
	</div>
</form>
END;
        return $html;
    }

    /**
     * affiche la création d'un compte existant
     * @return string
     */
    private function getHtmlCreationCompteUtilisateurExistant(){
        $actionCreation = $this->container->router->pathFor('creationCompte');
        $html = <<<END
<form method="POST" action="$actionCreation">
    <div>
        <label>Nom : </label>
        <input type="text" name="nom" required/>
    </div>
    <div>
        <label>Prenom : </label>
        <input type="text" name="prenom" required/>
    </div>
    <div>
        <label>Identifiant : </label>
        <input type="text" name="login" required/>
    </div>
    <div class="error">
        Cet utilisateur existe déjà
    </div>
    <div>
        <label>Mot de passe : </label>
        <input type="password" name="password" required/>
    </div>
    <div>
	    <button id="button" class="button" type="submit">S'inscrire</button>
	</div>
</form>
END;
        return $html;
    }

    /**
     * affiche la connexion
     * @return string
     */
    private function getHtmlConnexion(){
        $actionConnexion = $this->container->router->pathFor('connexion');
        $html = <<<END
<form method="POST" action="$actionConnexion">
    <div>
        <label for="login">Identifiant : </label>
        <input type="text" name="login" required/>
    </div>
    <div>
        <label for="password">Mot de passe : </label>
        <input type="password" name="password" required/>
    </div>
    <div>
	    <button id="button" class="button" type="submit">Se connecter</button>
	</div>
</form>
END;
        return $html;
    }

    /**
     * affiche une erreur de connexion : utilisateur inconnu
     * @return string
     */
    private function getHtmlErreurConnexion(){
        $html = <<<END
    <div class="error">
        L'utilisateur ou le mot de passe est erronné
    </div>
END;
        return $html;
    }

    /**
     * rendu
     * @param int $i
     * @return String
     */
    public function render(int $i) : String {
        switch ($i){
            case 0: // form de la creation du compte
                MainVue::$content = $this->getHtmlCreationCompte();
                break;
            case 1: // compte créé
                MainVue::$content = "Votre compte a bien été créé.";
                break;
            case 2: // form de la creation du compte si l'utilisateur existe deja
                MainVue::$content = $this->getHtmlCreationCompteUtilisateurExistant();
                break;
            case 3: // form de la connexion au compte
                MainVue::$content = $this->getHtmlConnexion();
                break;
            case 4: // form de la connexion au compte si erreur de connection
                MainVue::$content = $this->getHtmlConnexion() . $this->getHtmlErreurConnexion();
                break;
        }

        return substr(include ("html/index.php"), 0 , -1);
    }
}