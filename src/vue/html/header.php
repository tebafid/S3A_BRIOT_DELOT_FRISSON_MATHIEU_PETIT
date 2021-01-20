
<head>
    <div class="header">
        <?php
        $url_accueil = $this -> container -> router -> pathFor ( 'accueil' );

        echo "<a href='$url_accueil' class='logo'>MyWishList</a>";
        ?>
        <div class="header-right">
            <?php
            $refListesPubliques = $this->container->router->pathFor('listesPubliques');
            if(isset($_SESSION['iduser'])) {
                $refListes = $this->container->router->pathFor('mesListes');

                $refDeconnexion = $this->container->router->pathFor('deconnexion');

                echo "<a href='$refListesPubliques'>Listes publiques</a>";
                echo "<a class='active' href='$refListes'>Mes listes</a>";
                echo "<a href='$refDeconnexion'>Deconnexion</a>";

            }else {
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