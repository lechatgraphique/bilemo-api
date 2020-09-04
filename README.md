# P7-BileMo API

Work in progress ...

Création d'une API Rest pour BileMo, une entreprise de vente de téléphone.

## Environnement utilisé durant le développement
* Symfony 5
* Composer 1.9.2
* PHP 7.4.7
* MySQL 8.0.18

## Installation
[![Repo Size](https://img.shields.io/github/repo-size/lechatgraphique/bilemo-api.svg?label=Repo+Size)](https://github.com/lechatgraphique/bilemo-api.git/tree/master) \
Exécutez la ligne de commande suivante pour télécharger le projet dans le répertoire de votre choix:
```
git clone https://github.com/lechatgraphique/bilemo-api.git
```
Installez les dépendances en exécutant la commande suivante:
```
composer install
```
## Base de données
Modifier la connexion à la base de données dans le fichier .env.
```
DATABASE_URL=mysql://root:@127.0.0.1:3306/bilemo-api
```
Créer une base de données:
```
php bin/console doctrine:migrations:migrate
```
Créez la structure de la base de données:
```
php bin/console doctrine:migrations:migrate
```
Chargez les données initiales:
```
php bin/console doctrine:fixtures:load
```
## Lancez l'application
Lancez l'environnement d'exécution Apache / Php en utilisant:
```
php bin/console server:run
```
## Crédits d'utilisateur par défaut
```
{
  "username": "blabla-phone@gmail.com",
  "password": "123456"
}
```
