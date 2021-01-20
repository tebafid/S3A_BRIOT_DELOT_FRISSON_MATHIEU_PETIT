# Bienvenue sur myWishlist!

MyWishList.app est une application en ligne pour créer, partager et gérer des listes de cadeaux. L'application permet à un utilisateur de créer une liste de souhaits à l'occasion d'un événement particulier (anniversaire, fin d'année, mariage, retraite …) et lui permet de diffuser cette liste de souhaits à un ensemble de personnes concernées. Ces personnes peuvent alors consulter cette liste et s'engager à offrir 1 élément de la liste. Cet élément est alors marqué comme réservé dans cette liste.

## Installation

- Cloner le dépot : 
`git clone https://github.com/tebafid/S3A_BRIOT_DELOT_FRISSON_MATHIEU_PETIT`
- Importer le fichier database.sql se trouvant dans la racine dans votre base de données
- Dans le dossier src/conf modifier le fichier afin de correspondre à votre base de données
````
  driver=mysql
	host=localhost
	database= nom de la base de données
	username= nom de l'utilisateur
	password= mot de passe de la base
	charset=utf8
	collation=utf8_unicode_ci
````
- Voila myWishlist est pret à être utilisé


# Fonctionnalités développés



## Fonctionnalités principales

| Fonctionnalité | Description |
|--|--|
| 1 | Afficher une liste de souhaits |
| 2 | Afficher un item d'une liste |
| 3 | Réserver un item |
| 4 | Ajouter un message avec sa réservation |
| 5 | Ajouter un message sur une liste |
| 6 | Créer une liste |
| 7 | Modifier les informations générales d'une de ses listes |
| 8 | Ajouter des items |
| 9 | Modifier un item |
| 10 | Supprimer un item |
| 14 | Partager une liste |
| 15 | Consulter les réservations d'une de ses liste avant échéance |
| 16 | Consulter les réservations d'une de ses liste après échéance |

## Extensions

| Fonctionnalité | Description |
|--|--|
| 17 | Créer un compte |
| 18 | S'authentifier |
| 20 | Rendre une liste publique |
| 21 | Afficher les listes de souhaits publiques |
| 24 | Uploader une image |
|  | Supprimer une liste |
|  | Déconnexion |
