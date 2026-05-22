-- ============================================================
-- ERP SCOLAIRE - Schéma Base de Données Complet
-- Compatible : Primaire, Collège, Lycée, Université
-- Encodage : UTF-8 | Moteur : InnoDB
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

DROP DATABASE IF EXISTS erp_scolaire;
CREATE DATABASE erp_scolaire
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE erp_scolaire;

-- ============================================================
-- BLOC 1 — AUTH, RÔLES, PERMISSIONS
-- ============================================================

CREATE TABLE roles (
    id         TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom        VARCHAR(50)  NOT NULL UNIQUE,
    label      VARCHAR(100) NOT NULL,
    niveau     TINYINT      NOT NULL DEFAULT 0,
    created_at DATETIME     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE permissions (
    id     SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module VARCHAR(50) NOT NULL,
    action VARCHAR(50) NOT NULL,
    label  VARCHAR(150) NOT NULL,
    UNIQUE KEY uk_perm (module, action)
) ENGINE=InnoDB;

CREATE TABLE role_permissions (
    role_id       TINYINT UNSIGNED  NOT NULL,
    permission_id SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id)       REFERENCES roles(id)       ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 2 — ÉTABLISSEMENTS
-- ============================================================

CREATE TABLE etablissements (
    id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom                VARCHAR(200) NOT NULL,
    type               ENUM('primaire','college','lycee','universite') NOT NULL,
    logo               VARCHAR(255) NULL,
    adresse            TEXT,
    telephone          VARCHAR(20),
    email              VARCHAR(191),
    site_web           VARCHAR(255),
    code_etablissement VARCHAR(30) UNIQUE,
    devise             CHAR(3)      DEFAULT 'XOF',
    pays               VARCHAR(100) DEFAULT 'Côte d\'Ivoire',
    actif              TINYINT(1)   DEFAULT 1,
    created_at         DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at         DATETIME     ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 3 — UTILISATEURS
-- ============================================================

CREATE TABLE users (
    id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id   INT UNSIGNED  NULL,
    role_id            TINYINT UNSIGNED NOT NULL,
    nom                VARCHAR(100) NOT NULL,
    prenoms            VARCHAR(150) NOT NULL,
    email              VARCHAR(191) NULL,
    telephone          VARCHAR(20)  NULL,
    login              VARCHAR(100) NOT NULL UNIQUE,
    password           VARCHAR(255) NOT NULL,
    photo              VARCHAR(255) NULL,
    actif              TINYINT(1)   DEFAULT 1,
    email_verifie      TINYINT(1)   DEFAULT 0,
    token_reset        VARCHAR(100) NULL,
    token_expire       DATETIME     NULL,
    derniere_connexion DATETIME     NULL,
    created_at         DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at         DATETIME     ON UPDATE CURRENT_TIMESTAMP,
    deleted_at         DATETIME     NULL,
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE SET NULL,
    FOREIGN KEY (role_id)          REFERENCES roles(id),
    INDEX idx_login (login),
    INDEX idx_etablissement (etablissement_id)
) ENGINE=InnoDB;

CREATE TABLE auth_logs (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NULL,
    ip         VARCHAR(45)  NOT NULL,
    user_agent VARCHAR(255),
    action     ENUM('login','logout','echec') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id)
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 4 — STRUCTURE PÉDAGOGIQUE
-- ============================================================

CREATE TABLE annees_scolaires (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT UNSIGNED NOT NULL,
    libelle          VARCHAR(20)  NOT NULL,
    date_debut       DATE         NOT NULL,
    date_fin         DATE         NOT NULL,
    en_cours         TINYINT(1)   DEFAULT 0,
    created_at       DATETIME     DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_annee (etablissement_id, libelle),
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE cycles (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT UNSIGNED NOT NULL,
    nom              VARCHAR(100) NOT NULL,
    ordre            TINYINT UNSIGNED NOT NULL DEFAULT 1,
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE niveaux (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cycle_id     INT UNSIGNED NOT NULL,
    nom          VARCHAR(50)  NOT NULL,
    abreviation  VARCHAR(10),
    ordre        TINYINT UNSIGNED NOT NULL DEFAULT 1,
    FOREIGN KEY (cycle_id) REFERENCES cycles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE periodes (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    annee_scolaire_id INT UNSIGNED NOT NULL,
    nom               VARCHAR(50)  NOT NULL,
    type              ENUM('trimestre','semestre','annuel') NOT NULL,
    date_debut        DATE         NOT NULL,
    date_fin          DATE         NOT NULL,
    ordre             TINYINT UNSIGNED NOT NULL DEFAULT 1,
    FOREIGN KEY (annee_scolaire_id) REFERENCES annees_scolaires(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 5 — CLASSES & SALLES
-- ============================================================

CREATE TABLE salles (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT UNSIGNED NOT NULL,
    nom              VARCHAR(50)  NOT NULL,
    capacite         SMALLINT UNSIGNED,
    type             ENUM('cours','labo','informatique','sport','amphi') DEFAULT 'cours',
    disponible       TINYINT(1)   DEFAULT 1,
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE personnel (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id          INT UNSIGNED NOT NULL UNIQUE,
    etablissement_id INT UNSIGNED NOT NULL,
    matricule        VARCHAR(30)  UNIQUE,
    type             ENUM('enseignant','administratif','direction','surveillant','autre') NOT NULL,
    specialite       VARCHAR(150) NULL,
    diplome          VARCHAR(200) NULL,
    date_embauche    DATE         NULL,
    statut_contrat   ENUM('permanent','contractuel','vacataire','stagiaire') DEFAULT 'permanent',
    salaire_base     DECIMAL(12,2) NULL,
    created_at       DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at       DATETIME     ON UPDATE CURRENT_TIMESTAMP,
    deleted_at       DATETIME     NULL,
    FOREIGN KEY (user_id)          REFERENCES users(id)          ON DELETE CASCADE,
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id)
) ENGINE=InnoDB;

CREATE TABLE classes (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id  INT UNSIGNED NOT NULL,
    annee_scolaire_id INT UNSIGNED NOT NULL,
    niveau_id         INT UNSIGNED NOT NULL,
    nom               VARCHAR(50)  NOT NULL,
    effectif_max      TINYINT UNSIGNED DEFAULT 40,
    titulaire_id      INT UNSIGNED NULL,
    created_at        DATETIME     DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_classe (etablissement_id, annee_scolaire_id, nom),
    FOREIGN KEY (etablissement_id)  REFERENCES etablissements(id),
    FOREIGN KEY (annee_scolaire_id) REFERENCES annees_scolaires(id),
    FOREIGN KEY (niveau_id)         REFERENCES niveaux(id),
    FOREIGN KEY (titulaire_id)      REFERENCES personnel(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 6 — ÉLÈVES / ÉTUDIANTS
-- ============================================================

CREATE TABLE eleves (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id           INT UNSIGNED NULL,
    etablissement_id  INT UNSIGNED NOT NULL,
    matricule         VARCHAR(30)  NOT NULL UNIQUE,
    nom               VARCHAR(100) NOT NULL,
    prenoms           VARCHAR(150) NOT NULL,
    sexe              ENUM('M','F') NOT NULL,
    date_naissance    DATE         NULL,
    lieu_naissance    VARCHAR(150) NULL,
    nationalite       VARCHAR(100) NULL,
    photo             VARCHAR(255) NULL,
    adresse           TEXT         NULL,
    telephone         VARCHAR(20)  NULL,
    email             VARCHAR(191) NULL,
    parent1_nom       VARCHAR(200) NULL,
    parent1_telephone VARCHAR(20)  NULL,
    parent1_email     VARCHAR(191) NULL,
    parent1_profession VARCHAR(100) NULL,
    parent2_nom       VARCHAR(200) NULL,
    parent2_telephone VARCHAR(20)  NULL,
    parent2_email     VARCHAR(191) NULL,
    groupe_sanguin    VARCHAR(5)   NULL,
    notes_medicales   TEXT         NULL,
    statut            ENUM('actif','diplome','transfere','exclu','archive') DEFAULT 'actif',
    created_at        DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME     ON UPDATE CURRENT_TIMESTAMP,
    deleted_at        DATETIME     NULL,
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id),
    FOREIGN KEY (user_id)          REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_etablissement (etablissement_id),
    INDEX idx_nom (nom, prenoms)
) ENGINE=InnoDB;

CREATE TABLE inscriptions (
    id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    eleve_id             INT UNSIGNED NOT NULL,
    classe_id            INT UNSIGNED NOT NULL,
    annee_scolaire_id    INT UNSIGNED NOT NULL,
    date_inscription     DATE         NOT NULL,
    statut               ENUM('inscrit','en_attente','annule') DEFAULT 'inscrit',
    numero_ordre         SMALLINT UNSIGNED NULL,
    ancien_etablissement VARCHAR(200) NULL,
    documents_fournis    JSON         NULL,
    created_at           DATETIME     DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_inscr (eleve_id, annee_scolaire_id),
    FOREIGN KEY (eleve_id)           REFERENCES eleves(id)          ON DELETE CASCADE,
    FOREIGN KEY (classe_id)          REFERENCES classes(id),
    FOREIGN KEY (annee_scolaire_id)  REFERENCES annees_scolaires(id)
) ENGINE=InnoDB;

CREATE TABLE parent_eleve (
    user_id  INT UNSIGNED NOT NULL,
    eleve_id INT UNSIGNED NOT NULL,
    lien     VARCHAR(50)  DEFAULT 'parent',
    PRIMARY KEY (user_id, eleve_id),
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    FOREIGN KEY (eleve_id) REFERENCES eleves(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE transferts (
    id                       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    eleve_id                 INT UNSIGNED NOT NULL,
    etablissement_depart_id  INT UNSIGNED NULL,
    etablissement_arrivee_id INT UNSIGNED NULL,
    date_transfert           DATE         NOT NULL,
    motif                    TEXT         NULL,
    numero_ordre_depart      VARCHAR(50)  NULL,
    created_at               DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (eleve_id) REFERENCES eleves(id)
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 7 — MATIÈRES & AFFECTATIONS
-- ============================================================

CREATE TABLE matieres (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT UNSIGNED NOT NULL,
    nom              VARCHAR(100) NOT NULL,
    code             VARCHAR(20)  NULL,
    type             ENUM('principale','optionnelle','activite') DEFAULT 'principale',
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE affectations_cours (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    personnel_id      INT UNSIGNED NOT NULL,
    classe_id         INT UNSIGNED NOT NULL,
    matiere_id        INT UNSIGNED NOT NULL,
    annee_scolaire_id INT UNSIGNED NOT NULL,
    coefficient       DECIMAL(4,2) DEFAULT 1.00,
    heures_hebdo      DECIMAL(4,1) NULL,
    UNIQUE KEY uk_affect (personnel_id, classe_id, matiere_id, annee_scolaire_id),
    FOREIGN KEY (personnel_id)      REFERENCES personnel(id),
    FOREIGN KEY (classe_id)         REFERENCES classes(id),
    FOREIGN KEY (matiere_id)        REFERENCES matieres(id),
    FOREIGN KEY (annee_scolaire_id) REFERENCES annees_scolaires(id)
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 8 — EMPLOI DU TEMPS
-- ============================================================

CREATE TABLE creneaux_horaires (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT UNSIGNED NOT NULL,
    heure_debut      TIME         NOT NULL,
    heure_fin        TIME         NOT NULL,
    nom              VARCHAR(30)  NULL,
    type             ENUM('cours','pause','repas') DEFAULT 'cours',
    ordre            TINYINT UNSIGNED NOT NULL DEFAULT 1,
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE emplois_du_temps (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    classe_id         INT UNSIGNED NOT NULL,
    annee_scolaire_id INT UNSIGNED NOT NULL,
    affectation_id    INT UNSIGNED NOT NULL,
    salle_id          INT UNSIGNED NULL,
    creneau_id        INT UNSIGNED NOT NULL,
    jour_semaine      TINYINT      NOT NULL COMMENT '1=Lundi, 5=Vendredi, 6=Samedi',
    valide_du         DATE         NULL,
    valide_au         DATE         NULL,
    created_at        DATETIME     DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_salle_creneau      (salle_id, creneau_id, jour_semaine, annee_scolaire_id),
    UNIQUE KEY uk_enseignant_creneau (affectation_id, creneau_id, jour_semaine, annee_scolaire_id),
    FOREIGN KEY (classe_id)          REFERENCES classes(id),
    FOREIGN KEY (annee_scolaire_id)  REFERENCES annees_scolaires(id),
    FOREIGN KEY (affectation_id)     REFERENCES affectations_cours(id),
    FOREIGN KEY (salle_id)           REFERENCES salles(id) ON DELETE SET NULL,
    FOREIGN KEY (creneau_id)         REFERENCES creneaux_horaires(id)
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 9 — PRÉSENCES
-- ============================================================

CREATE TABLE seances (
    id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    emploi_du_temps_id INT UNSIGNED NULL,
    affectation_id     INT UNSIGNED NOT NULL,
    date_seance        DATE         NOT NULL,
    heure_debut        TIME         NOT NULL,
    heure_fin          TIME         NOT NULL,
    salle_id           INT UNSIGNED NULL,
    observation        TEXT         NULL,
    appel_fait         TINYINT(1)   DEFAULT 0,
    created_at         DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emploi_du_temps_id) REFERENCES emplois_du_temps(id) ON DELETE SET NULL,
    FOREIGN KEY (affectation_id)     REFERENCES affectations_cours(id),
    FOREIGN KEY (salle_id)           REFERENCES salles(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE presences (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seance_id     INT UNSIGNED NOT NULL,
    eleve_id      INT UNSIGNED NOT NULL,
    statut        ENUM('present','absent','retard','excuse') NOT NULL DEFAULT 'present',
    motif         VARCHAR(255) NULL,
    notifie       TINYINT(1)   DEFAULT 0,
    heure_arrivee TIME         NULL,
    created_at    DATETIME     DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_presence (seance_id, eleve_id),
    FOREIGN KEY (seance_id) REFERENCES seances(id) ON DELETE CASCADE,
    FOREIGN KEY (eleve_id)  REFERENCES eleves(id)  ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 10 — NOTES & BULLETINS
-- ============================================================

CREATE TABLE types_evaluation (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT UNSIGNED NOT NULL,
    nom              VARCHAR(50)  NOT NULL,
    coefficient      DECIMAL(4,2) DEFAULT 1.00,
    sur              DECIMAL(5,2) DEFAULT 20.00,
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE evaluations (
    id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    affectation_id     INT UNSIGNED NOT NULL,
    periode_id         INT UNSIGNED NOT NULL,
    type_evaluation_id INT UNSIGNED NOT NULL,
    titre              VARCHAR(200) NULL,
    date_evaluation    DATE         NOT NULL,
    note_sur           DECIMAL(5,2) DEFAULT 20.00,
    coefficient        DECIMAL(4,2) DEFAULT 1.00,
    created_at         DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (affectation_id)     REFERENCES affectations_cours(id),
    FOREIGN KEY (periode_id)         REFERENCES periodes(id),
    FOREIGN KEY (type_evaluation_id) REFERENCES types_evaluation(id)
) ENGINE=InnoDB;

CREATE TABLE notes (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    evaluation_id INT UNSIGNED NOT NULL,
    eleve_id      INT UNSIGNED NOT NULL,
    note          DECIMAL(5,2) NULL,
    statut        ENUM('present','absent','dispense') DEFAULT 'present',
    observation   VARCHAR(255) NULL,
    saisie_par    INT UNSIGNED NULL,
    created_at    DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME     ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_note (evaluation_id, eleve_id),
    FOREIGN KEY (evaluation_id) REFERENCES evaluations(id) ON DELETE CASCADE,
    FOREIGN KEY (eleve_id)      REFERENCES eleves(id)      ON DELETE CASCADE,
    FOREIGN KEY (saisie_par)    REFERENCES users(id)       ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE moyennes (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    inscription_id INT UNSIGNED NOT NULL,
    affectation_id INT UNSIGNED NULL,
    periode_id     INT UNSIGNED NULL,
    moyenne        DECIMAL(5,2) NULL,
    rang           SMALLINT UNSIGNED NULL,
    appreciation   VARCHAR(100) NULL,
    updated_at     DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_moy (inscription_id, affectation_id, periode_id),
    FOREIGN KEY (inscription_id)  REFERENCES inscriptions(id)      ON DELETE CASCADE,
    FOREIGN KEY (affectation_id)  REFERENCES affectations_cours(id) ON DELETE CASCADE,
    FOREIGN KEY (periode_id)      REFERENCES periodes(id)           ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE bulletins (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    inscription_id      INT UNSIGNED NOT NULL,
    periode_id          INT UNSIGNED NOT NULL,
    moyenne_generale    DECIMAL(5,2) NULL,
    rang                SMALLINT UNSIGNED NULL,
    mention             VARCHAR(50)  NULL,
    effectif_classe     SMALLINT UNSIGNED NULL,
    appreciation_conseil TEXT        NULL,
    conseil_classe      ENUM('passage','redoublement','exclusion','felicitations','encouragements') NULL,
    fichier_pdf         VARCHAR(255) NULL,
    genere_le           DATETIME     NULL,
    valide              TINYINT(1)   DEFAULT 0,
    created_at          DATETIME     DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_bulletin (inscription_id, periode_id),
    FOREIGN KEY (inscription_id) REFERENCES inscriptions(id) ON DELETE CASCADE,
    FOREIGN KEY (periode_id)     REFERENCES periodes(id)     ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 11 — PAIEMENTS / SCOLARITÉ
-- ============================================================

CREATE TABLE types_frais (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT UNSIGNED NOT NULL,
    nom              VARCHAR(100) NOT NULL,
    montant_defaut   DECIMAL(12,2) DEFAULT 0,
    obligatoire      TINYINT(1)   DEFAULT 1,
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE tarifs (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id  INT UNSIGNED NOT NULL,
    annee_scolaire_id INT UNSIGNED NOT NULL,
    niveau_id         INT UNSIGNED NULL,
    type_frais_id     INT UNSIGNED NOT NULL,
    montant           DECIMAL(12,2) NOT NULL,
    UNIQUE KEY uk_tarif (etablissement_id, annee_scolaire_id, niveau_id, type_frais_id),
    FOREIGN KEY (etablissement_id)  REFERENCES etablissements(id),
    FOREIGN KEY (annee_scolaire_id) REFERENCES annees_scolaires(id),
    FOREIGN KEY (niveau_id)         REFERENCES niveaux(id) ON DELETE SET NULL,
    FOREIGN KEY (type_frais_id)     REFERENCES types_frais(id)
) ENGINE=InnoDB;

CREATE TABLE dossiers_paiement (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    inscription_id  INT UNSIGNED NOT NULL UNIQUE,
    montant_total   DECIMAL(12,2) NOT NULL,
    montant_paye    DECIMAL(12,2) DEFAULT 0,
    statut          ENUM('non_paye','partiel','solde','exonere') DEFAULT 'non_paye',
    created_at      DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME     ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (inscription_id) REFERENCES inscriptions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE tranches_paiement (
    id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dossier_paiement_id  INT UNSIGNED NOT NULL,
    type_frais_id        INT UNSIGNED NOT NULL,
    numero_tranche       TINYINT UNSIGNED NOT NULL,
    montant_attendu      DECIMAL(12,2) NOT NULL,
    date_echeance        DATE         NOT NULL,
    statut               ENUM('en_attente','paye','en_retard') DEFAULT 'en_attente',
    FOREIGN KEY (dossier_paiement_id) REFERENCES dossiers_paiement(id) ON DELETE CASCADE,
    FOREIGN KEY (type_frais_id)       REFERENCES types_frais(id)
) ENGINE=InnoDB;

CREATE TABLE paiements (
    id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dossier_paiement_id   INT UNSIGNED NOT NULL,
    tranche_id            INT UNSIGNED NULL,
    numero_recu           VARCHAR(30)  NOT NULL UNIQUE,
    montant               DECIMAL(12,2) NOT NULL,
    mode_paiement         ENUM('espece','cheque','virement','mobile_money','carte') NOT NULL,
    reference_transaction VARCHAR(100) NULL,
    date_paiement         DATE         NOT NULL,
    encaisse_par          INT UNSIGNED NOT NULL,
    observation           TEXT         NULL,
    annule                TINYINT(1)   DEFAULT 0,
    created_at            DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dossier_paiement_id) REFERENCES dossiers_paiement(id),
    FOREIGN KEY (tranche_id)          REFERENCES tranches_paiement(id) ON DELETE SET NULL,
    FOREIGN KEY (encaisse_par)        REFERENCES users(id)
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 12 — COMPTABILITÉ
-- ============================================================

CREATE TABLE categories_comptables (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT UNSIGNED NOT NULL,
    nom              VARCHAR(100) NOT NULL,
    type             ENUM('recette','depense') NOT NULL,
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE transactions (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id  INT UNSIGNED NOT NULL,
    annee_scolaire_id INT UNSIGNED NOT NULL,
    categorie_id      INT UNSIGNED NOT NULL,
    libelle           VARCHAR(255) NOT NULL,
    montant           DECIMAL(12,2) NOT NULL,
    type              ENUM('recette','depense') NOT NULL,
    date_transaction  DATE         NOT NULL,
    reference         VARCHAR(100) NULL,
    piece_jointe      VARCHAR(255) NULL,
    paiement_id       INT UNSIGNED NULL,
    saisi_par         INT UNSIGNED NOT NULL,
    created_at        DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etablissement_id)  REFERENCES etablissements(id),
    FOREIGN KEY (annee_scolaire_id) REFERENCES annees_scolaires(id),
    FOREIGN KEY (categorie_id)      REFERENCES categories_comptables(id),
    FOREIGN KEY (paiement_id)       REFERENCES paiements(id) ON DELETE SET NULL,
    FOREIGN KEY (saisi_par)         REFERENCES users(id)
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 13 — EXAMENS
-- ============================================================

CREATE TABLE examens (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id  INT UNSIGNED NOT NULL,
    annee_scolaire_id INT UNSIGNED NOT NULL,
    periode_id        INT UNSIGNED NULL,
    nom               VARCHAR(200) NOT NULL,
    type              ENUM('interne','officiel','rattrapage') DEFAULT 'interne',
    date_debut        DATE         NOT NULL,
    date_fin          DATE         NOT NULL,
    created_at        DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etablissement_id)  REFERENCES etablissements(id),
    FOREIGN KEY (annee_scolaire_id) REFERENCES annees_scolaires(id),
    FOREIGN KEY (periode_id)        REFERENCES periodes(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE planning_examens (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    examen_id       INT UNSIGNED NOT NULL,
    matiere_id      INT UNSIGNED NOT NULL,
    classe_id       INT UNSIGNED NULL,
    salle_id        INT UNSIGNED NULL,
    date_epreuve    DATE         NOT NULL,
    heure_debut     TIME         NOT NULL,
    heure_fin       TIME         NOT NULL,
    duree_minutes   SMALLINT UNSIGNED NULL,
    FOREIGN KEY (examen_id)  REFERENCES examens(id) ON DELETE CASCADE,
    FOREIGN KEY (matiere_id) REFERENCES matieres(id),
    FOREIGN KEY (classe_id)  REFERENCES classes(id)  ON DELETE SET NULL,
    FOREIGN KEY (salle_id)   REFERENCES salles(id)   ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE surveillances_examen (
    id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    planning_examen_id INT UNSIGNED NOT NULL,
    personnel_id       INT UNSIGNED NOT NULL,
    role_surveillance  ENUM('surveillant','chef_salle','correcteur') DEFAULT 'surveillant',
    UNIQUE KEY uk_surv (planning_examen_id, personnel_id),
    FOREIGN KEY (planning_examen_id) REFERENCES planning_examens(id) ON DELETE CASCADE,
    FOREIGN KEY (personnel_id)       REFERENCES personnel(id)
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 14 — BIBLIOTHÈQUE
-- ============================================================

CREATE TABLE livres (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id  INT UNSIGNED NOT NULL,
    isbn              VARCHAR(20)  NULL,
    titre             VARCHAR(255) NOT NULL,
    auteur            VARCHAR(200) NULL,
    editeur           VARCHAR(150) NULL,
    annee_edition     YEAR         NULL,
    categorie         VARCHAR(100) NULL,
    localisation      VARCHAR(100) NULL,
    exemplaires_total SMALLINT UNSIGNED DEFAULT 1,
    exemplaires_dispo SMALLINT UNSIGNED DEFAULT 1,
    created_at        DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE emprunts (
    id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    livre_id              INT UNSIGNED NOT NULL,
    user_id               INT UNSIGNED NOT NULL,
    date_emprunt          DATE         NOT NULL,
    date_retour_prevu     DATE         NOT NULL,
    date_retour_effectif  DATE         NULL,
    statut                ENUM('en_cours','rendu','en_retard','perdu') DEFAULT 'en_cours',
    amende                DECIMAL(8,2) DEFAULT 0,
    amende_payee          TINYINT(1)   DEFAULT 0,
    observation           TEXT         NULL,
    created_at            DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (livre_id) REFERENCES livres(id),
    FOREIGN KEY (user_id)  REFERENCES users(id)
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 15 — COMMUNICATION
-- ============================================================

CREATE TABLE annonces (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etablissement_id INT UNSIGNED NOT NULL,
    auteur_id        INT UNSIGNED NOT NULL,
    titre            VARCHAR(255) NOT NULL,
    contenu          TEXT         NOT NULL,
    cible            ENUM('tous','enseignants','eleves','parents','classe_specifique') DEFAULT 'tous',
    classe_id        INT UNSIGNED NULL,
    priorite         ENUM('normale','importante','urgente') DEFAULT 'normale',
    publie_le        DATETIME     NULL,
    expire_le        DATETIME     NULL,
    created_at       DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etablissement_id) REFERENCES etablissements(id),
    FOREIGN KEY (auteur_id)        REFERENCES users(id),
    FOREIGN KEY (classe_id)        REFERENCES classes(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE messages (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    expediteur_id   INT UNSIGNED NOT NULL,
    destinataire_id INT UNSIGNED NOT NULL,
    sujet           VARCHAR(255) NULL,
    contenu         TEXT         NOT NULL,
    lu              TINYINT(1)   DEFAULT 0,
    lu_le           DATETIME     NULL,
    parent_id       INT UNSIGNED NULL,
    created_at      DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expediteur_id)   REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (destinataire_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id)       REFERENCES messages(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE notifications (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    type       VARCHAR(50)  NOT NULL,
    titre      VARCHAR(255) NULL,
    contenu    TEXT         NULL,
    lien       VARCHAR(255) NULL,
    lu         TINYINT(1)   DEFAULT 0,
    created_at DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_lu (user_id, lu)
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 16 — JOURNAL D'ACTIVITÉ
-- ============================================================

CREATE TABLE activity_logs (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       INT UNSIGNED NULL,
    action        VARCHAR(100) NOT NULL,
    module        VARCHAR(50)  NOT NULL,
    entite_type   VARCHAR(50)  NULL,
    entite_id     INT UNSIGNED NULL,
    donnees_avant JSON         NULL,
    donnees_apres JSON         NULL,
    ip            VARCHAR(45)  NULL,
    created_at    DATETIME     DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user   (user_id),
    INDEX idx_module (module),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- BLOC 17 — RÉINITIALISATION MOT DE PASSE
-- ============================================================

CREATE TABLE password_resets (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    token      VARCHAR(64)  NOT NULL,
    expires_at DATETIME     NOT NULL,
    used       TINYINT(1)   NOT NULL DEFAULT 0,
    created_at DATETIME     DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token   (token),
    INDEX idx_user_id (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;
