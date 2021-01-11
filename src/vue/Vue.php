<?php


namespace projet\Vue;


class Vue
{
    private $role, $titre;

    public function __construct(int $role){
        $this->role-$role;
    }

    public function render(){
        if($this->role == DEMANDEUR){
            $titre= "Création liste";
        }else{
            $titre ="Participation liste";
        }
        return <<<end
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset=" "utf-8">
            <title>$titre</title>
        </head>
        <body>
        
        <h1>$titre</h1>
        <div> {$this->menu}</div>
        {$this->html}
        </body>
        </html>
        end;

    }

}