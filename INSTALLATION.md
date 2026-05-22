# ERP Scolaire — Guide d'installation

## Prérequis
- PHP >= 8.1 (extensions : PDO, pdo_mysql, mbstring, json, fileinfo)
- MySQL >= 8.0
- Apache avec mod_rewrite activé
- Composer

## Installation pas à pas

### 1. Copier le projet dans le dossier web
```
C:\xampp\htdocs\erp-scolaire\
```
Le document root doit pointer sur `erp-scolaire/public/`.

### 2. Installer les dépendances PHP
```bash
composer install
```

### 3. Configurer l'environnement
```bash
cp .env.example .env
```
Éditer `.env` :
```
APP_URL=http://localhost/erp-scolaire/public
DB_HOST=localhost
DB_DATABASE=erp_scolaire
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Créer la base de données
```sql
-- Dans phpMyAdmin ou MySQL Workbench :
SOURCE database/schema.sql;
SOURCE database/seeds/init.sql;
```

### 5. Générer le vrai hash du mot de passe super admin
```php
// Dans un fichier PHP temporaire :
echo password_hash('Admin@2025', PASSWORD_BCRYPT, ['cost' => 12]);
```
Puis mettre à jour dans `seeds/init.sql` avant l'import, ou faire :
```sql
UPDATE users SET password = '$2y$12$VOTRE_HASH_ICI' WHERE login = 'superadmin';
```

### 6. Donner les permissions d'écriture
```
storage/logs/       → écriture
storage/uploads/    → écriture
storage/exports/    → écriture
storage/cache/      → écriture
```

### 7. Configuration Apache (Virtual Host recommandé)
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/erp-scolaire/public"
    ServerName erp.local
    <Directory "C:/xampp/htdocs/erp-scolaire/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## Première utilisation

1. Ouvrir `http://localhost/erp-scolaire/public` (ou `http://erp.local`)
2. Se connecter avec : **login** `superadmin` / **mot de passe** `Admin@2025`
3. Créer un établissement : Menu → Établissements → Nouvel établissement
4. Configurer les paramètres : Menu → Paramètres
   - Créer une année scolaire et l'activer
   - Créer les cycles et niveaux
   - Créer les matières
   - Créer les créneaux horaires
   - Créer les types de frais
   - Créer les périodes (trimestres/semestres)
5. Ajouter le personnel enseignant
6. Créer les classes et les affecter aux niveaux
7. Inscrire les élèves

## Structure du projet
```
erp-scolaire/
├── app/
│   ├── Controllers/    ← Un controller par module
│   ├── Core/           ← Router, Database, Auth, Model, View…
│   ├── Helpers/        ← Fonctions globales
│   └── Views/          ← Templates PHP (layouts + modules)
├── config/             ← app.php, database.php
├── database/
│   ├── schema.sql      ← Toutes les tables
│   └── seeds/init.sql  ← Données initiales
├── public/             ← DOCUMENT ROOT (index.php + assets)
├── routes/web.php      ← Toutes les routes
├── storage/            ← Uploads, logs, cache
└── .env                ← Configuration locale
```

## Identifiants par défaut
| Rôle | Login | Mot de passe |
|------|-------|-------------|
| Super Admin | superadmin | Admin@2025 |

⚠️ **Changer le mot de passe immédiatement après la première connexion.**

## Dépendances installées
- `vlucas/phpdotenv` — Variables d'environnement
- `mpdf/mpdf` — Génération de bulletins PDF
- `phpoffice/phpspreadsheet` — Exports Excel
- `phpmailer/phpmailer` — Envoi d'emails

## Modules disponibles (Phase 1 — MVP)
- ✅ Authentification & RBAC (6 rôles)
- ✅ Gestion établissements (super admin)
- ✅ Paramètres (années, cycles, niveaux, matières, salles, frais)
- ✅ Élèves — inscription, dossier, recherche
- ✅ Personnel — enseignants et administratif
- ✅ Classes — création, affectation cours
- ✅ Emploi du temps — grille hebdomadaire
- ✅ Présences — appel, enregistrement
- ✅ Notes — saisie par évaluation
- ✅ Bulletins — génération, calcul moyennes/rangs
- ✅ Paiements — encaissement, reçu imprimable
- ✅ Comptabilité — recettes/dépenses
- ✅ Examens — planification
- ✅ Bibliothèque — livres, emprunts
- ✅ Communication — annonces, messagerie, notifications
- ✅ Tableaux de bord par rôle

## Phase 2 (prochainement)
- Bulletins PDF complets (mPDF)
- Export Excel pour toutes les listes
- Rapports et statistiques avancés
- Notifications email automatiques (absences, paiements)
- Import CSV élèves
