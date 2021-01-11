<?php

namespace projet\Vue;

class Utilisateur
{
    public  function enregistrer(){
        echo <<<ez
        <h3>Enregistrez-vous</h3>
        <form action="" method="post">
        Nom : <input type ="text" name ="nom">
        <p>Mot de passe: <input type="password" name="password"></p>
        <input type="submit" name="i" value=""S'enregistrer">
        </form>
        ez;
    }

}