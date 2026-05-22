<?php
/**
 * Script de démonstration — ERP Scolaire
 * Crée : 1 lycée complet avec classes, élèves, professeurs, notes, paiements
 *
 * UTILISATION : php database/seeds/demo.php
 * (depuis la racine du projet)
 */

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$host   = $_ENV['DB_HOST']     ?? 'localhost';
$dbname = $_ENV['DB_DATABASE'] ?? 'erp_scolaire';
$user   = $_ENV['DB_USERNAME'] ?? 'root';
$pass   = $_ENV['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Connexion BDD échouée : " . $e->getMessage() . "\n");
}

$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

// ============================================================
// HELPERS
// ============================================================

function insert(PDO $pdo, string $table, array $data): int
{
    $cols = implode(', ', array_keys($data));
    $vals = implode(', ', array_fill(0, count($data), '?'));
    $pdo->prepare("INSERT INTO {$table} ({$cols}) VALUES ({$vals})")->execute(array_values($data));
    return (int) $pdo->lastInsertId();
}

function hash_pwd(string $password): string
{
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

function rand_date(string $start, string $end): string
{
    return date('Y-m-d', rand(strtotime($start), strtotime($end)));
}

function note_aleatoire(float $min = 4.0, float $max = 20.0): float
{
    return round(lcg_value() * ($max - $min) + $min, 2);
}

echo "=== ERP Scolaire — Création des données de démonstration ===\n\n";

// ============================================================
// NETTOYAGE des données de démo précédentes
// ============================================================

$pdo->exec("DELETE FROM activity_logs WHERE 1");
$pdo->exec("DELETE FROM presences WHERE 1");
$pdo->exec("DELETE FROM seances WHERE 1");
$pdo->exec("DELETE FROM notes WHERE 1");
$pdo->exec("DELETE FROM moyennes WHERE 1");
$pdo->exec("DELETE FROM bulletins WHERE 1");
$pdo->exec("DELETE FROM evaluations WHERE 1");
$pdo->exec("DELETE FROM types_evaluation WHERE etablissement_id IS NOT NULL");
$pdo->exec("DELETE FROM emplois_du_temps WHERE 1");
$pdo->exec("DELETE FROM affectations_cours WHERE 1");
$pdo->exec("DELETE FROM paiements WHERE 1");
$pdo->exec("DELETE FROM tranches_paiement WHERE 1");
$pdo->exec("DELETE FROM dossiers_paiement WHERE 1");
$pdo->exec("DELETE FROM transactions WHERE 1");
$pdo->exec("DELETE FROM categories_comptables WHERE 1");
$pdo->exec("DELETE FROM inscriptions WHERE 1");
$pdo->exec("DELETE FROM eleves WHERE 1");
$pdo->exec("DELETE FROM classes WHERE 1");
$pdo->exec("DELETE FROM creneaux_horaires WHERE 1");
$pdo->exec("DELETE FROM salles WHERE 1");
$pdo->exec("DELETE FROM periodes WHERE 1");
$pdo->exec("DELETE FROM annees_scolaires WHERE 1");
$pdo->exec("DELETE FROM matieres WHERE 1");
$pdo->exec("DELETE FROM niveaux WHERE 1");
$pdo->exec("DELETE FROM cycles WHERE 1");
$pdo->exec("DELETE FROM types_frais WHERE 1");
$pdo->exec("DELETE FROM planning_examens WHERE 1");
$pdo->exec("DELETE FROM examens WHERE 1");
$pdo->exec("DELETE FROM livres WHERE 1");
$pdo->exec("DELETE FROM emprunts WHERE 1");
$pdo->exec("DELETE FROM annonces WHERE 1");
$pdo->exec("DELETE FROM messages WHERE 1");
$pdo->exec("DELETE FROM notifications WHERE 1");
$pdo->exec("DELETE FROM personnel WHERE 1");
$pdo->exec("DELETE FROM users WHERE login != 'superadmin'");
$pdo->exec("DELETE FROM etablissements WHERE 1");

echo "✓ Nettoyage effectué\n";

// ============================================================
// 1. ÉTABLISSEMENT
// ============================================================

$etabId = insert($pdo, 'etablissements', [
    'nom'                => 'Lycée Moderne d\'Abidjan',
    'type'               => 'lycee',
    'adresse'            => 'Cocody, Boulevard de la Corniche, Abidjan',
    'telephone'          => '+225 27 22 44 00 11',
    'email'              => 'contact@lma-abidjan.ci',
    'code_etablissement' => 'LMA',
    'devise'             => 'XOF',
    'pays'               => 'Côte d\'Ivoire',
    'actif'              => 1,
]);

echo "✓ Établissement créé : Lycée Moderne d'Abidjan (ID: {$etabId})\n";

// ============================================================
// 2. UTILISATEUR ADMIN DE L'ÉTABLISSEMENT
// ============================================================

$adminId = insert($pdo, 'users', [
    'etablissement_id' => $etabId,
    'role_id'          => 2, // admin
    'nom'              => 'KONATÉ',
    'prenoms'          => 'Mamadou',
    'email'            => 'admin@lma-abidjan.ci',
    'telephone'        => '+225 07 00 00 01',
    'login'            => 'admin.lma',
    'password'         => hash_pwd('Admin@1234'),
    'actif'            => 1,
]);

// Directeur
$directeurUserId = insert($pdo, 'users', [
    'etablissement_id' => $etabId,
    'role_id'          => 3, // directeur
    'nom'              => 'DIALLO',
    'prenoms'          => 'Ibrahim',
    'email'            => 'directeur@lma-abidjan.ci',
    'telephone'        => '+225 07 00 00 02',
    'login'            => 'directeur.lma',
    'password'         => hash_pwd('Admin@1234'),
    'actif'            => 1,
]);

// Comptable
$comptableUserId = insert($pdo, 'users', [
    'etablissement_id' => $etabId,
    'role_id'          => 2,
    'nom'              => 'COULIBALY',
    'prenoms'          => 'Aïssatou',
    'email'            => 'compta@lma-abidjan.ci',
    'telephone'        => '+225 07 00 00 03',
    'login'            => 'compta.lma',
    'password'         => hash_pwd('Staff@1234'),
    'actif'            => 1,
]);

echo "✓ Utilisateurs admin/directeur/comptable créés\n";

// ============================================================
// 3. CYCLES & NIVEAUX
// ============================================================

$cycleId = insert($pdo, 'cycles', [
    'etablissement_id' => $etabId,
    'nom'              => 'Lycée',
    'ordre'            => 1,
]);

$niveaux = [
    'Seconde'   => 1,
    'Première'  => 2,
    'Terminale' => 3,
];
$niveauxIds = [];
foreach ($niveaux as $nom => $ordre) {
    $niveauxIds[$nom] = insert($pdo, 'niveaux', [
        'cycle_id'    => $cycleId,
        'nom'         => $nom,
        'abreviation' => substr($nom, 0, 3),
        'ordre'       => $ordre,
    ]);
}

echo "✓ Cycles et niveaux créés\n";

// ============================================================
// 4. ANNÉE SCOLAIRE & PÉRIODES
// ============================================================

$anneeId = insert($pdo, 'annees_scolaires', [
    'etablissement_id' => $etabId,
    'libelle'          => '2025-2026',
    'date_debut'       => '2025-09-02',
    'date_fin'         => '2026-07-10',
    'en_cours'         => 1,
]);

$periodes = [
    ['Trimestre 1', '2025-09-02', '2025-12-20', 1],
    ['Trimestre 2', '2026-01-05', '2026-03-27', 2],
    ['Trimestre 3', '2026-04-06', '2026-07-10', 3],
];
$periodesIds = [];
foreach ($periodes as [$nom, $debut, $fin, $ordre]) {
    $periodesIds[$nom] = insert($pdo, 'periodes', [
        'annee_scolaire_id' => $anneeId,
        'nom'               => $nom,
        'type'              => 'trimestre',
        'date_debut'        => $debut,
        'date_fin'          => $fin,
        'ordre'             => $ordre,
    ]);
}

echo "✓ Année scolaire 2025-2026 activée + 3 trimestres\n";

// ============================================================
// 5. SALLES
// ============================================================

$sallesIds = [];
foreach (['Salle A101','Salle A102','Salle B201','Salle B202','Laboratoire','Salle Informatique'] as $i => $nom) {
    $sallesIds[] = insert($pdo, 'salles', [
        'etablissement_id' => $etabId,
        'nom'              => $nom,
        'capacite'         => $i >= 4 ? 25 : 40,
        'type'             => $i === 4 ? 'labo' : ($i === 5 ? 'informatique' : 'cours'),
        'disponible'       => 1,
    ]);
}

// ============================================================
// 6. CRÉNEAUX HORAIRES
// ============================================================

$creneauxDef = [
    ['07:30','08:30','1er cours'],
    ['08:30','09:30','2e cours'],
    ['09:30','10:30','3e cours'],
    ['10:45','11:45','4e cours'],
    ['11:45','12:45','5e cours'],
    ['13:30','14:30','6e cours'],
    ['14:30','15:30','7e cours'],
    ['15:30','16:30','8e cours'],
];
$creneauxIds = [];
foreach ($creneauxDef as $i => [$debut, $fin, $nom]) {
    $creneauxIds[] = insert($pdo, 'creneaux_horaires', [
        'etablissement_id' => $etabId,
        'heure_debut'      => $debut,
        'heure_fin'        => $fin,
        'nom'              => $nom,
        'type'             => 'cours',
        'ordre'            => $i + 1,
    ]);
}

// ============================================================
// 7. MATIÈRES
// ============================================================

$matieresData = [
    ['Mathématiques',          'MATH', 9,  'principale'],
    ['Français',               'FR',   5,  'principale'],
    ['Philosophie',            'PHILO',3,  'principale'],
    ['Histoire-Géographie',    'HG',   3,  'principale'],
    ['Anglais',                'ANG',  4,  'principale'],
    ['Espagnol',               'ESP',  2,  'principale'],
    ['Sciences de la Vie et de la Terre', 'SVT', 6, 'principale'],
    ['Physique-Chimie',        'PC',   6,  'principale'],
    ['Économie',               'ECO',  2,  'principale'],
    ['Éducation Physique et Sportive', 'EPS', 2, 'activite'],
    ['Informatique',           'INFO', 1,  'optionnelle'],
    ['Arts Plastiques',        'ARTS', 1,  'activite'],
];
$matieresIds = [];
foreach ($matieresData as [$nom, $code, $coeff, $type]) {
    $matieresIds[$code] = insert($pdo, 'matieres', [
        'etablissement_id' => $etabId,
        'nom'              => $nom,
        'code'             => $code,
        'type'             => $type,
    ]);
}

echo "✓ 12 matières créées\n";

// ============================================================
// 8. TYPES D'ÉVALUATION
// ============================================================

$typeDevId  = insert($pdo, 'types_evaluation', ['etablissement_id' => $etabId, 'nom' => 'Devoir Surveillé', 'coefficient' => 1, 'sur' => 20]);
$typeCompId = insert($pdo, 'types_evaluation', ['etablissement_id' => $etabId, 'nom' => 'Composition',      'coefficient' => 2, 'sur' => 20]);
$typeInterrId = insert($pdo, 'types_evaluation', ['etablissement_id' => $etabId, 'nom' => 'Interrogation',  'coefficient' => 1, 'sur' => 10]);

// ============================================================
// 9. TYPES DE FRAIS
// ============================================================

$typeScolariteId = insert($pdo, 'types_frais', [
    'etablissement_id' => $etabId,
    'nom'              => 'Frais de scolarité',
    'montant_defaut'   => 250000,
    'obligatoire'      => 1,
]);
$typeInscriptionId = insert($pdo, 'types_frais', [
    'etablissement_id' => $etabId,
    'nom'              => 'Frais d\'inscription',
    'montant_defaut'   => 50000,
    'obligatoire'      => 1,
]);

// ============================================================
// 10. CATÉGORIES COMPTABLES
// ============================================================

$catScolariteId = insert($pdo, 'categories_comptables', ['etablissement_id' => $etabId, 'nom' => 'Scolarité',      'type' => 'recette']);
$catDiverrecId  = insert($pdo, 'categories_comptables', ['etablissement_id' => $etabId, 'nom' => 'Divers recettes','type' => 'recette']);
$catSalaireId   = insert($pdo, 'categories_comptables', ['etablissement_id' => $etabId, 'nom' => 'Salaires',       'type' => 'depense']);
$catFournitId   = insert($pdo, 'categories_comptables', ['etablissement_id' => $etabId, 'nom' => 'Fournitures',    'type' => 'depense']);
$catEntretienId = insert($pdo, 'categories_comptables', ['etablissement_id' => $etabId, 'nom' => 'Entretien',      'type' => 'depense']);

// ============================================================
// 11. PROFESSEURS (10 enseignants)
// ============================================================

$professeurs = [
    ['TOURÉ',     'Fatou',       'prof.toure',    'MATH', '+225 07 11 22 33', 'Mathématiques et Physique'],
    ['BAMBA',     'Seydou',      'prof.bamba',    'FR',   '+225 07 22 33 44', 'Lettres modernes'],
    ['OUATTARA',  'Mariam',      'prof.ouattara', 'PC',   '+225 07 33 44 55', 'Sciences Physiques'],
    ['KONÉ',      'Adama',       'prof.kone',     'SVT',  '+225 07 44 55 66', 'Sciences de la Vie'],
    ['TRAORÉ',    'Aminata',     'prof.traore',   'ANG',  '+225 07 55 66 77', 'Anglais Langue Étrangère'],
    ['SANOGO',    'Boubacar',    'prof.sanogo',   'HG',   '+225 07 66 77 88', 'Histoire-Géographie'],
    ['DIABATÉ',   'Kadidia',     'prof.diabate',  'PHILO','+225 07 77 88 99', 'Philosophie'],
    ['DEMBÉLÉ',   'Moussa',      'prof.dembele',  'ECO',  '+225 07 88 99 00', 'Économie et Sciences Sociales'],
    ['COULIBALY', 'Rokia',       'prof.coulibaly','EPS',  '+225 07 99 00 11', 'Éducation Physique'],
    ['FOFANA',    'Abdoulaye',   'prof.fofana',   'INFO', '+225 07 00 11 22', 'Informatique'],
];

$professeursIds = []; // [code_matiere => personnel_id]
$profUserIds    = []; // [code_matiere => user_id]

foreach ($professeurs as [$nom, $prenom, $login, $matCode, $tel, $spe]) {
    $uId = insert($pdo, 'users', [
        'etablissement_id' => $etabId,
        'role_id'          => 4, // enseignant
        'nom'              => $nom,
        'prenoms'          => $prenom,
        'email'            => strtolower($login) . '@lma-abidjan.ci',
        'telephone'        => $tel,
        'login'            => $login,
        'password'         => hash_pwd('Prof@1234'),
        'actif'            => 1,
    ]);
    $pId = insert($pdo, 'personnel', [
        'user_id'          => $uId,
        'etablissement_id' => $etabId,
        'matricule'        => 'PERS' . str_pad($uId, 4, '0', STR_PAD_LEFT),
        'type'             => 'enseignant',
        'specialite'       => $spe,
        'date_embauche'    => rand_date('2015-01-01', '2023-01-01'),
        'statut_contrat'   => ['permanent','contractuel','vacataire'][rand(0,2)],
    ]);
    $professeursIds[$matCode] = $pId;
    $profUserIds[$matCode]    = $uId;
}

// Le prof de MATH enseigne aussi PC → on lui affectera les deux
// Le prof de FR enseigne aussi PHILO & ESP (créer des affectations)
// Assigner ESP et ARTS au prof de FR
$professeursIds['ESP']  = $professeursIds['FR'];
$professeursIds['ARTS'] = $professeursIds['EPS'];

echo "✓ 10 professeurs créés\n";

// Personnel admin/directeur
foreach ([
    [$adminId,          'administratif', 'PERS-ADM-001', 'Administration'],
    [$directeurUserId,  'direction',     'PERS-DIR-001', 'Direction générale'],
    [$comptableUserId,  'administratif', 'PERS-COM-001', 'Comptabilité'],
] as [$uid, $type, $mat, $spe]) {
    insert($pdo, 'personnel', [
        'user_id'          => $uid,
        'etablissement_id' => $etabId,
        'matricule'        => $mat,
        'type'             => $type,
        'specialite'       => $spe,
        'date_embauche'    => '2020-09-01',
        'statut_contrat'   => 'permanent',
    ]);
}

// ============================================================
// 12. CLASSES (3 classes : 2nde A, 1ère D, Tle C)
// ============================================================

$classesData = [
    ['2nde A',  'Seconde',   36],
    ['1ère D',  'Première',  34],
    ['Tle C',   'Terminale', 32],
];
$classesIds = [];
foreach ($classesData as [$nom, $niveau, $max]) {
    $classesIds[$nom] = insert($pdo, 'classes', [
        'etablissement_id'  => $etabId,
        'annee_scolaire_id' => $anneeId,
        'niveau_id'         => $niveauxIds[$niveau],
        'nom'               => $nom,
        'effectif_max'      => $max,
    ]);
}

echo "✓ 3 classes créées : 2nde A, 1ère D, Tle C\n";

// ============================================================
// 13. AFFECTATIONS COURS (matière × classe × prof)
// ============================================================

// Matières par classe selon le niveau
$matieresParClasse = [
    '2nde A' => ['MATH','FR','PC','SVT','ANG','ESP','HG','PHILO','EPS','INFO','ARTS'],
    '1ère D' => ['MATH','FR','PC','SVT','ANG','ESP','HG','PHILO','EPS','INFO'],
    'Tle C'  => ['MATH','FR','PC','SVT','ANG','HG','PHILO','ECO','EPS','INFO'],
];

$coeffsParMatiere = [
    'MATH'=>9,'FR'=>5,'PHILO'=>3,'HG'=>3,'ANG'=>4,'ESP'=>2,
    'SVT'=>6,'PC'=>6,'ECO'=>2,'EPS'=>2,'INFO'=>1,'ARTS'=>1,
];

$affectationsIds = []; // ['classe_nom.mat_code' => id]

foreach ($matieresParClasse as $classeNom => $mats) {
    foreach ($mats as $matCode) {
        $profId = $professeursIds[$matCode] ?? $professeursIds['INFO'];
        $key    = $classeNom . '.' . $matCode;
        $affectationsIds[$key] = insert($pdo, 'affectations_cours', [
            'personnel_id'      => $profId,
            'classe_id'         => $classesIds[$classeNom],
            'matiere_id'        => $matieresIds[$matCode],
            'annee_scolaire_id' => $anneeId,
            'coefficient'       => $coeffsParMatiere[$matCode] ?? 1,
            'heures_hebdo'      => $matCode === 'MATH' ? 5 : ($matCode === 'FR' ? 4 : 2),
        ]);
    }
}

echo "✓ Affectations cours créées\n";

// ============================================================
// 14. EMPLOI DU TEMPS (2nde A — Lundi à Vendredi)
// ============================================================

// Grille simplifiée pour 2nde A
$edtGrille = [
    // [jour, créneau_idx, matière, salle_idx]
    [1, 0, 'MATH', 0], [1, 1, 'MATH', 0], [1, 2, 'FR', 0],
    [1, 3, 'ANG',  0], [1, 4, 'SVT',  1], [1, 5, 'PC',  1],
    [2, 0, 'HG',   0], [2, 1, 'PHILO',0], [2, 2, 'ESP', 0],
    [2, 3, 'MATH', 0], [2, 4, 'FR',   0], [2, 5, 'EPS', 4],
    [3, 0, 'PC',   1], [3, 1, 'SVT',  1], [3, 2, 'MATH',0],
    [3, 3, 'ANG',  0], [3, 4, 'HG',   0], [3, 5, 'INFO',5],
    [4, 0, 'FR',   0], [4, 1, 'PHILO',0], [4, 2, 'ECO_skip',0],
    [4, 3, 'MATH', 0], [4, 4, 'ANG',  0], [4, 5, 'ESP', 0],
    [5, 0, 'SVT',  1], [5, 1, 'PC',   1], [5, 2, 'FR',  0],
    [5, 3, 'HG',   0], [5, 4, 'ARTS', 0], [5, 5, 'MATH',0],
];

foreach ($edtGrille as [$jour, $cIdx, $matCode, $salleIdx]) {
    $key = '2nde A.' . $matCode;
    if (!isset($affectationsIds[$key])) continue;
    try {
        insert($pdo, 'emplois_du_temps', [
            'classe_id'         => $classesIds['2nde A'],
            'annee_scolaire_id' => $anneeId,
            'affectation_id'    => $affectationsIds[$key],
            'salle_id'          => $sallesIds[$salleIdx] ?? null,
            'creneau_id'        => $creneauxIds[$cIdx],
            'jour_semaine'      => $jour,
        ]);
    } catch (\Exception $e) {
        // Conflit emploi du temps ignoré silencieusement
    }
}

echo "✓ Emploi du temps 2nde A créé\n";

// ============================================================
// 15. ÉLÈVES (20 par classe = 60 au total)
// ============================================================

$prenomsM = ['Koffi','Moussa','Ibrahim','Seydou','Adama','Boubacar','Mamadou','Kofi','Youssouf','Drissa',
             'Lamine','Cheick','Oumar','Abdoul','Tiéba','Bakary','Lanciné','Daouda','Foussény','Souleymane'];
$prenomsF = ['Fatoumata','Aminata','Mariama','Aïssatou','Kadiatou','Mariam','Oumou','Safiatou','Nadia','Dieneba',
             'Salimata','Rokia','Bintou','Assiatou','Kadja','Fanta','Nana','Saran','Tenin','Mawa'];

$nomsAfricains = ['KONÉ','DIALLO','TRAORÉ','COULIBALY','DIABATÉ','BAMBA','SANOGO','FOFANA',
                  'TOURÉ','SYLLA','CAMARA','DOUMBIA','DEMBÉLÉ','OUATTARA','KOUYATÉ','BALDÉ',
                  'CISSÉ','SOW','BARRY','KEÏTA'];

$classesEleves = ['2nde A','1ère D','Tle C'];
$elevesParClasse = []; // [classe_nom => [eleve_id, ...]]

$eleveCpt = 1;
foreach ($classesEleves as $classeNom) {
    $elevesParClasse[$classeNom] = [];
    $nbEleves = 20;
    for ($i = 0; $i < $nbEleves; $i++) {
        $sexe   = $i % 2 === 0 ? 'M' : 'F';
        $nom    = $nomsAfricains[array_rand($nomsAfricains)];
        $prenom = $sexe === 'M' ? $prenomsM[$i % 20] : $prenomsF[$i % 20];
        $anneeNaiss = 2025 - (16 + (int)substr($classeNom, 0, 1) === 1 ? 16 : ($classeNom === '2nde A' ? 16 : ($classeNom === '1ère D' ? 17 : 18)));
        // Simplification âge
        $ageBase = $classeNom === '2nde A' ? 16 : ($classeNom === '1ère D' ? 17 : 18);
        $anneeNaissCalc = 2025 - $ageBase - rand(0,1);

        $anneeCode = '2526';
        $matricule = 'LMA' . $anneeCode . str_pad($eleveCpt, 4, '0', STR_PAD_LEFT);

        $eleveId = insert($pdo, 'eleves', [
            'etablissement_id'   => $etabId,
            'matricule'          => $matricule,
            'nom'                => $nom,
            'prenoms'            => $prenom,
            'sexe'               => $sexe,
            'date_naissance'     => $anneeNaissCalc . '-' . str_pad(rand(1,12),2,'0',STR_PAD_LEFT) . '-' . str_pad(rand(1,28),2,'0',STR_PAD_LEFT),
            'lieu_naissance'     => ['Abidjan','Bouaké','Yamoussoukro','Daloa','Korhogo','San-Pédro'][rand(0,5)],
            'nationalite'        => 'Ivoirienne',
            'parent1_nom'        => $nomsAfricains[array_rand($nomsAfricains)] . ' ' . ($sexe === 'M' ? 'Seydou' : 'Fatoumata'),
            'parent1_telephone'  => '+225 07 ' . rand(10,99) . ' ' . rand(10,99) . ' ' . rand(10,99),
            'groupe_sanguin'     => ['A+','A-','B+','B-','O+','O-','AB+','AB-'][rand(0,7)],
            'statut'             => 'actif',
        ]);

        // Inscription
        $inscId = insert($pdo, 'inscriptions', [
            'eleve_id'           => $eleveId,
            'classe_id'          => $classesIds[$classeNom],
            'annee_scolaire_id'  => $anneeId,
            'date_inscription'   => rand_date('2025-09-02', '2025-09-20'),
            'statut'             => 'inscrit',
        ]);

        // Dossier paiement
        $montantTotal = ($classeNom === 'Tle C') ? 320000 : ($classeNom === '1ère D' ? 300000 : 280000);
        // Répartition : 30% soldés, 40% partiels, 30% non payés
        $rand = $eleveCpt % 10;
        if ($rand < 3) {
            $montantPaye = $montantTotal;
            $statut      = 'solde';
        } elseif ($rand < 7) {
            $montantPaye = $montantTotal * [0.3, 0.5, 0.6, 0.7][$rand % 4];
            $statut      = 'partiel';
        } else {
            $montantPaye = 0;
            $statut      = 'non_paye';
        }

        $dossierId = insert($pdo, 'dossiers_paiement', [
            'inscription_id' => $inscId,
            'montant_total'  => $montantTotal,
            'montant_paye'   => $montantPaye,
            'statut'         => $statut,
        ]);

        // Tranches
        $tranche1Id = insert($pdo, 'tranches_paiement', [
            'dossier_paiement_id' => $dossierId,
            'type_frais_id'       => $typeScolariteId,
            'numero_tranche'      => 1,
            'montant_attendu'     => round($montantTotal * 0.4),
            'date_echeance'       => '2025-10-31',
            'statut'              => $montantPaye > 0 ? 'paye' : 'en_attente',
        ]);
        insert($pdo, 'tranches_paiement', [
            'dossier_paiement_id' => $dossierId,
            'type_frais_id'       => $typeScolariteId,
            'numero_tranche'      => 2,
            'montant_attendu'     => round($montantTotal * 0.3),
            'date_echeance'       => '2026-01-31',
            'statut'              => $montantPaye >= $montantTotal * 0.7 ? 'paye' : 'en_attente',
        ]);
        insert($pdo, 'tranches_paiement', [
            'dossier_paiement_id' => $dossierId,
            'type_frais_id'       => $typeScolariteId,
            'numero_tranche'      => 3,
            'montant_attendu'     => round($montantTotal * 0.3),
            'date_echeance'       => '2026-04-30',
            'statut'              => $montantPaye >= $montantTotal ? 'paye' : 'en_attente',
        ]);

        // Paiements effectués
        if ($montantPaye > 0) {
            $numRecu = 'LMA-2025-' . str_pad($eleveCpt, 6, '0', STR_PAD_LEFT);
            insert($pdo, 'paiements', [
                'dossier_paiement_id'  => $dossierId,
                'tranche_id'           => $tranche1Id,
                'numero_recu'          => $numRecu,
                'montant'              => $montantPaye,
                'mode_paiement'        => ['espece','mobile_money','virement'][rand(0,2)],
                'date_paiement'        => rand_date('2025-09-02', '2025-11-30'),
                'encaisse_par'         => $adminId,
                'annule'               => 0,
            ]);

            // Transaction comptable
            insert($pdo, 'transactions', [
                'etablissement_id'  => $etabId,
                'annee_scolaire_id' => $anneeId,
                'categorie_id'      => $catScolariteId,
                'libelle'           => 'Scolarité ' . $prenom . ' ' . $nom,
                'montant'           => $montantPaye,
                'type'              => 'recette',
                'date_transaction'  => rand_date('2025-09-02', '2025-11-30'),
                'saisi_par'         => $adminId,
            ]);
        }

        $elevesParClasse[$classeNom][] = ['id' => $eleveId, 'insc_id' => $inscId];
        $eleveCpt++;
    }
}

echo "✓ 60 élèves inscrits (20 par classe) avec dossiers de paiement\n";

// ============================================================
// 16. NOTES & ÉVALUATIONS (Trimestre 1 complet)
// ============================================================

$periode1Id = $periodesIds['Trimestre 1'];

foreach ($classesEleves as $classeNom) {
    $mats = $matieresParClasse[$classeNom];
    $eleves = $elevesParClasse[$classeNom];

    foreach ($mats as $matCode) {
        $key = $classeNom . '.' . $matCode;
        if (!isset($affectationsIds[$key])) continue;
        $affId = $affectationsIds[$key];

        // Devoir 1
        $evalDev1Id = insert($pdo, 'evaluations', [
            'affectation_id'     => $affId,
            'periode_id'         => $periode1Id,
            'type_evaluation_id' => $typeDevId,
            'titre'              => 'Devoir n°1 — ' . $matCode,
            'date_evaluation'    => rand_date('2025-10-01', '2025-10-31'),
            'note_sur'           => 20,
            'coefficient'        => 1,
        ]);

        // Devoir 2
        $evalDev2Id = insert($pdo, 'evaluations', [
            'affectation_id'     => $affId,
            'periode_id'         => $periode1Id,
            'type_evaluation_id' => $typeDevId,
            'titre'              => 'Devoir n°2 — ' . $matCode,
            'date_evaluation'    => rand_date('2025-11-01', '2025-11-30'),
            'note_sur'           => 20,
            'coefficient'        => 1,
        ]);

        // Composition T1
        $evalCompId = insert($pdo, 'evaluations', [
            'affectation_id'     => $affId,
            'periode_id'         => $periode1Id,
            'type_evaluation_id' => $typeCompId,
            'titre'              => 'Composition T1 — ' . $matCode,
            'date_evaluation'    => rand_date('2025-12-01', '2025-12-18'),
            'note_sur'           => 20,
            'coefficient'        => 2,
        ]);

        // Saisir les notes pour chaque élève
        foreach ($eleves as $eleve) {
            $eleveId = $eleve['id'];

            // Profil de l'élève : certains sont bons, moyens, faibles
            $profil = ($eleveId % 5 === 0) ? 'faible' : (($eleveId % 3 === 0) ? 'bon' : 'moyen');
            $base   = $profil === 'bon' ? 14 : ($profil === 'moyen' ? 10 : 5);
            $range  = 5;

            foreach ([$evalDev1Id, $evalDev2Id, $evalCompId] as $evalId) {
                $absent = (rand(1,20) === 1); // 5% d'absents
                insert($pdo, 'notes', [
                    'evaluation_id' => $evalId,
                    'eleve_id'      => $eleveId,
                    'note'          => $absent ? null : min(20, max(0, note_aleatoire($base - $range, $base + $range))),
                    'statut'        => $absent ? 'absent' : 'present',
                    'saisie_par'    => $profUserIds[$matCode] ?? $adminId,
                ]);
            }
        }
    }
}

echo "✓ Notes saisies pour le Trimestre 1 (3 évaluations × 12 matières × 3 classes × 20 élèves)\n";

// ============================================================
// 17. PRÉSENCES (quelques séances)
// ============================================================

// Créer 5 séances pour la 2nde A en Mathématiques
$affMath2nde = $affectationsIds['2nde A.MATH'];
$datesCours  = ['2025-10-06','2025-10-13','2025-10-20','2025-11-03','2025-11-10'];

foreach ($datesCours as $dateSeance) {
    $seanceId = insert($pdo, 'seances', [
        'affectation_id' => $affMath2nde,
        'date_seance'    => $dateSeance,
        'heure_debut'    => '07:30',
        'heure_fin'      => '08:30',
        'salle_id'       => $sallesIds[0],
        'appel_fait'     => 1,
    ]);

    foreach ($elevesParClasse['2nde A'] as $eleve) {
        $rand = rand(1,10);
        insert($pdo, 'presences', [
            'seance_id' => $seanceId,
            'eleve_id'  => $eleve['id'],
            'statut'    => $rand <= 7 ? 'present' : ($rand <= 8 ? 'retard' : ($rand <= 9 ? 'excuse' : 'absent')),
        ]);
    }
}

echo "✓ 5 séances de présences créées (Maths 2nde A)\n";

// ============================================================
// 18. TRANSACTIONS COMPTABLES (dépenses)
// ============================================================

$depenses = [
    [$catSalaireId,   'Salaires octobre 2025',      850000,  '2025-10-31'],
    [$catSalaireId,   'Salaires novembre 2025',      850000,  '2025-11-28'],
    [$catFournitId,   'Achat fournitures bureau',     45000,  '2025-10-05'],
    [$catFournitId,   'Papier et cartouches imprimante', 28000, '2025-10-15'],
    [$catEntretienId, 'Entretien climatiseurs',       75000,  '2025-10-20'],
    [$catEntretienId, 'Nettoyage locaux — octobre',   30000,  '2025-10-31'],
    [$catSalaireId,   'Salaires décembre 2025',      850000,  '2025-12-30'],
    [$catFournitId,   'Achat livres bibliothèque',    95000,  '2025-11-10'],
];

foreach ($depenses as [$catId, $libelle, $montant, $date]) {
    insert($pdo, 'transactions', [
        'etablissement_id'  => $etabId,
        'annee_scolaire_id' => $anneeId,
        'categorie_id'      => $catId,
        'libelle'           => $libelle,
        'montant'           => $montant,
        'type'              => 'depense',
        'date_transaction'  => $date,
        'saisi_par'         => $adminId,
    ]);
}

echo "✓ Transactions comptables créées\n";

// ============================================================
// 19. LIVRES (bibliothèque)
// ============================================================

$livres = [
    ['978-2-01-235407-8', 'Mathématiques Tle C',       'Nathan',     'Collectif Nathan',    2023, 'Mathématiques', 5, 3],
    ['978-2-01-395001-0', 'Français 1ère',              'Hachette',   'Collectif Hachette',  2022, 'Français',      4, 2],
    ['978-2-01-167892-3', 'Physique-Chimie 2nde',       'Belin',      'Collectif Belin',     2023, 'Sciences',      6, 4],
    ['978-2-13-082456-7', 'Le Père Goriot',             'GF Flammarion','Honoré de Balzac',  1835, 'Littérature',   3, 3],
    ['978-2-07-040850-4', 'L\'Aventure ambiguë',        'Julliard',   'Cheikh Hamidou Kane', 1961, 'Littérature',   4, 4],
    ['978-2-07-036024-5', 'Une si longue lettre',       'NEA',        'Mariama Bâ',          1979, 'Littérature',   5, 5],
    ['978-2-02-023741-2', 'Histoire Géo Terminale',     'Nathan',     'Collectif',           2023, 'Histoire',      4, 2],
    ['978-2-01-140256-1', 'Philosophie Tle',            'Hachette',   'Collectif',           2023, 'Philosophie',   3, 1],
    ['978-2-84879-122-3', 'SVT Première',               'Didier',     'Collectif',           2022, 'Sciences',      5, 3],
    ['978-2-01-000001-1', 'Anglais Seconde Step In',    'Hachette',   'Collectif',           2023, 'Anglais',       4, 4],
    ['978-2-01-000002-2', 'Python pour les lycéens',    'Eyrolles',   'Gilles Dowek',        2022, 'Informatique',  3, 2],
    ['978-2-01-000003-3', 'Économie Terminale ES',      'Nathan',     'Collectif',           2022, 'Économie',      4, 3],
];

$livresIds = [];
foreach ($livres as [$isbn, $titre, $editeur, $auteur, $annee, $cat, $total, $dispo]) {
    $livresIds[] = insert($pdo, 'livres', [
        'etablissement_id'  => $etabId,
        'isbn'              => $isbn,
        'titre'             => $titre,
        'auteur'            => $auteur,
        'editeur'           => $editeur,
        'annee_edition'     => $annee,
        'categorie'         => $cat,
        'localisation'      => 'Rayon ' . $cat,
        'exemplaires_total' => $total,
        'exemplaires_dispo' => $dispo,
    ]);
}

echo "✓ 12 livres ajoutés à la bibliothèque\n";

// ============================================================
// 20. ANNONCES
// ============================================================

$annoncesData = [
    ['Rentrée scolaire 2025-2026', 'Nous souhaitons la bienvenue à tous les élèves et au personnel pour cette nouvelle année scolaire. Les cours débutent le lundi 2 septembre 2025.', 'normale', 'tous'],
    ['Réunion parents d\'élèves', 'Une réunion d\'information pour les parents d\'élèves de Terminale se tiendra le samedi 18 octobre 2025 à 9h00 dans la grande salle.', 'importante', 'parents'],
    ['Compositions du 1er trimestre', 'Les compositions du premier trimestre auront lieu du 8 au 18 décembre 2025. Les emplois du temps détaillés seront affichés.', 'urgente', 'tous'],
    ['Résultats T1 disponibles', 'Les résultats et bulletins du premier trimestre sont disponibles. Les parents sont invités à se présenter à l\'administration.', 'importante', 'parents'],
    ['Concours général 2026', 'Les élèves souhaitant participer au Concours Général doivent déposer leur dossier avant le 15 janvier 2026.', 'normale', 'eleves'],
];

foreach ($annoncesData as [$titre, $contenu, $priorite, $cible]) {
    insert($pdo, 'annonces', [
        'etablissement_id' => $etabId,
        'auteur_id'        => $adminId,
        'titre'            => $titre,
        'contenu'          => $contenu,
        'priorite'         => $priorite,
        'cible'            => $cible,
        'created_at'       => date('Y-m-d H:i:s'),
    ]);
}

echo "✓ 5 annonces publiées\n";

// ============================================================
// 21. EXAMEN
// ============================================================

$examenId = insert($pdo, 'examens', [
    'etablissement_id'  => $etabId,
    'annee_scolaire_id' => $anneeId,
    'periode_id'        => $periode1Id,
    'nom'               => 'Compositions du 1er Trimestre 2025',
    'type'              => 'interne',
    'date_debut'        => '2025-12-08',
    'date_fin'          => '2025-12-18',
]);

// Planning épreuves Terminale C
$epreuves = [
    ['MATH', '2025-12-08', '07:30', '10:30'],
    ['PC',   '2025-12-09', '07:30', '10:30'],
    ['SVT',  '2025-12-10', '07:30', '10:30'],
    ['FR',   '2025-12-11', '07:30', '11:30'],
    ['HG',   '2025-12-12', '07:30', '10:30'],
    ['ANG',  '2025-12-15', '07:30', '09:30'],
    ['PHILO','2025-12-16', '07:30', '11:30'],
    ['ECO',  '2025-12-17', '07:30', '10:00'],
];

foreach ($epreuves as [$matCode, $date, $hdebut, $hfin]) {
    insert($pdo, 'planning_examens', [
        'examen_id'   => $examenId,
        'matiere_id'  => $matieresIds[$matCode],
        'classe_id'   => $classesIds['Tle C'],
        'salle_id'    => $sallesIds[0],
        'date_epreuve'=> $date,
        'heure_debut' => $hdebut,
        'heure_fin'   => $hfin,
    ]);
}

echo "✓ Examen T1 créé avec planning Terminale C\n";

// ============================================================
// FIN
// ============================================================

$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

echo "\n=== DONNÉES DE DÉMONSTRATION CRÉÉES AVEC SUCCÈS ===\n\n";
echo "COMPTES DE CONNEXION\n";
echo "──────────────────────────────────────────────────────\n";
echo "Super Admin   : superadmin       / Admin@2025\n";
echo "Admin LMA     : admin.lma        / Admin@1234\n";
echo "Directeur     : directeur.lma    / Admin@1234\n";
echo "Comptable     : compta.lma       / Staff@1234\n";
echo "Prof Maths    : prof.toure       / Prof@1234\n";
echo "Prof Français : prof.bamba       / Prof@1234\n";
echo "Prof Physique : prof.ouattara    / Prof@1234\n";
echo "Prof SVT      : prof.kone        / Prof@1234\n";
echo "Prof Anglais  : prof.traore      / Prof@1234\n";
echo "Prof Hist-Géo : prof.sanogo      / Prof@1234\n";
echo "Prof Philo    : prof.diabate     / Prof@1234\n";
echo "Prof Économie : prof.dembele     / Prof@1234\n";
echo "Prof EPS      : prof.coulibaly   / Prof@1234\n";
echo "Prof Info     : prof.fofana      / Prof@1234\n";
echo "──────────────────────────────────────────────────────\n\n";
echo "DONNÉES CRÉÉES\n";
echo "──────────────────────────────────────────────────────\n";
echo "• 1 établissement  : Lycée Moderne d'Abidjan\n";
echo "• 3 classes        : 2nde A, 1ère D, Tle C\n";
echo "• 12 matières      : Maths, Français, PC, SVT, Anglais...\n";
echo "• 10 professeurs   : chacun avec ses cours\n";
echo "• 60 élèves        : 20 par classe avec dossiers paiements\n";
echo "• Notes T1         : 3 évaluations × 12 matières × 3 classes\n";
echo "• Paiements        : 30% soldés, 40% partiels, 30% non payés\n";
echo "• Emploi du temps  : 2nde A (Lundi→Vendredi)\n";
echo "• Présences        : 5 séances Maths 2nde A\n";
echo "• Bibliothèque     : 12 livres\n";
echo "• Annonces         : 5 annonces publiées\n";
echo "• Examen           : Compositions T1 + planning Tle C\n";
echo "• Comptabilité     : recettes scolarité + 8 transactions dépenses\n";
echo "──────────────────────────────────────────────────────\n\n";
echo "Accès : http://localhost/erp-scolaire/public/login\n";
