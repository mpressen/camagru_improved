# Camagru (Improved)

Il s'agit d'une petite app' de partage de photos à la webcam, développée **sans utiliser de librairies ou frameworks** .

[[https://github.com/mpressen/camagru_improved/blob/master/snapshot-camagru.png|alt=camagru]]

---

Stack imposée : 
- back : **PHP** avec interface PDO et librairie GD.
- front : **HTML**, **CSS** et **JavaScript**.

_le sujet pédago est dispo [ici](https://github.com/mpressen/camagru_improved/blob/master/camagru.fr.pdf)_

---

>Ce projet est un reboot du premier projet web que j'ai réalisé il y a deux ans (dispo [ici](https://github.com/mpressen/web-portfolio/tree/master/camagru), attention les yeux !). Il a pour but de démontrer le chemin parcouru en terme de programmation web.

Pour ce projet, j'ai choisi :
- [x] d'implémenter mon propre [*AMP](https://fr.wikipedia.org/wiki/*AMP) avec **Docker-Compose**.
- [x] de créer _from scratch_ une architecture **MVC** avec notamment un proto-ORM, un routeur basique, des simili-middlewares et un conteneur de service.
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

Camagru est alors disponible sur http://localhost.

Et PhpMyAdmin sur http://localhost:8081.
>login : root, password : root
