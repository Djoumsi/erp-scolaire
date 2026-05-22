# Manuel Utilisateur — ERP Scolaire
## Guide complet d'utilisation

> **Version :** Phase 2 | **Date :** Mai 2026 | **Public :** Administrateurs, Enseignants, Comptables, Secrétaires

---

## Table des matières

1. [Accès et connexion](#1-accès-et-connexion)
2. [Interface générale](#2-interface-générale)
3. [Tableau de bord](#3-tableau-de-bord)
4. [Paramètres (configuration initiale)](#4-paramètres--configuration-initiale)
5. [Établissements](#5-établissements)
6. [Gestion des élèves](#6-gestion-des-élèves)
7. [Gestion du personnel](#7-gestion-du-personnel)
8. [Classes](#8-classes)
9. [Emploi du temps](#9-emploi-du-temps)
10. [Présences](#10-présences)
11. [Notes et évaluations](#11-notes-et-évaluations)
12. [Bulletins scolaires](#12-bulletins-scolaires)
13. [Examens](#13-examens)
14. [Paiements](#14-paiements)
15. [Comptabilité](#15-comptabilité)
16. [Bibliothèque](#16-bibliothèque)
17. [Communication](#17-communication)
18. [Rapports et statistiques](#18-rapports-et-statistiques)
19. [Mon profil](#19-mon-profil)
20. [Rôles et permissions](#20-rôles-et-permissions)
21. [Questions fréquentes (FAQ)](#21-questions-fréquentes-faq)

---

## 1. Accès et connexion

### URL d'accès
```
http://[adresse-serveur]/erp-scolaire/public/
```

### Se connecter
1. Aller sur la page de connexion : `/login`
2. Saisir votre **identifiant de connexion** (login) et votre **mot de passe**
3. Cliquer sur **Se connecter**

> **Sécurité :** Après 5 tentatives échouées consécutives, votre accès est bloqué pendant 5 minutes.

### Mot de passe oublié
1. Cliquer sur **Mot de passe oublié ?** sur la page de connexion
2. Saisir votre identifiant de connexion
3. Un email est envoyé à votre adresse si elle est enregistrée
4. Cliquer sur le lien dans l'email (valable **1 heure**)
5. Saisir et confirmer votre nouveau mot de passe (minimum 8 caractères)

> Si vous n'avez pas d'adresse email enregistrée, contactez votre administrateur pour réinitialiser votre mot de passe.

### Comptes par défaut (à changer impérativement)
| Rôle | Login | Mot de passe par défaut |
|------|-------|------------------------|
| Super Administrateur | `superadmin` | `Admin@1234` |
| Administrateur | `admin` | `Admin@1234` |

### Se déconnecter
Cliquer sur votre nom en haut à droite → **Déconnexion**

---

## 2. Interface générale

### Structure de l'écran

```
┌──────────────────────────────────────────────────────┐
│  BARRE SUPÉRIEURE (topbar)                           │
│  [≡ Menu]          [🔔 Notifs]  [👤 Profil]  [→ Exit]│
├───────────┬──────────────────────────────────────────┤
│           │                                          │
│ BARRE     │  CONTENU DE LA PAGE                      │
│ LATÉRALE  │                                          │
│ (sidebar) │  Fil d'Ariane (breadcrumb)               │
│           │  ─────────────────────────               │
│ • Dashboard│  Titre de la page                       │
│ • Élèves  │                                          │
│ • Classes │  [Contenu principal]                     │
│ • Notes   │                                          │
│ • ...     │                                          │
│           │                                          │
└───────────┴──────────────────────────────────────────┘
```

### Navigation
- **Barre latérale (sidebar)** : Menu principal — cliquer sur un élément pour naviguer
- **Fil d'Ariane** : Indique votre position dans l'application (ex. : Accueil > Élèves > Jean Dupont)
- **Icône ≡** (burger) : Réduire/afficher la barre latérale sur mobile ou petit écran
- **🔔 Cloche** : Vos notifications non lues
- **Nom en haut à droite** : Accès à votre profil et déconnexion

### Messages système
- **Bandeau vert** : Action réalisée avec succès
- **Bandeau rouge** : Erreur — lire le message pour comprendre ce qui s'est passé
- **Bandeau jaune** : Avertissement — action possible mais attention requise

### Menus visibles selon votre rôle
Vous ne verrez dans le menu que les modules auxquels vous avez accès. Un enseignant ne verra pas la comptabilité, un comptable ne verra pas les notes, etc.

---

## 3. Tableau de bord

Le tableau de bord est la première page après la connexion. Il s'adapte automatiquement à votre rôle.

### Dashboard Administrateur / Directeur

**4 compteurs en haut :**
- Nombre d'élèves inscrits cette année
- Nombre de membres du personnel
- Nombre de classes
- Montant encaissé ce mois (en FCFA)

**Alertes automatiques :**
- Absences enregistrées aujourd'hui (lien vers Présences)
- Paiements en retard (tranches échues non payées)

**Graphiques :**
- Évolution des encaissements sur 6 mois
- Répartition garçons / filles

**Tableaux :**
- 5 dernières inscriptions avec nom, classe, date
- Annonces récentes de l'établissement

**Actions rapides :**
- Inscrire un élève, Encaisser un paiement, Faire l'appel, Saisir des notes, Bulletins

### Dashboard Super Administrateur

- Total établissements actifs
- Total utilisateurs
- Liste des établissements avec leurs effectifs

### Dashboard Enseignant

- Mêmes stats globales + liste de ses cours assignés (classe, matière)

### Aucune année scolaire
Si aucune année scolaire n'est configurée comme "en cours", un message d'avertissement s'affiche avec un lien vers les Paramètres.

---

## 4. Paramètres — Configuration initiale

> **Accessible :** Administrateur, Directeur
> **URL :** `/parametres`

Les paramètres doivent être configurés **avant toute utilisation** du système. Voici l'ordre recommandé.

### 4.1 Année scolaire

**Créer une année scolaire :**
1. Dans la section "Années scolaires", remplir : Libellé (ex. : `2025-2026`), Date début, Date fin
2. Cliquer **Ajouter**

**Activer une année scolaire :**
- Cliquer le bouton **Activer** à côté de l'année → elle devient "En cours"
- Une seule année peut être active à la fois

> Toutes les données (inscriptions, notes, paiements) sont liées à l'année scolaire active.

### 4.2 Cycles d'enseignement

Exemples de cycles : Primaire, Collège, Lycée, Université

1. Saisir le nom du cycle
2. Cliquer **Ajouter**
3. Répéter pour chaque cycle

### 4.3 Niveaux

Les niveaux appartiennent à un cycle. Exemples : CP1, CP2, CE1, CE2, CM1, CM2 (pour le Primaire) ou 6ème, 5ème, 4ème, 3ème (pour le Collège).

1. Choisir le cycle dans la liste
2. Saisir le nom du niveau et son ordre d'affichage
3. Cliquer **Ajouter**

### 4.4 Périodes

Les périodes correspondent aux trimestres ou semestres de l'année.
Exemples : Trimestre 1, Trimestre 2, Trimestre 3

1. Saisir le libellé, les dates de début et fin, l'ordre
2. Cliquer **Ajouter**

### 4.5 Types de frais

Exemples : Frais de scolarité, Frais d'inscription, Frais d'examen, Cantine

1. Saisir le nom du type de frais
2. Cliquer **Ajouter**

### 4.6 Types d'évaluation

Exemples : Devoir surveillé, Composition, Interrogation orale, TP

1. Saisir le nom, le coefficient par défaut
2. Cliquer **Ajouter**

### 4.7 Salles

Les salles de classe ou d'examen.

1. Saisir le nom (ex. : Salle A, Amphi 1), la capacité
2. Cliquer **Ajouter**

### 4.8 Créneaux horaires

Les plages horaires de l'emploi du temps.
Exemples : 7h30-8h30, 8h30-9h30, etc.

1. Saisir l'heure de début et de fin, le libellé, le jour (ou laisser vide pour tous les jours)
2. Cliquer **Ajouter**

### 4.9 Matières

Les matières enseignées dans l'établissement.

1. Saisir le nom de la matière
2. Définir le coefficient et le type (Général, Technique, Sport, Artistique)
3. Cliquer **Ajouter**

---

## 5. Établissements

> **Accessible :** Super Administrateur uniquement
> **URL :** `/etablissements`

### Créer un établissement
1. Cliquer **Nouvel établissement**
2. Remplir : Nom, Code établissement (ex. : `LYC-ABJ`), Type (Primaire/Collège/Lycée/Université), Adresse, Téléphone, Email
3. Téléverser un logo (optionnel, formats : JPG, PNG, WEBP, max 5 Mo)
4. Créer le compte administrateur principal : Login, Mot de passe, Prénom, Nom, Email
5. Cliquer **Enregistrer**

### Modifier un établissement
1. Cliquer sur le nom de l'établissement dans la liste
2. Cliquer **Modifier**
3. Modifier les champs souhaités
4. Cliquer **Mettre à jour**

### Consulter un établissement
En cliquant sur un établissement, vous voyez :
- Ses informations générales
- Ses années scolaires
- La liste de ses utilisateurs

---

## 6. Gestion des élèves

> **URL :** `/eleves`

### 6.1 Liste des élèves

La liste affiche tous les élèves inscrits à l'année scolaire en cours.

**Filtres disponibles :**
- **Recherche** (barre en haut) : par nom, prénom ou matricule
- **Filtre par classe** : sélectionner une classe dans la liste déroulante

### 6.2 Inscrire un élève

1. Cliquer **Inscrire un élève**
2. Remplir les informations obligatoires :
   - Nom, Prénoms, Sexe (M/F), Date de naissance
3. Informations optionnelles :
   - Lieu de naissance, Nationalité, Adresse, Téléphone, Email
   - Parent 1 : Nom, Téléphone, Email, Profession
   - Parent 2 : Nom, Téléphone, Email
   - Groupe sanguin, Notes médicales
   - Photo (JPG/PNG/WEBP, max 5 Mo)
4. Choisir la **classe** d'inscription (si une année scolaire est active)
5. Cliquer **Inscrire**

Un **matricule unique** est généré automatiquement (format : `CODE-ANNEE-NUMÉRO`).
Un **dossier de paiement** est créé automatiquement à l'inscription.

### 6.3 Fiche élève

Cliquer sur un élève pour voir :
- Ses informations personnelles et contacts parents
- Son historique d'inscriptions (toutes les années)
- Ses derniers paiements
- Ses dernières notes

### 6.4 Modifier un élève

1. Ouvrir la fiche de l'élève
2. Cliquer **Modifier**
3. Modifier les informations
4. Possibilité de changer de classe en cours d'année (champ "Nouvelle classe")
5. Cliquer **Mettre à jour**

### 6.5 Supprimer un élève

La suppression est une **suppression douce** (soft delete) : l'élève disparaît des listes mais ses données sont conservées en base de données.

### 6.6 Exporter les élèves en Excel

1. Aller dans la liste des élèves
2. Cliquer **Exporter Excel** (bouton vert en haut)
3. Le fichier `Eleves_XXXX-XXXX_YYYYMMDD.xlsx` se télécharge automatiquement
4. Il contient toutes les colonnes : matricule, identité, contacts, parents, classe

### 6.7 Importer des élèves depuis un CSV

Pratique pour inscrire un grand nombre d'élèves en une seule fois.

1. Cliquer **Importer CSV**
2. Sélectionner la classe de destination (optionnel)
3. Préparer votre fichier CSV avec les colonnes suivantes (séparées par `;`) :
   ```
   nom;prenoms;sexe;date_naissance;lieu_naissance;telephone;parent1_nom;parent1_telephone
   KONÉ;Aminata;F;2008-03-15;Abidjan;0708090010;Koné Paul;0101020304
   DIALLO;Moussa;M;2007-11-22;Bouaké;;Diallo Ibrahim;
   ```
   > La première ligne est l'en-tête et sera ignorée. Les colonnes 4 à 8 sont optionnelles.
4. Sélectionner le fichier et cliquer **Lancer l'import**
5. Un bilan s'affiche : nombre d'élèves importés, lignes ignorées avec raison

---

## 7. Gestion du personnel

> **URL :** `/personnel`

### 7.1 Ajouter un membre du personnel

1. Cliquer **Ajouter du personnel**
2. Informations obligatoires :
   - Nom, Prénoms, Sexe, Type (Enseignant, Directeur, Surveillant, Comptable, Secrétaire, Autre)
3. Informations professionnelles :
   - Matricule interne, Spécialité, Date de prise de service, Statut (Permanent/Contractuel/Vacataire)
4. Créer le **compte de connexion** :
   - Login (identifiant unique), Email, Rôle dans le système, Mot de passe initial
5. Photo (optionnel)
6. Cliquer **Enregistrer**

### 7.2 Fiche personnel

En cliquant sur un membre du personnel :
- Ses informations professionnelles
- Ses **cours assignés** (classes et matières)

### 7.3 Modifier / Supprimer

Même fonctionnement que pour les élèves.

---

## 8. Classes

> **URL :** `/classes`

### 8.1 Créer une classe

1. Cliquer **Créer une classe**
2. Remplir :
   - Nom de la classe (ex. : `6ème A`, `Terminale D`)
   - Niveau (choix dans la liste des niveaux configurés)
   - Effectif maximum (capacité de la salle)
3. Cliquer **Enregistrer**

### 8.2 Fiche d'une classe

La fiche classe affiche :
- Informations générales (niveau, effectif actuel / maximum)
- **Cours assignés** : matières + enseignants
- **Liste des élèves** inscrits dans cette classe

### 8.3 Affecter des cours à une classe

Pour qu'un enseignant puisse saisir des notes pour une classe, il faut d'abord affecter le cours.

1. Ouvrir la fiche de la classe
2. Dans la section "Affecter un cours", choisir :
   - La matière
   - L'enseignant responsable
   - Le coefficient pour cette classe
3. Cliquer **Affecter**

---

## 9. Emploi du temps

> **URL :** `/emploi-du-temps`

### 9.1 Vue d'ensemble

La page principale liste toutes les classes. Cliquer sur une classe pour voir son emploi du temps.

### 9.2 Grille hebdomadaire

L'emploi du temps est présenté sous forme de tableau :
- **Colonnes** : jours de la semaine (Lundi → Vendredi, Samedi si configuré)
- **Lignes** : créneaux horaires

Les cases remplies affichent : la matière, l'enseignant, la salle.

### 9.3 Ajouter un cours dans la grille

1. Ouvrir l'emploi du temps d'une classe
2. Cliquer le bouton **Ajouter un cours** ou cliquer sur une case vide
3. Dans le formulaire, choisir :
   - Le cours (affectation matière/enseignant déjà créée)
   - Le jour
   - Le créneau horaire
   - La salle
4. Cliquer **Ajouter**

### 9.4 Supprimer un cours

Cliquer l'icône **🗑️** sur la case à supprimer.

---

## 10. Présences

> **URL :** `/presences`
> **Accessible :** Enseignants, Administrateurs

### 10.1 Faire l'appel

1. Dans la liste des cours/affectations, cliquer **Faire l'appel** sur le cours concerné
2. La liste de tous les élèves de la classe s'affiche
3. Pour chaque élève, cocher son statut :
   - **Présent** (par défaut)
   - **Absent**
   - **Retard**
   - **Excusé**
4. Cliquer **Enregistrer l'appel**

### 10.2 Rapport d'absences

Accéder aux statistiques d'absentéisme :
- Taux d'absence par élève
- Nombre d'absences, retards, excusés
- Classement du plus absent au moins absent

---

## 11. Notes et évaluations

> **URL :** `/notes`
> **Accessible :** Enseignants (leurs cours uniquement), Administrateurs, Directeurs

### 11.1 Vue d'ensemble

La liste affiche :
- Pour un **enseignant** : uniquement ses cours (classe + matière + période)
- Pour un **administrateur** : tous les cours de l'établissement

### 11.2 Créer une évaluation

Avant de saisir des notes, il faut créer l'évaluation correspondante.

1. Cliquer **Saisir les notes** sur un cours
2. Cliquer **+ Nouvelle évaluation**
3. Remplir :
   - Titre (ex. : `Devoir n°1`, `Composition du 1er trimestre`)
   - Type d'évaluation (Devoir surveillé, Composition, etc.)
   - Date
   - Note sur (ex. : 20)
   - Coefficient
4. Cliquer **Créer l'évaluation**

### 11.3 Saisir les notes

1. Cliquer **Saisir les notes** sur un cours
2. Choisir la période (Trimestre 1, 2, 3) en haut de page
3. Choisir l'évaluation dans la liste
4. Pour chaque élève, saisir sa note dans le champ correspondant
   - Laisser vide = élève absent à l'évaluation
5. Cliquer **Enregistrer les notes**

> Les notes peuvent être modifiées à tout moment en resaisissant dans le même formulaire.

### 11.4 Calcul des moyennes

Les moyennes sont calculées automatiquement lors de la génération des bulletins. Le calcul prend en compte les coefficients des matières.

**Formule :** `Moyenne = Σ(note_matière × coefficient) / Σ(coefficients)`

---

## 12. Bulletins scolaires

> **URL :** `/bulletins`
> **Accessible :** Administrateurs, Secrétaires, Directeurs

### 12.1 Générer les bulletins d'une classe

1. Aller dans **Bulletins**
2. Choisir la **classe** et la **période** dans le formulaire
3. Cliquer **Générer les bulletins**
4. Le système calcule toutes les moyennes (matières + générale) et les rangs
5. Les bulletins apparaissent dans la liste sous la classe

### 12.2 Imprimer un bulletin

1. Cliquer sur le nom de l'élève dans la liste des bulletins
2. Cliquer **Imprimer / PDF**
3. La page s'ouvre en format impression avec :
   - En-tête de l'établissement
   - Informations de l'élève
   - Tableau de toutes les matières avec notes et moyennes
   - Moyenne générale, rang, effectif de la classe
   - Appréciation globale
   - 3 zones de signature (directeur, enseignant principal, parent)
4. Utiliser `Ctrl+P` (ou Cmd+P sur Mac) pour imprimer ou enregistrer en PDF

---

## 13. Examens

> **URL :** `/examens`
> **Accessible :** Administrateurs, Directeurs

### 13.1 Créer un examen

1. Cliquer **Créer un examen**
2. Remplir :
   - Nom de l'examen (ex. : `Baccalauréat 2026`, `BEPC Session 1`)
   - Type (Examen interne, Examen officiel, Concours)
   - Période scolaire associée
   - Date de début et de fin
3. Cliquer **Enregistrer**

### 13.2 Planifier les épreuves

1. Ouvrir la fiche de l'examen
2. Cliquer **+ Ajouter une épreuve**
3. Choisir :
   - La matière
   - Le niveau concerné
   - La date et l'heure
   - La durée
   - La salle
4. Cliquer **Ajouter**

Le planning s'affiche sous forme de tableau récapitulatif.

---

## 14. Paiements

> **URL :** `/paiements`
> **Accessible :** Comptables, Administrateurs, Secrétaires (selon permissions)

### 14.1 Dossiers de paiement

Chaque élève inscrit possède automatiquement un **dossier de paiement**. La liste affiche :
- Nom et matricule de l'élève
- Sa classe
- Montant total attendu
- Montant déjà payé
- Statut : `Non payé`, `Partiel`, `Soldé`, `Exonéré`

**Recherche :** par nom, prénom ou matricule
**Filtre :** par statut (Non payé, Partiel, Soldé)

### 14.2 Encaisser un paiement

1. Trouver le dossier de l'élève dans la liste
2. Cliquer **Encaisser**
3. La page affiche :
   - Résumé financier (attendu / payé / reste)
   - Barre de progression du recouvrement
   - Liste des tranches non payées (si configurées)
   - Formulaire d'encaissement
4. Remplir le formulaire :
   - Montant encaissé
   - Mode de paiement (Espèces, Mobile Money, Virement, Chèque)
   - Référence de transaction (optionnel, pour Mobile Money/virement)
   - Tranche associée (optionnel)
   - Observation
5. Cliquer **Enregistrer le paiement**

Un **numéro de reçu unique** est généré automatiquement (ex. : `LYC-2026-000042`).
Vous êtes redirigé vers le reçu imprimable.

### 14.3 Imprimer un reçu

Après un encaissement, ou en cliquant **Reçu** sur un paiement existant :
- La page de reçu s'affiche avec en-tête établissement, détails du paiement, numéro de reçu
- Cliquer `Ctrl+P` pour imprimer

### 14.4 Annuler un paiement

En cas d'erreur de saisie :
1. Ouvrir le détail du paiement
2. Cliquer **Annuler ce paiement**
3. Confirmer

> L'annulation est tracée dans l'audit trail. Le montant payé du dossier est automatiquement mis à jour.

### 14.5 Exporter les paiements en Excel

1. Cliquer **Exporter Excel** dans la liste des paiements
2. Le fichier contient : matricule, nom, classe, montants, statut, numéros de reçus, dates

---

## 15. Comptabilité

> **URL :** `/comptabilite`
> **Accessible :** Comptables, Administrateurs

### 15.1 Tableau du mois

La page principale affiche les transactions du mois sélectionné :
- **Filtre par mois** : sélectionner un mois dans le sélecteur en haut
- **3 cartes statistiques** : Total recettes, Total dépenses, Solde
- **Tableau des transactions** : date, catégorie, libellé, référence, montant

### 15.2 Enregistrer une transaction

1. Remplir le formulaire sur la droite :
   - Catégorie (Scolarité, Salaires, Fournitures, etc.)
   - Libellé (description de la transaction)
   - Montant
   - Date
   - Référence (numéro de pièce, optionnel)
2. Cliquer **Enregistrer**

> Les paiements de scolarité encaissés via le module Paiements génèrent automatiquement une transaction comptable dans la catégorie "Scolarité".

### 15.3 Bilan annuel

1. Cliquer **Bilan annuel** dans la barre de navigation
2. Le bilan affiche :
   - 3 KPIs : Total recettes, Total dépenses, Solde (bénéfice ou déficit)
   - Graphique à barres : évolution mensuelle recettes vs dépenses
   - Graphique doughnut : répartition des dépenses par catégorie
   - Tableau récapitulatif mois par mois

### 15.4 Exporter la comptabilité en Excel

1. Depuis le bilan ou le tableau mensuel, cliquer **Exporter Excel**
2. Le fichier contient toutes les transactions avec totaux finaux

---

## 16. Bibliothèque

> **URL :** `/bibliotheque`
> **Accessible :** Administrateurs, Secrétaires

### 16.1 Catalogue des livres

Le tableau "Catalogue" liste tous les livres avec :
- Titre, Auteur, ISBN
- Nombre d'exemplaires disponibles / total
- Statut (Disponible, Emprunté, Indisponible)

### 16.2 Ajouter un livre

1. Cliquer **+ Ajouter un livre**
2. Remplir : Titre, Auteur, ISBN, Éditeur, Année, Nombre d'exemplaires, Catégorie
3. Cliquer **Enregistrer**

### 16.3 Enregistrer un emprunt

1. Cliquer **+ Emprunter**
2. Sélectionner :
   - Le livre à emprunter
   - L'emprunteur (élève ou personnel)
   - La date de retour prévue
3. Cliquer **Enregistrer l'emprunt**

### 16.4 Retourner un livre

1. Dans le tableau "Emprunts en cours", trouver le prêt
2. Cliquer **Retour** sur la ligne correspondante

Les emprunts dont la date de retour est dépassée sont **surlignés en rouge** (alerte retard).

---

## 17. Communication

### 17.1 Annonces

> **URL :** `/annonces`

Les annonces sont visibles par tous les utilisateurs connectés (selon la cible).

**Créer une annonce :**
1. Cliquer **Nouvelle annonce**
2. Remplir :
   - Titre
   - Contenu (message complet)
   - Priorité : Normale, Importante, Urgente
   - Cible : Tous, Enseignants, Parents, Élèves, Personnel administratif
   - Classe concernée (optionnel, pour une annonce ciblée)
   - Date d'expiration (optionnel — l'annonce disparaît automatiquement)
3. Cliquer **Publier**

### 17.2 Messagerie interne

> **URL :** `/messages`

Système de messages entre utilisateurs de l'établissement.

**Envoyer un message :**
1. Cliquer **Nouveau message**
2. Choisir le destinataire dans la liste
3. Saisir le sujet (optionnel) et le message
4. Cliquer **Envoyer**

**Messages non lus** : affichés en **gras** dans la liste.

### 17.3 Notifications

> **URL :** `/notifications`

Les notifications sont générées automatiquement par le système (nouveau message reçu, paiement enregistré, etc.).

- **Badge rouge** sur la cloche en haut : nombre de notifications non lues
- Cliquer la cloche pour voir les 5 dernières
- Cliquer "Voir tout" pour la liste complète

---

## 18. Rapports et statistiques

> **URL :** `/rapports`
> **Accessible :** Administrateurs, Directeurs

Tous les rapports incluent des **graphiques Chart.js** et un bouton **Exporter Excel**.

### 18.1 Rapport Élèves

**URL :** `/rapports/eleves`

Affiche :
- Total inscrits, répartition garçons/filles avec pourcentages
- Tableau des effectifs par classe (total, garçons, filles, % filles)
- Graphique doughnut : répartition par sexe
- Graphique ligne : évolution des inscriptions par mois

**Export Excel :** contient toutes les données de chaque élève inscrit.

### 18.2 Rapport Notes

**URL :** `/rapports/notes`

Filtrer par période (Trimestre 1, 2, 3).

Affiche :
- Moyennes générales par classe avec taux d'admission
- Graphique barres : moyennes (vert si ≥ 10, rouge si < 10)
- Tableau détaillé par classe et par matière (moyenne, min, max, nb admis)

**Export Excel :** moyennes par classe et matière.

### 18.3 Rapport Paiements

**URL :** `/rapports/paiements`

Affiche :
- Total encaissé, total attendu, taux de recouvrement global, nombre de dossiers en retard
- Graphique barres : encaissements par mois
- Barre de progression du recouvrement global
- Tableau par classe : attendu, encaissé, reste, barre de progression

**Export Excel :** état détaillé par dossier (attendu, payé, reste, statut).

### 18.4 Rapport Présences

**URL :** `/rapports/presences`

Filtrer par classe.

Affiche :
- Tableau des élèves : total séances, absences, retards, excusés, taux d'absence
  - **Rouge** : taux > 20% (alerte critique)
  - **Jaune** : taux > 10% (surveillance)
- Absentéisme par matière avec barres de progression
- Graphique horizontal : top 8 des élèves les plus absents

---

## 19. Mon profil

> **URL :** `/profil`

### 19.1 Modifier mes informations

1. Modifier vos prénom(s), nom, email, téléphone
2. Changer votre photo de profil (JPG/PNG/WEBP, max 5 Mo)
3. Cliquer **Mettre à jour**

### 19.2 Changer mon mot de passe

1. Saisir votre mot de passe actuel
2. Saisir le nouveau mot de passe (minimum 6 caractères)
3. Confirmer le nouveau mot de passe
4. Cliquer **Changer le mot de passe**

> Si vous avez oublié votre mot de passe actuel, utilisez la fonctionnalité "Mot de passe oublié" sur la page de connexion.

---

## 20. Rôles et permissions

Chaque utilisateur a un rôle qui détermine ce qu'il peut voir et faire.

| Rôle | Ce qu'il peut faire |
|------|---------------------|
| **Super Admin** | Tout — gère tous les établissements |
| **Admin** | Tout dans son établissement |
| **Directeur** | Consultation élargie, validation, bulletins |
| **Enseignant** | Ses notes, présences, emploi du temps, messagerie |
| **Comptable** | Paiements, comptabilité, rapports financiers |
| **Secrétaire** | Inscriptions élèves, bulletins, communication, bibliothèque |

### Ce que voit chaque rôle dans le menu

| Module | Super Admin | Admin | Directeur | Enseignant | Comptable | Secrétaire |
|--------|:-----------:|:-----:|:---------:|:----------:|:---------:|:----------:|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Établissements | ✅ | — | — | — | — | — |
| Élèves | ✅ | ✅ | ✅ | — | — | ✅ |
| Personnel | ✅ | ✅ | ✅ | — | — | — |
| Classes | ✅ | ✅ | ✅ | — | — | — |
| Emploi du temps | ✅ | ✅ | ✅ | ✅ | — | — |
| Présences | ✅ | ✅ | ✅ | ✅ | — | — |
| Notes | ✅ | ✅ | ✅ | ✅ | — | — |
| Bulletins | ✅ | ✅ | ✅ | — | — | ✅ |
| Examens | ✅ | ✅ | ✅ | — | — | — |
| Paiements | ✅ | ✅ | — | — | ✅ | ✅ |
| Comptabilité | ✅ | ✅ | — | — | ✅ | — |
| Bibliothèque | ✅ | ✅ | ✅ | — | — | ✅ |
| Communication | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Rapports | ✅ | ✅ | ✅ | — | ✅ | — |
| Paramètres | ✅ | ✅ | ✅ | — | — | — |

---

## 21. Questions fréquentes (FAQ)

### Connexion

**Q : Je n'arrive pas à me connecter, le système me bloque.**
R : Après 5 tentatives échouées, l'accès est bloqué 5 minutes. Attendez puis réessayez. Si le problème persiste, contactez votre administrateur.

**Q : J'ai oublié mon mot de passe et je n'ai pas d'email enregistré.**
R : Contactez votre administrateur. Il peut réinitialiser votre mot de passe directement depuis la gestion des utilisateurs.

### Élèves

**Q : Le matricule d'un élève est mal généré.**
R : Le matricule est généré automatiquement à partir du code établissement et de l'année scolaire. Vérifiez que l'année scolaire est bien activée dans les Paramètres.

**Q : Je veux transférer un élève dans une autre classe.**
R : Aller dans la fiche de l'élève → Modifier → changer le champ "Classe".

**Q : L'import CSV ne fonctionne pas.**
R : Vérifiez que : (1) le fichier est bien en UTF-8, (2) les colonnes sont séparées par des `;` et non des `,`, (3) la ligne d'en-tête est présente, (4) le sexe est `M` ou `F` (majuscule).

### Notes

**Q : Un enseignant ne voit pas ses cours dans le module Notes.**
R : Les cours doivent d'abord être **affectés** dans la fiche de la classe (Classes → Fiche classe → Affecter un cours). L'enseignant doit être sélectionné comme responsable.

**Q : Comment annuler une note saisie par erreur ?**
R : Revenir dans le formulaire de saisie du même cours/évaluation. Laisser le champ vide pour marquer l'élève absent, ou resaisir la valeur correcte.

**Q : Les bulletins ne se génèrent pas.**
R : Vérifiez que : (1) des évaluations ont été créées et des notes saisies, (2) une période est bien configurée dans les Paramètres, (3) l'année scolaire est active.

### Paiements

**Q : J'ai enregistré un paiement par erreur. Comment faire ?**
R : Ouvrir le détail du paiement → **Annuler ce paiement**. L'annulation est tracée, le dossier est mis à jour automatiquement.

**Q : Le dossier de paiement d'un élève n'a pas de montant attendu (0 FCFA).**
R : Le montant attendu doit être configuré dans le dossier. Aller dans Paiements → trouver le dossier → le modifier pour indiquer le montant total et créer les tranches.

### Exportations

**Q : L'export Excel ne se lance pas.**
R : Vérifiez que les dépendances Composer sont installées (`composer install`). Si le problème persiste, vérifiez les logs dans `storage/logs/`.

**Q : Comment voir les logs d'activité ?**
R : Les logs se trouvent dans `storage/logs/app-YYYY-MM-DD.log`. Ils enregistrent les connexions, exports, annulations de paiements, et événements de sécurité.

### Technique

**Q : La page affiche une erreur "Vue introuvable".**
R : Contacter l'administrateur technique — un fichier de vue est manquant.

**Q : La session expire rapidement.**
R : La durée de session est configurable dans `php.ini` (`session.gc_maxlifetime`). Par défaut PHP = 24 minutes d'inactivité.

**Q : Comment sauvegarder la base de données ?**
R : Utiliser phpMyAdmin → Export, ou en ligne de commande :
```bash
mysqldump -u root -p erp_scolaire > backup_$(date +%Y%m%d).sql
```

---

## Procédure de démarrage d'année scolaire

Voici l'ordre des opérations pour bien démarrer une nouvelle année scolaire :

```
1. Paramètres
   ├── Créer la nouvelle année scolaire (ex. : 2026-2027)
   ├── L'activer
   ├── Vérifier/ajouter les niveaux et cycles
   ├── Créer les nouvelles périodes (Trimestre 1, 2, 3)
   └── Vérifier les créneaux horaires et salles

2. Classes
   ├── Créer les classes de la nouvelle année
   └── Affecter les cours (matière + enseignant) à chaque classe

3. Personnel
   └── Mettre à jour les informations et créer les nouveaux comptes

4. Élèves
   ├── Inscrire les nouveaux élèves (ou importer en CSV)
   └── Réinscrire les anciens élèves (changer d'année dans leur fiche)

5. Emploi du temps
   └── Remplir la grille pour chaque classe

6. Paiements
   └── Configurer les dossiers de paiement (montants et tranches)

7. Communication
   └── Publier une annonce de rentrée
```

---

*ERP Scolaire — Manuel Utilisateur v2.0 | Mai 2026*
*Pour toute assistance technique, contacter l'administrateur système.*
