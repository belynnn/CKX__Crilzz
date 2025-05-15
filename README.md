# 🧠 CRILZZ
## 🚀 À propos du projet
Ce projet est né dans le cadre d'une seconde collaboration avec la Conseillère Numérique Christelle Borrego du CRIL54, une association oeuvrant pour l'inclusion numérique et linguistique. Le besoin : concevoir un outil interactif, simple et engageant, permettant :
- Aux apprenant·es du numérique de tester leurs connaissances de manière ludique
- Aux apprenant·es du français d’améliorer leur compréhension via des quiz thématiques (alpha, FLE)
- Aux bénévoles et formateur·rices de disposer d’un outil pédagogique flexible, personnalisable, et adapté à différents profils

Ce projet s’inscrit dans la continuité des missions portées par l'État Français en matière de médiation numérique et d’autonomisation des publics éloignés du numérique.

## 🎯 Objectif de l'application

Permettre à un·e administrateur·ice de créer des quiz dynamiques avec différents formats de question (QCM, réponse libre, choix multiples) et de lancer des sessions de jeu où les utilisateur·ices (inscrit·es ou anonymes) peuvent jouer ensemble en direct.  
Une session dure jusqu'à la complétion du quiz, avec un **système de score** et un **classement final**.

Ce projet a été conçu de bout en bout : de l’infrastructure réseau (Raspberry Pi 4 auto-hébergé sous Linux Debian) à la gestion du backend, frontend, base de données, et sécurité.

## Public cible
- 🧑‍🏫 **Formateur·rices et bénévoles** : pour animer des sessions dynamiques d’apprentissage
- 📱 **Apprenant·es en numérique** : pour s'exercer aux notions fondamentales de manière ludique
- 🗣️ **Apprenant·es en FLE (Français Langue Étrangère)** : pour renforcer leur compréhension à l’oral et à l’écrit via des quiz thématiques
- 🧑‍💼 **Autres Conseiller·ères Numériques** : comme outil duplicable et inspirant dans d'autres structures

## 🔮 Perspectives d’évolution
- Création de quiz collaboratifs ou communautaires
- Statistiques par session pour suivre les progrès des participant·es
- Intégration d’un système de badge ou de gamification
- Traduction multilingue de l’interface
- Déploiement en cloud ou installation multi-site

---

## #️⃣ Stack technique
### 🧱 Méthodologie
- Notion

### ⚙️ Infrastructure
- Raspberry Pi 4
- Linux Debian

### 🔐 Sécurité réseau
- Configuration IP sécurisée
- Authentification JWT
- Gestion des rôles

### 🌐 Temps réel
- *en réflexion*

### 🗄️ Base de données
- MySQL

### 🔙 Backend
- PHP Symfony6

### 🎨 Frontend
- HTML / SCSS / JavaScript / Bootstrap

---

## 🛠️ Fonctionnalités clés
### 🧑 Apprenant·es
- S’inscrire et se connecter (ou accès anonyme selon la configuration)
- Rejoindre une session de quiz en temps réel
- Répondre aux questions (choix multiples, libre, etc.)
- Voir ses résultats en fin de session
- Adaptabilité du contenu (français, numérique, culture générale, etc.)

### 👩‍💼 Administrateur·ices
- Créer / Modifier / Supprimer des quiz
- Ajouter / modifier / supprimer des questions et réponses
- ancer ou supprimer des sessions de jeu
- Désactiver un quiz sans le supprimer (soft delete)

### 💬 Bénévoles :
- Utilisation de l’outil comme support pédagogique
- Suivi des progressions selon les sessions

---

## 🖥️ Développement
Ce projet est pensé de A à Z :
- Conception de l’infrastructure réseau et machine (auto-hébergement)
- Développement backend REST + WebSocket
- Développement frontend réactif et accessible
- Gestion de projet agile, et versioning Git

## 🔐 Sécurité & hébergement
- Hébergement sécurisé auto-géré sur Raspberry Pi 4 (Debian)
- Restriction des accès via firewall & IP autorisées
- Sécurisation des comptes (hashing, validation, token JWT)
- Données protégées (RGPD compliant)
- Hashage des mots de passe

---

## 🔗 Liens utiles
- 🔗 Dépôt GitHub : [CRILZZ](https://github.com/belynnn/CKX__Crilzz)
- 🌍 Démo en ligne : *à venir / localhost*

---

## Paramètre utile
Ce paramètre vous permet d'utiliser les abréviations **Emmet** directement dans vos fichiers Twig.
1. Vous rendre dans vos settings
2. Sélectionner "> Extensions" dans le menu de gauche
3. Sélectionner "Emmet"
4. Quand vous lisez :
```
Include Languages
Enable Emmet abbreviations in languages that are not supported by default. Add a mapping here between the language and Emmet supported language. For example: {"vue-html": "html", "javascript": "javascriptreact"}
```
5. Cliquez sur "Ajouter"
6. Ajoutez "twig" en "**Key**" et "html" en "**Value**"

## Commandes utiles
### Serveur
- Lancer le serveur
```
symfony server:start
```

- Couper le serveur
```
symfony server:stop
ou
CTRL+C (si vous ne pouvez pas écrire)
```

- Lancer NPM
```
npm run watch
```

- Couper NPM
```
CTRL+C
Répondez "O"
Appuyez sur Enter
```

### Controller
- Créer un nouveau controller
```
php bin/console make:controller nom_du_nouveau_controller
```

### Modèle et Entités
- Creation des entités et rajout d'attributs et de rélations dans les entités
```
php bin/console make:entity
```