# ERP Scolaire — Documentation

Système de gestion scolaire PHP/MySQL multi-établissements, conçu pour les structures Primaire, Collège, Lycée et Université.

> **État actuel :** Phase 1 MVP — ~90% fonctionnel. Analyse production effectuée le 18/05/2026.

---

## Prérequis

- **WampServer** (ou LAMP/LEMP) avec **PHP ≥ 8.0** et **MySQL ≥ 5.7**
- **Composer** (gestionnaire de dépendances PHP)
- Apache avec `mod_rewrite` activé

---

## Installation

```bash
# 1. Copier le projet dans le répertoire web
#    WampServer : C:\wamp64\www\erp-scolaire\

# 2. Installer les dépendances
composer install

# 3. Importer la base de données
mysql -u root -p erp_scolaire < database/schema.sql
mysql -u root -p erp_scolaire < database/seeds/init.sql

# 4. Copier et configurer les variables d'environnement
copy .env.example .env
# Puis éditer .env avec vos valeurs
```

### Variables d'environnement (`.env`)

```ini
APP_NAME=ERP Scolaire
APP_URL=https://votre-domaine.com        # HTTP en dev, HTTPS en prod
APP_ENV=production                        # development | production
APP_DEBUG=false                           # false en production

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=erp_scolaire
DB_USERNAME=root
DB_PASSWORD=VotreMotDePasseSecurise

MAIL_HOST=smtp.votre-fournisseur.com
MAIL_PORT=587
MAIL_USERNAME=noreply@votre-domaine.com
MAIL_PASSWORD=VotreMotDePasseSMTP
MAIL_FROM_NAME="ERP Scolaire"
```

### Accès

| URL | Description |
|-----|-------------|
| `http://localhost/erp-scolaire/public/` | Point d'entrée (développement) |
| `http://localhost/erp-scolaire/public/login` | Page de connexion |

---

## Comptes par défaut

| Rôle | Login | Mot de passe |
|------|-------|--------------|
| Super Admin | `superadmin` | `Admin@1234` |
| Admin établissement | `admin` | `Admin@1234` |

> **IMPORTANT :** Changer ces mots de passe immédiatement après la première connexion, et supprimer ou chiffrer les mots de passe dans `database/seeds/init.sql` avant tout déploiement.

---

## Architecture

```
erp-scolaire/
├── app/
│   ├── Config/           # app.php, database.php
│   ├── Controllers/      # 18 contrôleurs MVC
│   ├── Core/             # Router, Auth, Database, View, Session, CSRF,
│   │                     # Controller, Model, QueryBuilder, Request, Validator
│   ├── Helpers/          # functions.php — 30+ fonctions globales
│   └── Views/            # 45+ vues PHP
│       ├── layouts/      # app.php, auth.php, print.php
│       ├── auth/         # login, forgot-password, reset-password
│       ├── dashboard/    # admin, super_admin, no_annee
│       ├── etablissement/
│       ├── personnel/
│       ├── eleves/
│       ├── classes/
│       ├── emploi-du-temps/
│       ├── presences/
│       ├── notes/
│       ├── bulletins/    # + pdf/bulletin.php (impression)
│       ├── paiements/
│       ├── examens/
│       ├── comptabilite/
│       ├── bibliotheque/
│       ├── communication/
│       ├── rapports/
│       ├── parametres/
│       └── profil/
├── config/               # app.php, database.php
├── database/
│   ├── schema.sql        # 33 tables
│   └── seeds/init.sql    # Données initiales (rôles, permissions, comptes)
├── routes/
│   └── web.php           # 100+ routes avec middlewares
├── public/
│   ├── index.php         # Point d'entrée unique
│   ├── .htaccess         # Réécriture URL (Apache 2.4)
│   └── assets/
│       ├── css/          # app.css, bootstrap.min.css, bootstrap-icons
│       └── js/           # bootstrap.bundle.min.js
├── storage/
│   ├── uploads/          # Photos, logos (écriture requise)
│   ├── logs/             # Fichiers de log (écriture requise)
│   └── exports/          # PDF, Excel générés
├── vendor/               # Dépendances Composer
├── composer.json
├── .env                  # Variables d'environnement (NE PAS COMMITTER)
└── README.md
```

---

## Modules

