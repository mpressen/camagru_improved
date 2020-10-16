# Camagru (Improved)

Il s'agit d'une petite app' de partage de photos à la webcam, développée **sans utiliser de librairies ou frameworks**.

![camagru](https://github.com/mpressen/camagru_improved/blob/master/snapshot-camagru.png)


---

Stack imposée :
- back : **PHP**.
- front : **HTML**, **CSS** et **JavaScript**.

_le sujet pédago est dispo [ici](https://github.com/mpressen/camagru_improved/blob/master/camagru.fr.pdf)_

---

>Ce projet est un reboot du premier projet web  de l'**école 42** que j'ai réalisé en 2016 (dispo [ici](https://github.com/mpressen/web-portfolio/tree/master/camagru), attention les yeux !). Il a pour but de démontrer le chemin parcouru en terme de programmation web.

Pour ce projet, j'ai choisi :
- [x] d'implémenter mon propre [*AMP](https://fr.wikipedia.org/wiki/*AMP) avec **Docker-Compose**.
- [x] de créer _from scratch_ une architecture **MVC** avec notamment un ORM, un routeur, des middlewares et un conteneur de service.
- [x] de sécuriser l'app en suivant les préconisations [OWASP](https://www.owasp.org/index.php/Main_Page) (protections anti-[XSS, CSRF, injections SQL, brute force, tampering], .htaccess, mot de passe hashé).
- [x] de proposer une **UI/UX responsive et dynamique** (drag'n'drop, infinite scrolling, modal display, facebook sharing).
- [x] de le déployer en production avec **Amazon EC2** et **Amazon Route 53** en **HTTPS**.

## Getting started

#### production

<https://www.camagru.maximilien-pressense.fr>

#### développement
Pré-requis : [Docker](https://www.docker.com/) avec Docker Compose.

Dans le terminal, à la racine du projet :
```
docker-compose up
```

_Pour les utilisateurs de Linux, jouer `chmod 777 app/public/images/users` à la racine du projet._

Camagru est alors disponible sur http://localhost.

Et PhpMyAdmin sur http://localhost:8081.
>login : root, password : root
