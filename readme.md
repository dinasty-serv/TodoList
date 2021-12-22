
1 Clonage du repository
Cloner le repository du projet via la commande suivante :

git clone git@github.com:dinasty-serv/TodoList.git ,/


2 Installation des dépendances et de Symfony

composer install –-no-dev (pour les environnements de production)


3 Configuration de la base de données:

DATABASE_URL="mysql://USER:MDP@p8_dev_db:3306/todo?serverVersion=5.7"


4 Initialisation de l’application

composer prepare-dev

La commande:composer prepare-dev va se charger de créer et initialiser la base de données avec des données factice (Des fixtures), cela permet de chargé l’application et testé les différentes fonctionnalités.

Un compte administrateur est créer avec ce mode:
Username: admin@email.com
Password: 0000


