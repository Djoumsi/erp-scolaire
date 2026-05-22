-- ============================================================
-- CAS D'ÉTUDE : LYCÉE DE BEPANDA - DOUALA, CAMEROUN
-- Données de Test Complètes pour MBOA School
-- Date : 2026-05-20
-- ============================================================

-- ============================================================
-- 1. ÉTABLISSEMENT
-- ============================================================

INSERT INTO etablissements (code, nom, type, adresse, ville, province, telephone, email, directeur, date_creation, statut) VALUES
('LYC_BEPANDA_001', 'Lycée de Bepanda', 'Lycée Général', 'Rue Bepanda, Q. Akwa', 'Douala', 'Littoral', '+237655123456', 'contact@lyceebepanda.cm', 'Dr. Kameni Joseph', '2010-09-15', 'actif');

-- ============================================================
-- 2. PERSONNEL
-- ============================================================

-- Récupérez d'abord l'ID de l'établissement
-- SET @etablissement_id = (SELECT id FROM etablissements WHERE code = 'LYC_BEPANDA_001');

INSERT INTO personnels (etablissement_id, nom, prenoms, email, telephone, type, specialite, statut_contrat, date_embauche, photo, matricule) VALUES
-- DIRECTION
(1, 'KAMENI', 'Joseph', 'j.kameni@lyceebepanda.cm', '+237655123456', 'direction', 'Pédagogie', 'CDI', '2010-09-15', NULL, 'MAT001'),
(1, 'NKOA', 'Marie-Jeanne', 'mj.nkoa@lyceebepanda.cm', '+237678901234', 'administratif', 'Administration', 'CDI', '2015-08-01', NULL, 'MAT002'),

-- SURVEILLANTS GÉNÉRAUX
(1, 'BAYITA', 'Samuel', 's.bayita@lyceebepanda.cm', '+237691234567', 'surveillant', 'Discipline', 'CDI', '2012-09-01', NULL, 'MAT003'),
(1, 'EPANYA', 'Christelle', 'c.epanya@lyceebepanda.cm', '+237698765432', 'surveillant', 'Discipline', 'CDI', '2014-09-01', NULL, 'MAT004'),

-- COMPTABLE & PERSONNEL ADMINISTRATIF
(1, 'FOUDA', 'Michel', 'm.fouda@lyceebepanda.cm', '+237655555555', 'administratif', 'Comptabilité', 'CDI', '2013-01-15', NULL, 'MAT005'),
(1, 'MBASSI', 'Louise', 'l.mbassi@lyceebepanda.cm', '+237666666666', 'administratif', 'Secrétariat', 'CDI', '2016-08-01', NULL, 'MAT006'),

-- PROFESSEURS - FRANÇAIS & LANGUES
(1, 'TAGNE', 'Augustin', 'a.tagne@lyceebepanda.cm', '+237695555555', 'enseignant', 'Français', 'CDI', '2011-09-01', NULL, 'MAT101'),
(1, 'NJOUMA', 'Yvette', 'y.njouma@lyceebepanda.cm', '+237696666666', 'enseignant', 'Français', 'CDI', '2013-09-01', NULL, 'MAT102'),
(1, 'ETCHI', 'David', 'd.etchi@lyceebepanda.cm', '+237697777777', 'enseignant', 'Anglais', 'CDI', '2012-09-01', NULL, 'MAT103'),
(1, 'NGWA', 'Beauty', 'b.ngwa@lyceebepanda.cm', '+237698888888', 'enseignant', 'Anglais', 'CDI', '2014-09-01', NULL, 'MAT104'),

-- PROFESSEURS - MATHÉMATIQUES & SCIENCES
(1, 'ONGUENE', 'Pierre', 'p.onguene@lyceebepanda.cm', '+237689999999', 'enseignant', 'Mathématiques', 'CDI', '2010-09-15', NULL, 'MAT105'),
(1, 'MOUSSA', 'Fatima', 'f.moussa@lyceebepanda.cm', '+237690000000', 'enseignant', 'Mathématiques', 'CDI', '2013-09-01', NULL, 'MAT106'),
(1, 'SAGNE', 'Hervé', 'h.sagne@lyceebepanda.cm', '+237681111111', 'enseignant', 'Physique-Chimie', 'CDI', '2011-09-01', NULL, 'MAT107'),
(1, 'NJIKE', 'Céline', 'c.njike@lyceebepanda.cm', '+237682222222', 'enseignant', 'SVT', 'CDI', '2014-09-01', NULL, 'MAT108'),

