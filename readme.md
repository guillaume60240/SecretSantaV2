# Secret Santa 

Ce projet est en cours de réalisation. Certaines features verront le jour plus tard et je m'amuse à relire mon code afin de l'améliorer et de l'optimiser. Je suis preneur de toutes remarques me permettant d'avoir un code de meilleure qualité.


# L'application

**Secret Santa** permet de générer des pères Noël aléatoires dans une liste de participants en suivant certaines contraintes. 

## L'utilisateur

C'est le membre qui se créera un compte et sera à l'initiative de la liste des participants. Il devra renseigner son mail, son nom et son prénom. Le mail lui permettra, en plus de se connecter, de recevoir certaines notifications qui seront détaillées plus tard.

## Les participants

Chaque participant sera un **Secret Santa** et sera renseigné par l'utilisateur. Au moment de la création de la liste, l'utilisateur peut choisir de participer ou non au tirage avec une simple checkbox à cocher.  Si l'utilisateur renseigne un mail à l'utilisateur, celui ci pourra recevoir une notification une fois la liste générée. 
Toutes les informations renseignées seront modifiables plus tard.

## La liste

Une liste de **Secret Santa** aura une date qui correspondra à la date de l'évènement pendant lequel l'échange de cadeaux aura lieu. Un nom permettra de l'identifier et une description pourra lui être ajoutée. L'utilisateur pourra ajouter ou supprimer des participants à n'importe quel moment ainsi que modifier les informations relatives à la liste.

## Les contraintes

De base chaque participant débute avec deux contraintes : lui-même et la dernière personne à qui il a offert un cadeau (dans le cadre d'une liste réutilisée). L'utilisateur pourra en plus empêcher un participant d'être le **Secret Santa** d'un autre.


## Générer la liste

Une fois toutes les informations renseignées et tous les participants créés, l'utilisateur peut lancer la génération des **Secret Santa**. Une nouvelle génération ne sera plus possible jusqu'à ce que la date de l'évènement soit passée. 
Après la génération des **Secret Santa** un lien propre à chaque participant sera créé afin qu'il puisse consulter sur le site le participant qui lui a été assigné.

## Les notifications

Une fois les **Secret Santa** générés, l'utilisateur peut recevoir un mail de confirmation. Chaque participant, si un mail lui est assigné, recevra un mail avec le nom du participant à qui il doit faire un cadeau. L'utilisateur peut être averti quand un participant vient consulter sur le site le résultat du tirage via le lien qui lui est assigné.
Toutes les notifications peuvent être désactivées par l'utilisateur. 
 > Il n'y a que l'envoi de mail aux participants dont l'adresse mail est renseignée activé par défaut.


# L'évolution

Comme dit en préambule, cette application est en cours de développement. Je prévois d'améliorer l'algorithme permettant l'assignation d'un participant aux **Secret Santa** par exemple.
Certaines fonctionnalités n'existent pas encore :

- Rendre un évènement récurent 
   >Une fois générée la liste peut être modifiée ainsi que les participants mais pas réutilisée. 
   
- L'utilisateur peut supprimer son compte
- L'utilisateur peut modifier ses informations personnelles
- L'utilisateur peut récupérer son compte après un oubli de mot de passe
- Il n'est pas possible qu'un participant soit une contrainte pour tous les participants 
   >Pour le moment un message d'erreur est retourné à l'utilisateur .
  
Le design peut être amené à être modifier en fonction des idées que je pourrai avoir.


# Spécifications techniques


## Symfony 6 et PHP 8

Le coeur de l'application a été pensé et réalisé avec le framework **Symfony** . 
J'aime particulièrement son utilisation, le travail en respectant le pattern **MVC**, l'utilisation de **Doctrine** pour communiquer avec la base de données **PostgreSql** et la sécurité des applications proposée par défaut. 
L'algorithme pour la génération des **Secret Santa** a été développé en **PHP**.
Le moteur de template **Twig** est vraiment utile pour éviter d'utiliser du **PHP** dans du **HTML** et ceci rend le code plus lisible.

## Javascript

Afin de rendre l'application plus dynamique j'ai décidé d'utiliser **Javascript** pour valider niveau client les inputs des formulaires, avant et après soumission. 
> Il y a bien sûr une deuxième validation niveau serveur avec **PHP**

Initialement développé en procédural j'ai modifié mon code pour me tourner vers la **POO** afin de le rendre réutilisable et plus maintenable. 
**Javascript** s'est montré particulièrement efficace dans la gestion des contraintes des participants. L'expérience utilisateur est vraiment pour agréable ainsi.

## SCSS et Bootstrap

Le style a été conçu autour du framework **Bootstrap** afin de garantir un développement mobile-first, un responsive idéal et une bonne mise en place des éléments (comme les cartes) qui m'étaient nécessaires. 
Afin d'être plus précis j'ai utilisé **SCSS** pour modifier chaque élément. 
Ceci a été intégré à **Symfony** par le bundle **Webpack-encore**. 

## Mise en production

Même si l'application n'est pas terminée, j'ai préféré la rendre accessible dès maintenant. Ceci me permet de vérifier son comportement et d'avoir des retours d'utilisateurs afin d'améliorer son utilisation. 
Vous pourrez la trouver sur **Heroku** ici :
 [L'application Secret Santa](https://secret-santa-list-v2.herokuapp.com/)

## Me contacter
N'hésitez pas à me contacter ou à me rejoindre sur mes réseaux professionnels, vous les trouverez [ICI](https://formationsweb-glepoetre.fr)

