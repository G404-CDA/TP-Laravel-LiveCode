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

## Partie 4 Séparation des responsabilités

Nos classes **ImportRecipeFromJson** et **ImportRecipeFromCsv** possède toujours des responsabilités qu'ils ne sont pas sencé avoir.
Nous allons donc préparer notre couche de persistance des données

- Créer une nouvelle classe appelée **ImporterPersistanceMysql** qui contiendra la logique de code de l'enregistrement en base de données **mysql**
- Créer l'interface appropriés **ImporterPersistanceInterface**
- Faire fonctionner l'ensemble à l'aide de l'injection de dépendance toujours en typant à l'aide de l'interface


## Partie 5 Création de Job et gestion de la Queue avec les Workers

Arrivé à ce stade il nous reste encore une dernière partie du code à refactoriser.
Le code executer dans la commande n'est pas censer être dépendant de la commande
Il va falloir séparer cela en utilisant un Job 
<details>
  <summary>
    <b>
      <i>Mais comment ça marche les Jobs et la queue ?</i>
    </b>
  </summary>
  
  ```md
  Dans Laravel, les **queues** (files d'attente) et les **jobs** (tâches) sont utilisés pour exécuter des tâches en arrière-plan. 
  Cela permet d'améliorer les performances de l'application en décalant les tâches longues ou intensives en ressources, comme l'envoi d'e-mails ou le traitement d'images, pour qu'elles ne bloquent pas l'exécution du reste de ton application.

  Donc par définition : 

  Les **Jobs** sont des tâches que tu veux exécuter en arrière-plan. Par exemple le chargement de nos recettes en Base de données

  Les **Queues** sont des files d'attente de jobs 
  - Quand tu crées un job, il est ajouté à la queue.  
  - Laravel exécute ensuite les jobs de la queue un par un, en arrière-plan. 

  Tu peux avoir plusieurs queues pour organiser tes jobs (par exemple, une queue pour les e-mails, une autre pour le traitement d'import et chargement des données, les traitements des images, etc.).
  
  Les **Workers** sont des processus qui exécute les jobs de la queue. 
  Quand un worker est disponible, il prend le prochain job de la queue et l'exécute. 
  Si plusieurs workers sont disponibles, ils peuvent exécuter plusieurs jobs en parallèle.
  ```
</details>

Avant de commencer cette étape, ils faut paramettrer la gestion de la queue.
Voici la documentation Laravel pour le faire :
https://laravel.com/docs/10.x/queues#driver-prerequisites

Pour cet exercice nous allons utiliser notre base de données (database dans la documentation laravel) pour gérer le système de queue.

Mais je vous invite vivement à essayer **Redis** pour gérer vos queues ainsi que vos workers à l'avenir. C'est évidement un meilleur choix en raison de **sa performance**, de **sa fiabilité**, de **ses fonctionnalités avancées** et de **sa meilleure gestion de la concurrence**.

Nous allons donc créer un **Job** au lancement de la commande
Pour cela créer la classe **ImporterJob** dans un dossier `Job`
  - Déplacer la logique de code restant de la commande dans le job.
  - Si vous le souhaitez, ajouter un `sleep(5)` dans votre jobs pour simuler une opération lourde mettant plusieur seconde pour s'executer par notre worker

Au Lancement de la commande :
- Dispatcher **de manière synchrone** le job pour l'ajouter à une queue

#### **Vérification du lancement du Job et execution de la queue**

Grâce à **laravel telescope** vous allez pouvoir vérifié le lancement des différents Jobs 

#### Execution des workers pour gerer la queue : 
Laravel possède différentes commande afin de gerer les queues ainsi que les workers :
 - https://laravel.com/docs/10.x/queues#running-the-queue-worker 


Prendre en compte la gestion des jobs-failed (des tâches échouées) :

 - https://laravel.com/docs/10.x/queues#dealing-with-failed-jobs



### Source pour avoir plus d'information sur les files d'attentes (Queue, Job) et la gestion de la concurrence : 
- https://www.youtube.com/watch?v=_tP1WXgPSRk&ab_channel=Grafikart.fr
- https://www.youtube.com/watch?v=j4h0lFswnu4&t=2s&ab_channel=AFUPPHP
- https://www.youtube.com/watch?v=Xuj-4yFsQzE&ab_channel=Grafikart.fr
- https://www.youtube.com/watch?v=j4h0lFswnu4&ab_channel=AFUPPHP



## Partie 6 Evénements et Ecouteurs d'événements

Maintenant que Nous avons créé des jobs s'éxecutant en tâche de fond nous allons nous intérésser au événement.
  - Créer un évenement **SucceedJobEvent** dans un dossier `app/Events`;
  - Dispatcher l'événement lorsque le job se termine en utilisant le **AppServiceProvider**.

Pour vérifier que notre événement est bien déclanché créont notre écouteur d'événement ! 
 - Créer l'écouteur d'événement **SucceedJobEventSubscriber** qui écoutera l'event créé plus tôt et qui enverra un mail à l'administrateur pour le prévenir de la fin du job.
