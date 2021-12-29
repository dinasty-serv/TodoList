
## Téléchargement du projet

Télécharger les sources de l'application:

    git clone git@github.com:dinasty-serv/TodoList.git ,/


## Installation
Dans le répertoire des sources taper la commande suivante

    composer install –-no-dev (pour les environnements de production)


## Configuration
Dans le fichier .env configurer les accès à votre base de données.

    DATABASE_URL="mysql://USER:MDP@p8_dev_db:3306/todo?serverVersion=5.7"


## Déploiement de l'environement de Production
Dans le répertoire des sources taper la commande suivante

    composer prepare-prod

Cette commande va installer et initialisé la base données en environnement de production, c-a-d sans les éléments de debug de symfony.


## Déploiement de l'environement de Développement
Dans le répertoire des sources taper la commande suivante

    composer prepare-dev


Un compte administrateur est créer avec ce mode:

    Username: admin@email.com
    Password: 0000

## Lancement des tests

Dans le répertoire des sources taper la commande suivante

    composer start-test
Cette commande va initialisé une base de données avec des données factice pour réaliser les différents tests. 

A l'issue de ces tests vous aurez le résultat suivant si tout les tests sont passé: 
    
    PHPUnit 9.5.10 by Sebastian Bergmann and contributors.

    Testing
    ...............                                                   15 / 15 (100%)

    Time: 00:01.534, Memory: 50.50 MB

    OK (15 tests, 22 assertions)

## Rapport code coverage
Les fichiers du rapport sont disponible dans le dossier reports à la racine du projet, le dernier rapport effectué remonte un taux de code Covrage de 82.63% 