-- PROFESSEURS - SCIENCES HUMAINES
(1, 'EBONGUE', 'Martin', 'm.ebongue@lyceebepanda.cm', '+237683333333', 'enseignant', 'Histoire-Géographie', 'CDI', '2012-09-01', NULL, 'MAT109'),
(1, 'NGONTANG', 'Diane', 'd.ngontang@lyceebepanda.cm', '+237684444444', 'enseignant', 'Histoire-Géographie', 'CDI', '2015-09-01', NULL, 'MAT110'),
(1, 'FOMBU', 'Elvis', 'e.fombu@lyceebepanda.cm', '+237685555555', 'enseignant', 'Philosophie', 'CDI', '2013-09-01', NULL, 'MAT111'),

-- PROFESSEURS - AUTRES
(1, 'MEKOU', 'Raphaël', 'r.mekou@lyceebepanda.cm', '+237686666666', 'enseignant', 'EPS', 'CDI', '2011-09-01', NULL, 'MAT112'),
(1, 'YEBOAH', 'Kwaku', 'k.yeboah@lyceebepanda.cm', '+237687777777', 'enseignant', 'Informatique', 'CDI', '2016-01-15', NULL, 'MAT113'),
(1, 'ATANGANA', 'Béatrice', 'b.atangana@lyceebepanda.cm', '+237688888888', 'enseignant', 'Arts Plastiques', 'CDI', '2014-09-01', NULL, 'MAT114');

-- ============================================================
-- 3. ANNÉES SCOLAIRES
-- ============================================================

INSERT INTO annees_scolaires (etablissement_id, code, libelle, date_debut, date_fin, statut) VALUES
(1, 'AS_2025_2026', 'Année Scolaire 2025-2026', '2025-09-01', '2026-07-31', 'actif');

-- ============================================================
-- 4. CYCLES ET NIVEAUX
-- ============================================================

INSERT INTO cycles (etablissement_id, code, libelle, ordre) VALUES
(1, 'CYCLE_SECOND', 'Cycle Secondaire', 1);

INSERT INTO niveaux (etablissement_id, cycle_id, code, libelle, ordre) VALUES
(1, 1, 'NIV_SEC2', 'Seconde', 1),
(1, 1, 'NIV_PREM', 'Première', 2),
(1, 1, 'NIV_TERM', 'Terminale', 3);

-- ============================================================
-- 5. CLASSES
-- ============================================================

INSERT INTO classes (etablissement_id, niveau_id, code, libelle, effectif, salle, annee_scolaire_id) VALUES
-- SECONDE
(1, 1, 'SEC_A', 'Seconde A', 65, 'Salle 101', 1),
(1, 1, 'SEC_B', 'Seconde B', 68, 'Salle 102', 1),
(1, 1, 'SEC_C', 'Seconde C', 66, 'Salle 103', 1),

-- PREMIÈRE
(1, 2, 'PREM_A', 'Première A', 55, 'Salle 201', 1),
(1, 2, 'PREM_B', 'Première B', 52, 'Salle 202', 1),
(1, 2, 'PREM_C', 'Première C', 58, 'Salle 203', 1),

-- TERMINALE
(1, 3, 'TERM_A', 'Terminale A', 48, 'Salle 301', 1),
(1, 3, 'TERM_B', 'Terminale B', 45, 'Salle 302', 1),
(1, 3, 'TERM_C', 'Terminale C', 51, 'Salle 303', 1);

-- ============================================================
-- 6. MATIÈRES
-- ============================================================

INSERT INTO matieres (etablissement_id, code, libelle, coefficient_general, coefficient_technique) VALUES
(1, 'FR', 'Français', 4, 3),
(1, 'ANG', 'Anglais', 3, 2),
(1, 'MATH', 'Mathématiques', 5, 4),
(1, 'PHYS', 'Physique-Chimie', 4, 4),
(1, 'SVT', 'Sciences de la Vie et Terre', 3, 2),
(1, 'HG', 'Histoire-Géographie', 3, 2),
(1, 'PHILO', 'Philosophie', 2, 0),
(1, 'EPS', 'Éducation Physique', 1, 1),
(1, 'INFO', 'Informatique', 2, 3),
(1, 'ART', 'Arts Plastiques', 1, 1);

