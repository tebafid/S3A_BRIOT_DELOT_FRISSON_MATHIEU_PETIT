
<head>
    <div class="header">
        <?php

        use mywishlist\models\User;

        $url_accueil = $this -> container -> router -> pathFor ( 'accueil' );

        echo "<a href='$url_accueil' class='logo'>MyWishList</a>";

        //$url_rechercher = $this -> container -> router -> pathFor ( 'rechercher' );
/*        echo <<<End
<div class="search-container">
    <form method="POST" action="$url_rechercher" style="margin-left: 2%">
	    <input type="text" name="token" placeholder="Entrer une clé de partage" required/></label>
    </form>	
</div>
End;*/

        ?>
        <div class="header-right">

            <?php
            $refListesPubliques = $this->container->router->pathFor('listesPubliques');
            if (isset( $_SESSION['iduser'] )) {

                //$url_items = $this -> container -> router -> pathFor ( 'afficheritems' );
                //$refListes = $this->container->router->pathFor('afficherListes');

                //$url_comptes = $this -> container -> router -> pathFor ( 'compte', ["login" => User::find($_SESSION['iduser'])->login] );
                $refDeconnexion = $this->container->router->pathFor('deconnexion');

                echo "<a href='$refListesPubliques'>Listes publiques</a>";
                //echo "<a href='$url_items'>Mes Participations</a>";
                //echo "<a class='active' href='$refListes'>Mes listes</a>";
                //echo "<a href='$url_comptes'>Mon Compte</a>";*/
                echo "<a href='$refDeconnexion'>Deconnexion</a>";

            } else {
                $refConnection = $this->container->router->pathFor('formConnexion');
                $refCreationCompte = $this->container->router->pathFor('formCreationCompte');
                echo "<a href='$refListesPubliques'>Listes publiques</a>";
                echo "<a class='active' href='$refConnection'>Se connecter</a>";
                echo "<a href='$refCreationCompte'>S'inscrire</a>";

            }

            ?>

        </div>
    </div>
</head>