| # | Module | URL | État | Fonctionnalités |
|---|--------|-----|------|-----------------|
| 1 | **Dashboard** | `/dashboard` | ✅ Complet | Stats globales, activité récente, multi-rôles |
| 2 | **Établissements** | `/etablissements` | ✅ Complet | CRUD multi-établissements, logo, type |
| 3 | **Personnel** | `/personnel` | ✅ Complet | Enseignants & admin, photo, matières |
| 4 | **Élèves** | `/eleves` | ✅ Complet | Inscription, parents, groupe sanguin |
| 5 | **Classes** | `/classes` | ✅ Complet | Niveaux, effectif max, affectation cours |
| 6 | **Matières** | `/matieres` | ✅ Complet | Coefficients, type (général/tech/sport) |
| 7 | **Emploi du temps** | `/emploi-du-temps` | ✅ Complet | Grille hebdomadaire par classe |
| 8 | **Présences** | `/presences` | ✅ Complet | Appel numérique, rapport absentéisme |
| 9 | **Notes** | `/notes` | ✅ Complet | Saisie par période, calcul moyennes |
| 10 | **Bulletins** | `/bulletins` | ✅ Complet | Génération + impression PDF par élève |
| 11 | **Paiements** | `/paiements` | ✅ Complet | Dossiers financiers, tranches, reçus |
| 12 | **Examens** | `/examens` | ✅ Complet | Programmation épreuves, planning |
| 13 | **Comptabilité** | `/comptabilite` | ✅ Complet | Transactions, bilan annuel, export Excel |
| 14 | **Bibliothèque** | `/bibliotheque` | ✅ Complet | Catalogue, prêts, alertes retard |
| 15 | **Communication** | `/communication` | ✅ Complet | Annonces, messagerie interne, notifications |
| 16 | **Rapports** | `/rapports` | ✅ Complet | Élèves, notes, paiements, présences + exports Excel |
| 17 | **Paramètres** | `/parametres` | ✅ Complet | Années, cycles, niveaux, salles, créneaux |
| 18 | **Profil** | `/profil` | ✅ Complet | Infos personnelles, photo, mot de passe |

---

## Rôles & Permissions (RBAC)

| Rôle | Périmètre |
|------|-----------|
| `superadmin` | Accès total, gestion multi-établissements |
| `admin` | Gestion complète d'un établissement |
| `directeur` | Lecture élargie + validation pédagogique |
| `enseignant` | Saisie notes, emploi du temps personnel |
| `comptable` | Paiements, comptabilité uniquement |
| `secretaire` | Inscriptions, bulletins, communication |

67 permissions granulaires au format `module.action` (ex: `eleves.create`, `notes.saisir`).

---

## Stack technique

| Composant | Version |
|-----------|---------|
| PHP | 8.0+ |
| MySQL | 5.7+ |
| Bootstrap | 5.3 |
| Bootstrap Icons | 1.11 |
| Composer | 2.x |
| vlucas/phpdotenv | 5.6 |
| mpdf/mpdf | 8.2 |
| phpoffice/phpspreadsheet | 2.x |
| phpmailer/phpmailer | 6.9 |
| Architecture | MVC maison (sans framework) |

---

## Helpers disponibles (globaux)

```php
url('/chemin')              // URL absolue depuis APP_URL
asset('/assets/css/app.css')// URL d'un asset public
e($string)                  // Échappe le HTML (XSS)
old('champ', $defaut)       // Repopule les formulaires après erreur
csrf_field()                // Champ CSRF caché dans les formulaires
can('permission')           // Vérifie une permission RBAC
auth()                      // Retourne l'utilisateur connecté
money($montant)             // Ex: "15 000 FCFA"
dateFormat($date)           // Ex: "18 mai 2026"
dateTimeFormat($date)       // Ex: "18 mai 2026 à 14:30"
statutBadge($statut)        // Badge Bootstrap coloré
appreciation($moyenne)      // Ex: "Bien" pour moyenne 14
generateNumeroRecu()        // Numéro unique de reçu de paiement
uploadFile($file, $folder)  // Sauvegarde sécurisée d'un fichier uploadé
```

---

## Phase 2 — Fonctionnalités ajoutées (18/05/2026)