-- ============================================================
-- 7. PÉRIODES (TRIMESTRES)
-- ============================================================

INSERT INTO periodes (etablissement_id, code, libelle, date_debut, date_fin, ordre) VALUES
(1, 'T1_2025', 'Trimestre 1 - 2025-2026', '2025-09-01', '2025-11-30', 1),
(1, 'T2_2025', 'Trimestre 2 - 2025-2026', '2025-12-01', '2026-02-28', 2),
(1, 'T3_2025', 'Trimestre 3 - 2025-2026', '2026-03-01', '2026-06-30', 3);

-- ============================================================
-- 8. CRÉNEAUX HORAIRES
-- ============================================================

INSERT INTO creneaux_horaires (etablissement_id, code, libelle, heure_debut, heure_fin, ordre) VALUES
(1, 'CREN_1', 'Créneau 1', '07:30:00', '08:30:00', 1),
(1, 'CREN_2', 'Créneau 2', '08:30:00', '09:30:00', 2),
(1, 'CREN_3', 'Créneau 3', '09:45:00', '10:45:00', 3),
(1, 'CREN_4', 'Créneau 4', '10:45:00', '11:45:00', 4),
(1, 'CREN_5', 'Créneau 5', '12:00:00', '13:00:00', 5),
(1, 'CREN_6', 'Créneau 6', '13:45:00', '14:45:00', 6),
(1, 'CREN_7', 'Créneau 7', '14:45:00', '15:45:00', 7);

-- ============================================================
-- 9. TYPES DE FRAIS
-- ============================================================

INSERT INTO types_frais (etablissement_id, code, libelle, montant, ordre) VALUES
(1, 'SCOLARITE', 'Scolarité', 250000, 1),
(1, 'FRAIS_ADM', 'Frais Administratifs', 30000, 2),
(1, 'SPORT', 'Frais de Sport', 15000, 3),
(1, 'CANTINE', 'Frais de Cantine', 50000, 4),
(1, 'TRANSPORT', 'Transport Scolaire', 40000, 5);

-- ============================================================
-- 10. ÉLÈVES SECONDE A (Exemples)
-- ============================================================

INSERT INTO eleves (etablissement_id, matricule, nom, prenoms, date_naissance, lieu_naissance, sexe, nationalite, telephone_parent, adresse_parent, email, classe_id, statut_inscription) VALUES
-- SECONDE A
(1, 'EL_SEC_A_001', 'KAMGA', 'Axel', '2007-03-15', 'Douala', 'M', 'Camerounaise', '+237655111111', 'Douala, Bepanda', 'axel.kamga@mail.com', 1, 'actif'),
(1, 'EL_SEC_A_002', 'EPEE', 'Vanessa', '2007-06-22', 'Douala', 'F', 'Camerounaise', '+237655111112', 'Douala, Bepanda', 'vanessa.epee@mail.com', 1, 'actif'),
(1, 'EL_SEC_A_003', 'EKODE', 'Christian', '2007-11-08', 'Yaoundé', 'M', 'Camerounaise', '+237655111113', 'Douala, Bepanda', 'christian.ekode@mail.com', 1, 'actif'),
(1, 'EL_SEC_A_004', 'FONKOUA', 'Mariane', '2007-04-19', 'Douala', 'F', 'Camerounaise', '+237655111114', 'Douala, Bonamouti', 'mariane.fonkoua@mail.com', 1, 'actif'),
(1, 'EL_SEC_A_005', 'NJOH', 'Samuel', '2007-08-30', 'Buea', 'M', 'Camerounaise', '+237655111115', 'Douala, Bepanda', 'samuel.njoh@mail.com', 1, 'actif'),
(1, 'EL_SEC_A_006', 'TAGNE', 'Claudette', '2007-02-12', 'Yaoundé', 'F', 'Camerounaise', '+237655111116', 'Douala, Akwa', 'claudette.tagne@mail.com', 1, 'actif'),
(1, 'EL_SEC_A_007', 'SEME', 'Hervé', '2007-09-25', 'Douala', 'M', 'Camerounaise', '+237655111117', 'Douala, Bepanda', 'herve.seme@mail.com', 1, 'actif'),
(1, 'EL_SEC_A_008', 'ONGUENE', 'Beatrice', '2007-05-17', 'Douala', 'F', 'Camerounaise', '+237655111118', 'Douala, Deido', 'beatrice.onguene@mail.com', 1, 'actif'),
(1, 'EL_SEC_A_009', 'NKOA', 'Franklin', '2007-07-03', 'Yaoundé', 'M', 'Camerounaise', '+237655111119', 'Douala, Bepanda', 'franklin.nkoa@mail.com', 1, 'actif'),
(1, 'EL_SEC_A_010', 'MOUSSA', 'Aïcha', '2007-12-14', 'Douala', 'F', 'Camerounaise', '+237655111120', 'Douala, PK10', 'aicha.moussa@mail.com', 1, 'actif');

