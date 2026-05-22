-- ============================================================
-- ERP SCOLAIRE — Données initiales
-- ============================================================
USE erp_scolaire;

-- Rôles
INSERT INTO roles (nom, label, niveau) VALUES
('super_admin', 'Super Administrateur', 100),
('admin',       'Administrateur Établissement', 80),
('directeur',   'Directeur', 70),
('enseignant',  'Enseignant', 40),
('parent',      'Parent / Tuteur', 20),
('eleve',       'Élève / Étudiant', 10);

-- Permissions par module
INSERT INTO permissions (module, action, label) VALUES
('dashboard',   'voir',       'Voir le tableau de bord'),
('etablissements', 'voir',    'Voir les établissements'),
('etablissements', 'creer',   'Créer un établissement'),
('etablissements', 'modifier','Modifier un établissement'),
('etablissements', 'supprimer','Supprimer un établissement'),
('eleves',      'voir',       'Voir les élèves'),
('eleves',      'creer',      'Inscrire un élève'),
('eleves',      'modifier',   'Modifier un élève'),
('eleves',      'supprimer',  'Supprimer un élève'),
('eleves',      'exporter',   'Exporter la liste des élèves'),
('personnel',   'voir',       'Voir le personnel'),
('personnel',   'creer',      'Créer un membre du personnel'),
('personnel',   'modifier',   'Modifier un membre du personnel'),
('personnel',   'supprimer',  'Supprimer un membre du personnel'),
('classes',     'voir',       'Voir les classes'),
('classes',     'creer',      'Créer une classe'),
('classes',     'modifier',   'Modifier une classe'),
('classes',     'supprimer',  'Supprimer une classe'),
('notes',       'voir',       'Voir les notes'),
('notes',       'saisir',     'Saisir des notes'),
('notes',       'modifier',   'Modifier des notes'),
('notes',       'valider',    'Valider/fermer une période'),
('bulletins',   'voir',       'Voir les bulletins'),
('bulletins',   'generer',    'Générer les bulletins PDF'),
('presences',   'voir',       'Voir les présences'),
('presences',   'saisir',     'Faire l\'appel'),
('presences',   'modifier',   'Modifier une présence'),
('paiements',   'voir',       'Voir les paiements'),
('paiements',   'encaisser',  'Encaisser un paiement'),
('paiements',   'annuler',    'Annuler un paiement'),
('paiements',   'exporter',   'Exporter les paiements'),
('comptabilite','voir',       'Voir la comptabilité'),
('comptabilite','creer',      'Enregistrer une transaction'),
('comptabilite','exporter',   'Exporter les rapports financiers'),
('examens',     'voir',       'Voir les examens'),
('examens',     'creer',      'Créer un examen'),
('examens',     'modifier',   'Modifier un examen'),
('bibliotheque','voir',       'Voir la bibliothèque'),
('bibliotheque','gerer',      'Gérer les emprunts'),
('emploi_temps','voir',       'Voir l\'emploi du temps'),
('emploi_temps','modifier',   'Modifier l\'emploi du temps'),
('communication','voir',      'Voir les annonces'),
('communication','creer',     'Publier une annonce'),
('rapports',    'voir',       'Voir les rapports'),
('rapports',    'exporter',   'Exporter les rapports'),
('parametres',  'voir',       'Voir les paramètres'),
('parametres',  'modifier',   'Modifier les paramètres'),
('users',       'voir',       'Voir les utilisateurs'),
('users',       'creer',      'Créer un utilisateur'),
('users',       'modifier',   'Modifier un utilisateur'),
('users',       'supprimer',  'Supprimer un utilisateur');

-- Permissions super_admin : toutes
INSERT INTO role_permissions (role_id, permission_id)
SELECT 1, id FROM permissions;

-- Permissions admin établissement
INSERT INTO role_permissions (role_id, permission_id)
SELECT 2, p.id FROM permissions p
WHERE p.module NOT IN ('etablissements') OR p.action = 'voir';

-- Permissions directeur
INSERT INTO role_permissions (role_id, permission_id)
SELECT 3, p.id FROM permissions p
WHERE p.module IN ('dashboard','eleves','personnel','classes','notes','bulletins',
                   'presences','paiements','examens','emploi_temps','communication',
                   'rapports','bibliotheque') AND p.action IN ('voir','exporter','generer');

-- Permissions enseignant
INSERT INTO role_permissions (role_id, permission_id)
SELECT 4, p.id FROM permissions p
WHERE (p.module = 'notes'       AND p.action IN ('voir','saisir','modifier'))
   OR (p.module = 'presences'   AND p.action IN ('voir','saisir'))
   OR (p.module = 'bulletins'   AND p.action = 'voir')
   OR (p.module = 'emploi_temps'AND p.action = 'voir')
   OR (p.module = 'communication' AND p.action = 'voir')
   OR (p.module = 'dashboard'   AND p.action = 'voir');

-- Permissions parent
INSERT INTO role_permissions (role_id, permission_id)
SELECT 5, p.id FROM permissions p
WHERE p.module IN ('bulletins','presences','paiements','communication','dashboard')
  AND p.action = 'voir';

-- Permissions élève
INSERT INTO role_permissions (role_id, permission_id)
SELECT 6, p.id FROM permissions p
WHERE p.module IN ('bulletins','notes','emploi_temps','communication','dashboard')
  AND p.action = 'voir';

-- Super admin initial
-- mot de passe : Admin@2025 (bcrypt)
INSERT INTO users (etablissement_id, role_id, nom, prenoms, email, login, password, actif)
VALUES (NULL, 1, 'Super', 'Admin', 'admin@erp-scolaire.local', 'superadmin',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);
-- Note: Le hash ci-dessus est un exemple. Utiliser password_hash('Admin@2025', PASSWORD_BCRYPT) en PHP.