| Fonctionnalité | Fichier(s) |
|----------------|-----------|
| **Logger** (app-YYYY-MM-DD.log) | `app/Core/Logger.php` |
| **Rate limiting** (5 tentatives / 5 min) | `AuthController::login()` |
| **Validation mime-type** uploads | `Helpers/functions.php::uploadFile()` |
| **Email reset** mot de passe + token 1h | `AuthController::sendReset/resetPassword()` |
| **Rapport élèves** (graphiques Chart.js) | `RapportController::eleves()` + `Views/rapports/eleves.php` |
| **Rapport notes** (moyennes par classe/matière) | `RapportController::notes()` + `Views/rapports/notes.php` |
| **Rapport paiements** (taux recouvrement) | `RapportController::paiements()` + `Views/rapports/paiements.php` |
| **Rapport présences** (absentéisme) | `RapportController::presences()` + `Views/rapports/presences.php` |
| **Bilan comptable annuel** | `ComptabiliteController::bilan()` + `Views/comptabilite/bilan.php` |
| **Export Excel comptabilité** | `ComptabiliteController::export()` |
| **Graphiques Chart.js dashboard** | `Views/dashboard/admin.php` |
| **Export Excel élèves** | `EleveController::export()` |
| **Import CSV élèves** | `EleveController::importCsv()` + `Views/eleves/import.php` |
| **Export Excel paiements** | `PaiementController::export()` |
| **Export Excel rapports** | `RapportController::export{Eleves,Notes,Paiements}()` |
| **Audit trail** (activity_logs) | `app/Core/AuditLog.php` intégré dans Notes + Paiements |
| **Table password_resets** | `database/schema.sql` |

---

## Analyse de production — État au 18/05/2026

### ✅ RÉSOLU en Phase 2

| # | Problème | Solution apportée |
|---|----------|------------------|
| 1 | Rate limiting absent | 5 tentatives / 5 min via session dans `AuthController` |
| 2 | Validation mime-type uploads | `finfo_file()` + whitelist mime dans `uploadFile()` |
| 3 | Token reset sans expiration | Table `password_resets` avec `expires_at` (1h) |
| 4 | Email reset non fonctionnel | PHPMailer intégré dans `AuthController::sendReset()` |
| 7 | Aucun système de logs | `app/Core/Logger.php` — fichiers journaliers dans `storage/logs/` |
| 8 | Rapports stub (HTTP 501) | 4 rapports complets + exports Excel |
| 9 | Bilan/export comptable stub | `ComptabiliteController::bilan()` + `::export()` |
| 10 | Aucun audit trail | `app/Core/AuditLog.php` intégré dans Notes et Paiements |

### 🔴 ENCORE CRITIQUE — Avant déploiement production

| # | Problème | Action |
|---|----------|--------|
| 5 | **HTTPS non forcé** | Activer redirect HTTP→HTTPS dans `.htaccess` + `APP_URL` en https |
| 6 | **Mots de passe en clair** dans `seeds/init.sql` | Remplacer par hash bcrypt avant déploiement |
| 11 | **Aucun test automatisé** | Ajouter tests manuels module par module + PHPUnit |
| 12 | **Permissions `storage/`** | `chmod -R 775 storage/` + `chown www-data:www-data storage/` |
| 13 | **Sauvegarde BDD** | Configurer cron MySQL dump quotidien |
| 14 | **SMTP production** | Configurer `.env` avec un vrai serveur SMTP (SendGrid, Mailgun…) |

### 🟠 PHASE 3 — Roadmap future

| # | Fonctionnalité |
|---|----------------|
| 15 | 2FA pour comptes administrateurs |
| 16 | Bulletins PDF avancés (mPDF, mise en page fine) |
| 17 | Notifications temps réel (WebSocket ou polling) |
| 18 | Multi-langue (Français / Anglais) |
| 19 | API REST + PWA mobile |
| 20 | Cache requêtes fréquentes (Redis/APCu) |
| 21 | Module présences avancé (QR code) |

---

## Sécurité en place

- **CSRF** : token sur tous les formulaires POST
- **XSS** : échappement systématique via `e()`
- **SQL Injection** : requêtes préparées PDO uniquement
- **Mots de passe** : bcrypt cost 12 (`password_hash` / `password_verify`)
- **Authentification** : session PHP avec `session_regenerate_id()`
- **Autorisation** : RBAC 67 permissions, vérification sur chaque route
- **Fichiers sensibles** : `.env`, `.sql`, `.log` bloqués par `.htaccess`
- **Logs de connexion** : table `auth_logs` (login, logout, échecs)

---

## Débogage

Activer les erreurs en développement (`public/index.php`) :

```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

En production, mettre `APP_DEBUG=false` dans `.env` — les erreurs doivent aller dans les logs, pas dans le navigateur.

Logs Apache (WampServer) : `C:\wamp64\logs\apache_error.log`

---

## Permissions fichiers (Linux/production)

```bash
chmod -R 755 app/ config/ routes/ public/
chmod -R 775 storage/
chown -R www-data:www-data storage/
```

---

*ERP Scolaire — Phase 2 complète. Dernière mise à jour : 18/05/2026. Tous droits réservés.*