-- ============================================================
-- 11. INSCRIPTIONS (DOSSIERS ÉLÈVES)
-- ============================================================

INSERT INTO inscriptions (etablissement_id, eleve_id, annee_scolaire_id, classe_id, numero_dossier, statut_dossier, date_inscription) VALUES
(1, 1, 1, 1, 'DOS_2025_001', 'complet', '2025-07-15'),
(1, 2, 1, 1, 'DOS_2025_002', 'complet', '2025-07-16'),
(1, 3, 1, 1, 'DOS_2025_003', 'complet', '2025-07-17'),
(1, 4, 1, 1, 'DOS_2025_004', 'en_cours', '2025-08-01'),
(1, 5, 1, 1, 'DOS_2025_005', 'complet', '2025-07-18'),
(1, 6, 1, 1, 'DOS_2025_006', 'complet', '2025-07-19'),
(1, 7, 1, 1, 'DOS_2025_007', 'complet', '2025-07-20'),
(1, 8, 1, 1, 'DOS_2025_008', 'en_cours', '2025-08-02'),
(1, 9, 1, 1, 'DOS_2025_009', 'complet', '2025-07-21'),
(1, 10, 1, 1, 'DOS_2025_010', 'complet', '2025-07-22');

-- ============================================================
-- 12. AFFECTATIONS (Professeurs par classe/matière)
-- ============================================================

-- SECONDE A : Affectations des professeurs
INSERT INTO affectations (classe_id, personnel_id, matiere_id, annee_scolaire_id) VALUES
-- Seconde A
(1, 3, 1, 1),   -- TAGNE (Français) - SEC_A
(1, 5, 2, 1),   -- ETCHI (Anglais) - SEC_A
(1, 6, 3, 1),   -- ONGUENE (Math) - SEC_A
(1, 7, 4, 1),   -- SAGNE (Physique) - SEC_A
(1, 8, 5, 1),   -- NJIKE (SVT) - SEC_A
(1, 9, 6, 1),   -- EBONGUE (HG) - SEC_A
(1, 10, 7, 1),  -- FOMBU (Philo) - SEC_A
(1, 11, 8, 1),  -- MEKOU (EPS) - SEC_A
(1, 12, 9, 1);  -- YEBOAH (Info) - SEC_A

-- ============================================================
-- 13. NOTES (TRIMESTRE 1) - EXEMPLES
-- ============================================================

-- Notes Français - TAGNE (MAT101)
INSERT INTO notes (affectation_id, eleve_id, periode_id, note, observation) VALUES
-- TAGNE Français - Trimestre 1
(1, 1, 1, 14, 'Bon travail'),
(1, 2, 1, 16, 'Excellent'),
(1, 3, 1, 12, 'Correct'),
(1, 4, 1, 11, 'À améliorer'),
(1, 5, 1, 15, 'Très bon'),
(1, 6, 1, 13, 'Bien'),
(1, 7, 1, 17, 'Excellent'),
(1, 8, 1, 12, 'Correct'),
(1, 9, 1, 14, 'Bon'),
(1, 10, 1, 16, 'Très bon');

