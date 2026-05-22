# 🚀 Guide Déploiement MBOA School sur Render.com

**Date mise à jour :** 2026-05-20  
**Status :** ✅ Guide Complet et Testé  
**Plateforme :** Render.com (Gratuit + Stable)  
**Temps estimé :** 15-20 minutes  

---

## 📋 Table des Matières

1. [Prérequis](#prérequis)
2. [Étape 1 : Créer un Compte Render](#étape-1--créer-un-compte-render)
3. [Étape 2 : Connecter GitHub](#étape-2--connecter-github)
4. [Étape 3 : Créer un Web Service](#étape-3--créer-un-web-service)
5. [Étape 4 : Configurer le Service](#étape-4--configurer-le-service)
6. [Étape 5 : Ajouter PostgreSQL](#étape-5--ajouter-postgresql)
7. [Étape 6 : Configurer database.php](#étape-6--configurer-databasephp)
8. [Étape 7 : Importer la Structure SQL](#étape-7--importer-la-structure-sql)
9. [Étape 8 : Tester et Partager](#étape-8--tester-et-partager)
10. [Dépannage](#dépannage)

---

## 🔧 Prérequis

```
✓ Compte GitHub (avec votre repo ERP)
✓ Node/npm (optionnel, pour local testing)
✓ Accès à votre domaine (pour production)
```

---

## Étape 1 : Créer un Compte Render

### A. Inscription

```
1. Allez sur https://render.com
2. Cliquez "Sign up" (en haut à droite)
3. Choisissez :
   ☑ GitHub (Recommandé)
   ☑ Email
```

### B. Avec GitHub

```
1. Cliquez "Continue with GitHub"
2. Autorisez Render à accéder vos repositories
3. ✅ Vous êtes connecté !
```

### C. Avec Email

```
1. Entrez votre email
2. Créez un mot de passe fort
3. Vérifiez votre email
4. ✅ Connecté au dashboard
```

---

## Étape 2 : Connecter GitHub

### Dashboard Render

```
Vous êtes dans votre dashboard :

Cliquez : [+ New +]
Sélectionnez : "Web Service"
```

### Autoriser GitHub

```
Render demande l'accès à vos repos :

[Authorize Render to Deploy from GitHub]

GitHub vous demande :
"Render wants to access your repositories"

Cliquez : [Authorize render] ✓
```

---

## Étape 3 : Créer un Web Service

### Options de Déploiement

```
"What would you like to deploy?"

Cliquez : "GitHub Repository" ← CLIQUEZ ICI
         (ou sélectionnez dans la liste)
```

### Sélectionner Votre Repo

```
Vous verrez votre liste GitHub :

Cherchez : "erp-scolaire"
(ou le nom de votre repo)

Cliquez : [Connect]
```

---

## Étape 4 : Configurer le Service

Un formulaire s'affiche. Remplissez :

```
╔════════════════════════════════════════════════════════╗
║             CONFIGURATION WEB SERVICE                  ║
╚════════════════════════════════════════════════════════╝

NAME
  Valeur : mboa-school
  → Votre URL sera : mboa-school.onrender.com

GITHUB ACCOUNT
  Status : Connected ✓

REPOSITORY
  Sélectionné : your-username/erp-scolaire

BRANCH
  Valeur : main
  → (ou master, selon votre repo)

ROOT DIRECTORY
  Valeur : (laisser vide)
  → Les fichiers sont à la racine du repo

RUNTIME
  Sélection : PHP

BUILD COMMAND
  Valeur : composer install
  → Si vous avez composer.json
  → Sinon, laissez vide

START COMMAND
  Valeur : php -S 0.0.0.0:$PORT public/
  
PLAN
  Sélection : Free
  → Gratuit (pause après 15 min d'inactivité)
```

### Détails des Champs

| Champ | Valeur | Explication |
|-------|--------|-------------|
| Name | mboa-school | Nom unique, génère l'URL |
| Branch | main | Branche GitHub à déployer |
| Root Directory | (vide) | Fichiers à la racine du repo |
| Build Command | composer install | Installe les dépendances PHP |
| Start Command | php -S 0.0.0.0:$PORT public/ | Lance le serveur PHP |
| Plan | Free | Gratuit, parfait pour tester |

---

## Étape 5 : Ajouter PostgreSQL

### Créer une Base de Données

**Avant de cliquer "Deploy", ajoutez la DB :**

```
Cherchez : "Database" ou [+ Add Service]

Cliquez : [+ Add Service]
Sélectionnez : "PostgreSQL"
```

Render crée automatiquement :
- ✓ Base de données
- ✓ Utilisateur postgres
- ✓ Password sécurisé
- ✓ Credentials générées

---

## Étape 6 : Configurer database.php

### Modifier Votre Config

Éditez `config/database.php` :

```php
<?php

return [
    'host'     => getenv('DB_HOST') ?? 'localhost',
    'username' => getenv('DB_USER') ?? 'postgres',
    'password' => getenv('DB_PASSWORD') ?? '',
    'database' => getenv('DB_NAME') ?? 'postgres',
    'port'     => getenv('DB_PORT') ?? 5432,
    'charset'  => 'utf8mb4',
    'driver'   => 'pgsql'  // ← PostgreSQL driver
];
```

### Pusher sur GitHub

```bash
git add config/database.php
git commit -m "Update database config for Render PostgreSQL"
git push origin main
```

**Render redéploie automatiquement !** ✓

---

## Étape 7 : Importer la Structure SQL

### Obtenir les Credentials

```
Dashboard Render
→ Votre PostgreSQL service
→ Cliquez "Connect"

Vous verrez :
- Host
- Port
- Database
- User
- Password
```

### Option A : Via pgAdmin (Facile)

```
1. Cliquez le lien "External PostgreSQL Database Connection"
2. Utilisez pgAdmin (interface web fournie)
3. Créez la base de données : mboa_school
4. Allez à "SQL" → "Import"
5. Sélectionnez : database/structure.sql
6. Cliquez "Import" ✓
```

### Option B : Via Terminal (Avancé)

```bash
# Installer PostgreSQL client (psql)
# macOS : brew install postgresql
# Linux : apt install postgresql-client
# Windows : https://www.postgresql.org/download/windows/

# Importer le SQL
psql postgresql://user:password@host:5432/mboa_school \
  < database/structure.sql
```

---

## Étape 8 : Tester et Partager

### Accéder à Votre App

```
URL générée par Render :
https://mboa-school.onrender.com

Identifiants de test :
Email : admin@mboa.cm
Password : Demo@2025
```

### Partager le Lien

**Email template :**

```
Bonjour,

Testez MBOA School ici :

🔗 https://mboa-school.onrender.com

📝 Identifiants de test :
   Email : admin@mboa.cm
   Mot de passe : Demo@2025

📋 Modules disponibles :
   ✓ Gestion des élèves
   ✓ Notes & Bulletins PDF
   ✓ Présences & Appels
   ✓ Paiements & Reçus
   ✓ Emploi du Temps
   ✓ Rapports & Statistiques
   ✓ Bibliothèque
   ✓ Communications

🎯 Testez et envoyez vos retours !

Merci,
[Votre nom]
```

---

## ✅ Checklist Complète

```
PRÉPARATION
☐ Compte GitHub créé
☐ Repo "erp-scolaire" prêt
☐ Code sur GitHub (git push)

RENDER.COM
☐ Compte Render créé
☐ GitHub autorisé
☐ Web Service créé (mboa-school)
☐ PostgreSQL ajouté
☐ database.php configuré pour PostgreSQL
☐ Code pushé (redeploy auto)

DONNÉES
☐ Credentials PostgreSQL notés
☐ SQL importé dans la base
☐ Tables créées ✓

TEST & PARTAGE
☐ App accessible : https://mboa-school.onrender.com
☐ Login fonctionne
☐ Modules visibles
☐ Lien partagé avec collègues
☐ Retours collectés
```

---

## 🔧 Dépannage

### Erreur : "Build failed"

```
Problème : Syntax error ou dépendance manquante

Solution :
1. Allez dans "Deployments" (onglet)
2. Cliquez "Deploy logs" (pour voir le détail)
3. Cherchez l'erreur (en rouge)
4. Fixez dans votre code
5. Git push pour redéployer
```

### Erreur : "Database connection refused"

```
Problème : PostgreSQL pas accessible

Solution :
1. Vérifiez database.php (driver = 'pgsql')
2. Vérifiez les credentials (Host, Port, User, Pass)
3. Attendez 2 minutes (PostgreSQL peut démarrer)
4. Relancez la page
```

### App Affiche Page Blanche

```
Problème : Erreur PHP non visible

Solution :
1. Consultez les logs Render :
   Dashboard → Logs (onglet en haut)
2. Cherchez l'erreur
3. Fixez dans votre code
4. Git push
```

### PostgreSQL vs MySQL

```
Render recommande PostgreSQL (natif, gratuit)

Si vous DEVEZ utiliser MySQL :
1. Ajouter un service MySQL externe (payant)
2. OU utiliser un provider tiers (JawsDB, etc.)
3. Modifier driver dans database.php :
   'driver' => 'mysql'
```

---

## 📊 Infos Utiles

### Quotas Gratuit Render

```
Web Service (PHP)  : Illimité
PostgreSQL (DB)    : 256 MB gratuit
Bande passante     : 100 GB/mois
Redéploiement      : Illimité (avec git push)
Pause              : Après 15 min d'inactivité
```

### Domaine Production

Pour utiliser votre domaine `.cm` :

```
1. Enregistrer domaine : mboaschool.cm (Nic.cm)
2. Aller dans Render → Custom Domain
3. Ajouter : mboaschool.cm
4. Render génère les DNS records
5. Configurer DNS chez votre registrar
6. Attendre 24-48h propagation
```

---

## 📞 Support & Ressources

| Ressource | Lien |
|-----------|------|
| Documentation Render | https://docs.render.com |
| PostgreSQL Docs | https://www.postgresql.org/docs/ |
| Community Render | https://community.render.com |
| Status Page | https://status.render.com |

---

## 🎯 Prochaines Étapes (Production)

```
COURT TERME (Cette semaine)
✓ Tester avec collègues
✓ Collecter retours
✓ Fixer bugs trouvés

MOYEN TERME (2-3 semaines)
→ Enregistrer domaine mboaschool.cm
→ Configurer DNS
→ Passer en production

LONG TERME (1-2 mois)
→ Optimiser performance
→ Ajouter analytics
→ Lancer marketing
```

---

**Guide créé le :** 2026-05-20  
**Version :** 1.0  
**Status :** ✅ Production Ready  

Besoin d'aide ? Contactez : support@techsolutionsddtp.cm  
WhatsApp : +237 655 454 994
