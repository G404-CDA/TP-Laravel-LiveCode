<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# TP Laravel Recipe

## Partie 1 Installation et configuration de laravel

- Installation de la stack
  - Install Laravel (laragon, devilbox, php, composer) : https://laravel.com/docs/10.x/
- Créer l'application : `composer create-project laravel/laravel ./`
- Créer la bdd
- Paramétrer le fichier .env
- Laravel Telescope : https://laravel.com/docs/10.x/telescope
- Laravel Swagger : https://github.com/DarkaOnLine/L5-Swagger
- Laravel Nova : https://nova.laravel.com/docs/installation.html#installing-nova-via-composer
  - Paramètrage de l'authentification de laravel Nova
  - Créer un fichier `auth.json` à la racine du projet
  - Ajouter le json contenant les informations de connexion nova dans le fichier : 
    ```json
    {
        "http-basic": {
            "nova.laravel.com": {
                "username": "admin@idmkr.io",
                "password": "f2VV1A2SxdXtz50kf9qa1NeNLg6opM8Yk4d0FNSxaED6Decscz"
            }
        }   
    }
    ```
 - Lancer l'installation des assets Laravel Nova


## Partie 2 Création du Model, Commande, Routes API, Documentation Swagger

### Model et Migration
- Créer le model (Recipe)
- Paramétrer le model
- Créer le fichier de migration
  - La migration contient les champs suivant : 
    - id
    - name > string
    - ingredients > json
    - preparationTime > string
    - cookingTime > string
    - serves > integer
- Migrer la migration et ajouter les fillables au model

### Création de la commande
- Créer la commande qui permet d'importer le fichier json en base de données: 
    - Création de la commande php artisan


### Route API et Controller
- Créer le controller
  - Créer la methode **all()** 
    - Retourne un json de l'ensemble des recettes ( les propriétés importantes ) 
    - L'url de la route sera `/recipes` 
    - (http://localhost/api/recipes)
  - Créer le methode **get($id)** 
    - Retourne un json d'une seule et unique recette (l'ensemble des propriétés) 
    - L'url de la route sera `/recipe/{id}`
- Créer les routes api dans le fichier `api.php`
- Créer la doc Swagger
  - Ajouter les commentaires permettant de générer la documentation swagger
  - Tester la route API avec la documentation
- Ajouter les routes suivantes :
  - **POST** : `/recipe/add`
  - **PUT** : `/recipe/modify/{id}`
  - **DELETE** : `/recipe/delete/{id}`
- Ajouter également la documentation swagger à ces routes


### Dashboard Admin Nova
- Créer l'admin
- Créer les ressources laravel Nova dont nous allons avoir besoin


## Partie 3 Création de RepositoryPattern + Importer

### Création du RepositoryPattern
  - Pour la suite de l'exercice travaillons sur de la refactorisation 
  - Nous allons implémenter le DesignPattern **Repository**
    - `Un Repository est une séparation entre un domaine (business) et une couche de persistance. Le Repository fournit une interface de collecte pour accéder aux données stockées dans une base de données, un système de fichiers ou un service externe.` 
    - *`En Laravel, l'accés aux données se fait via Eloquent, qui est un ORM, mais il est aussi possible d'utiliser Doctrine. Ces deux ORM sont géniaux pour encapsuler les requetes SQL, mais restent limités lorsque l'accès aux données deviens plus complexe ou spécifique. Utiliser directement l'ORM dans le controlleur est un antipattern qui risque vite rendre le code de votre application illisible !`*
  - Créer un dossier **Repositories** dans le dossier app
  - Créer une interface **RepositoryInterface** puis une classe **RecipeRepository**
  - Créer les methodes du repository en utilisant le model recipe afin de gerer les requêtes à la base de données
  - Utilisation du repository : 
    - Refactoriser le code du **RecipeController** afin de ne plus utiliser le model mais d'utiliser le repository dans nois controller
    - **Injecter** le **Repository** (en gardant en tête les principes SOLID) dans la commande afin de refactoriser la commande et d'utiliser le repository plutot que le model dans la commande
      - Utiliser le ServiceContainer afin d'injecter l'instance de la classe dans le constructeur de la commande pour éviter la dépendance

### Refactorisation : Création d'une classe Importer

- Créer une classe **ImportRecipesFromJSON** dans un dossier `Importer` qui va exécuter le code actuellement dans le fichier de commande (En prenant en compte le **SingleResponsabilityPrinciple**)
  - Cette classe doit recevoir en argument le nom du fichier
  - La commande reçoit en **injection de dépendance** la classe à utiliser

### Ajouter la possibilité d'importer un fichier CSV ou JSON

- Transformer le fichier `recipe.json` en `recipe.csv`
- Créer un nouvel importer **ImportRecipesFromCsv**
- En fonction de l'extension du fichier (csv ou json), je veux que ce soit soit le **ImportRecipesFromJson** ou le **ImportRecipesFromCsv** qui soit appeler
    - Il faudrait créer un fichier de configuration appeler `importer.php` qui permettra d'associer un **Importer** à un type de fichier
    - Je ne veux pas que la logique contienne de if sur la variable contenant le nom du fichier ni sur son type (il y aura des if mais sur d'autre variable)
    - Pour que tous fonctionne vous allez avoir besoin de plusieurs choses :
      - Premièrement créer les 2 Importers
      - Créer une interface et (si besoin) une classe abstraite pour les importers 
      - En fonction du type de fichier envoyé, choisir d'instancier le bon importer
      - Pour cela il va vous falloir utiliser le **DesignPattern Factory** avec afin de pouvoir instancié la bonne classe 
        - Créer une classe **ImporterFactory** qui possedera une methode `chooseImporter()` qui premettra de renvoyer la bonne instance en fonction de l'extension du fichier.
        - Utiliser le fichier de config `importer.php` pour stocker les **Importer** à utiliser en fonction du type de fichier ainsi que la class Config
        - Utiliser la Facade **Config** pour dans la factory pour aller cherche la bonne classe instancier (Utiliser `Config::get(....)` > [How to get values from config files in Laravel with Config facade](https://www.educative.io/answers/how-to-get-values-from-config-files-in-laravel-with-config-facade))
      - Changer le code de la commande afin d'utiliser la factory qui permettra de choisir le bon importer et ainsi executer le code pour importer les données

        