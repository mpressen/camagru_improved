# Camagru (Improved)
##### *(en cours de développement)*
Premier projet web de l'école 42, il s'agit de créer une petite app' de partage de photos, **sans utiliser de librairie ou framework**.
---

Stack imposée : 
- back : **PHP** avec interface PDO pour communiquer avec une base SQL.
- front : **HTML**, **CSS** et **JavaScript** (avec les API natives des navigateurs uniquement).
---

>Ce projet est un reboot du premier projet web que j'ai réalisé il y a deux ans (dispo [ici](https://github.com/mpressen/web-portfolio/tree/master/camagru), attention les yeux !). Il a pour but de démontrer le chemin parcouru en terme de programmation web.

Pour ce projet, j'ai voulu me concentrer sur le back et l'infra. Ainsi, j'ai choisi :
- [x] d'implémenter mon propre [*AMP](https://fr.wikipedia.org/wiki/*AMP) avec **Docker**.
- [ ]  de créer mon propre micro-framework MVC. *(WIP)*
- [ ] de le déployer en production avec **Amazon ECS** (Elastic Container Service).

## Getting started

#### dev
Pré-requis : [Docker](https://www.docker.com/)

Dans le terminal, à la racine du projet :
```
docker-compose up
```

Camagru est alors disponible sur http://localhost.

Et PhpMyAdmin sur http://localhost:8081.
>login : root, password : mpressen