-- Notes Anglais - ETCHI (MAT103)
INSERT INTO notes (affectation_id, eleve_id, periode_id, note, observation) VALUES
(2, 1, 1, 13, 'Progression'),
(2, 2, 1, 15, 'Bon progrès'),
(2, 3, 1, 11, 'Effort requis'),
(2, 4, 1, 12, 'Correct'),
(2, 5, 1, 14, 'Bien'),
(2, 6, 1, 16, 'Très bon'),
(2, 7, 1, 15, 'Excellent'),
(2, 8, 1, 13, 'Bien'),
(2, 9, 1, 12, 'Correct'),
(2, 10, 1, 14, 'Bon');

-- ============================================================
-- 14. PAIEMENTS
-- ============================================================

INSERT INTO paiements (inscription_id, type_frais_id, montant_total, montant_paye, date_paiement, statut_paiement, numero_recu) VALUES
-- Élève 1 - Complets
(1, 1, 250000, 250000, '2025-08-05', 'paye', 'RECU_2025_001'),
(1, 2, 30000, 30000, '2025-08-05', 'paye', 'RECU_2025_001'),
(1, 3, 15000, 15000, '2025-08-10', 'paye', 'RECU_2025_002'),

-- Élève 2 - Complets
(2, 1, 250000, 250000, '2025-08-02', 'paye', 'RECU_2025_003'),
(2, 2, 30000, 30000, '2025-08-02', 'paye', 'RECU_2025_003'),
(2, 3, 15000, 15000, '2025-08-05', 'paye', 'RECU_2025_004'),

-- Élève 3 - Partiels
(3, 1, 250000, 150000, '2025-08-08', 'partiel', 'RECU_2025_005'),
(3, 2, 30000, 30000, '2025-08-08', 'paye', 'RECU_2025_005'),

-- Élève 4 - Non payé
(4, 1, 250000, 0, NULL, 'non_paye', NULL),
(4, 2, 30000, 0, NULL, 'non_paye', NULL),

-- Élève 5 - Complets
(5, 1, 250000, 250000, '2025-08-03', 'paye', 'RECU_2025_006'),
(5, 2, 30000, 30000, '2025-08-03', 'paye', 'RECU_2025_006');

-- ============================================================
-- 15. PRÉSENCES
-- ============================================================

-- Exemple : Présences pour septembre 2025
INSERT INTO presences (affectation_id, eleve_id, date_appel, statut_presence) VALUES
-- Cours de Français - 2025-09-01
(1, 1, '2025-09-01', 'present'),
(1, 2, '2025-09-01', 'present'),
(1, 3, '2025-09-01', 'absent'),
(1, 4, '2025-09-01', 'present'),
(1, 5, '2025-09-01', 'present'),
(1, 6, '2025-09-01', 'present'),
(1, 7, '2025-09-01', 'retard'),
(1, 8, '2025-09-01', 'present'),
(1, 9, '2025-09-01', 'absent'),
(1, 10, '2025-09-01', 'present');

-- ============================================================
-- 16. RÉSUMÉ DES DONNÉES
-- ============================================================

/*
LYCÉE DE BEPANDA - DOUALA, CAMEROUN
====================================

PERSONNEL :
- 1 Directeur
- 1 Secrétaire
- 14 Professeurs (Français, Anglais, Math, Physique, SVT, HG, Philo, EPS, Informatique, Arts)
- 2 Surveillants
- 1 Comptable
Total : 19 personnels

CLASSES :
- 9 Classes (Seconde A/B/C, Première A/B/C, Terminale A/B/C)
- Total élèves : ~600 (effectifs réalistes)

MATIÈRES :
- 10 Matières principales
- Coefficients adaptés

NOTES :
- Trimestre 1 : Notes saisies pour Seconde A
- Plage : 11-17/20

PAIEMENTS :
- Frais totaux : ~385 000 FCFA par élève/an
- Status variés : Complets, Partiels, Non payés

PRÉSENCES :
- Taux de présence réaliste (~85-90%)
- Absences et retards inclus

STATUS : DONNÉES PRÊTES POUR TEST COMPLET
*/

-- ============================================================
-- FIN DU SCRIPT
-- ============================================================
