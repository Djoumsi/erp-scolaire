<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Request;
use App\Core\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EleveController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('eleves.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();

        // Année scolaire en cours
        $annee  = $this->anneeEnCours($db, $etabId);
        $search = trim($request->get('q', ''));
        $classe = (int) $request->get('classe', 0);
        $page   = max(1, (int) $request->get('page', 1));
        $perPage = 20;

        $sql    = "SELECT e.*, i.statut as statut_inscription, c.nom as classe_nom, i.id as inscription_id
                   FROM eleves e
                   LEFT JOIN inscriptions i ON i.eleve_id=e.id AND i.annee_scolaire_id=?
                   LEFT JOIN classes c ON c.id=i.classe_id
                   WHERE e.etablissement_id=? AND e.deleted_at IS NULL";
        $params = [$annee['id'] ?? 0, $etabId];

        if ($search) {
            $sql .= " AND (e.nom LIKE ? OR e.prenoms LIKE ? OR e.matricule LIKE ?)";
            $like = "%$search%";
            $params[] = $like; $params[] = $like; $params[] = $like;
        }
        if ($classe) {
            $sql .= " AND i.classe_id = ?";
            $params[] = $classe;
        }

        $countStmt = $db->prepare("SELECT COUNT(*) FROM ($sql) t");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $sql .= " ORDER BY e.nom, e.prenoms LIMIT $perPage OFFSET " . (($page - 1) * $perPage);
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $eleves = $stmt->fetchAll();

        // Classes pour filtre
        $stmtC = $db->prepare("SELECT * FROM classes WHERE etablissement_id=? AND annee_scolaire_id=? ORDER BY nom");
        $stmtC->execute([$etabId, $annee['id'] ?? 0]);
        $classes = $stmtC->fetchAll();

        $this->view('eleves/index', [
            'pageTitle'  => 'Élèves',
            'eleves'     => $eleves,
            'classes'    => $classes,
            'annee'      => $annee,
            'search'     => $search,
            'classeId'   => $classe,
            'pagination' => ['items' => $eleves, 'total' => $total, 'per_page' => $perPage, 'current_page' => $page, 'last_page' => max(1, (int) ceil($total / $perPage))],
        ]);
    }

    public function create(Request $request): void
    {
        $this->requirePermission('eleves.creer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $stmtC = $db->prepare("SELECT * FROM classes WHERE etablissement_id=? AND annee_scolaire_id=? ORDER BY nom");
        $stmtC->execute([$etabId, $annee['id'] ?? 0]);

        $this->view('eleves/create', [
            'pageTitle' => 'Inscrire un élève',
            'classes'   => $stmtC->fetchAll(),
            'annee'     => $annee,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requirePermission('eleves.creer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $data = $request->all();

        // Générer matricule unique
        $anneeCode = substr($annee['libelle'] ?? date('Y'), 2, 2) . substr($annee['libelle'] ?? date('Y'), 7, 2);
        $cntStmt   = $db->prepare("SELECT COUNT(*) FROM eleves WHERE etablissement_id=?");
        $cntStmt->execute([$etabId]);
        $count     = (int) $cntStmt->fetchColumn() + 1;

        $etablStmt = $db->prepare("SELECT code_etablissement FROM etablissements WHERE id=?");
        $etablStmt->execute([$etabId]);
        $code = $etablStmt->fetchColumn() ?: 'ETB';
        $matricule = strtoupper($code) . $anneeCode . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Photo
        $photo = null;
        if (!empty($_FILES['photo']['tmp_name'])) {
            $photo = uploadFile($_FILES['photo'], 'photos', ['jpg','jpeg','png','webp']);
        }

        // Créer l'élève
        $eleveId = $this->insertEleve($db, $etabId, $matricule, $data, $photo);

        // Inscription à la classe
        if (!empty($data['classe_id']) && $annee) {
            $db->prepare("INSERT INTO inscriptions (eleve_id, classe_id, annee_scolaire_id, date_inscription, statut) VALUES (?,?,?,CURDATE(),'inscrit')")
               ->execute([$eleveId, (int) $data['classe_id'], $annee['id']]);

            // Créer le dossier paiement vide
            $db->prepare("INSERT IGNORE INTO dossiers_paiement (inscription_id, montant_total) VALUES (LAST_INSERT_ID(), 0)")
               ->execute([]);
        }

        Session::flash('success', "Élève {$data['prenoms']} {$data['nom']} inscrit avec succès. Matricule : $matricule");
        redirect('/eleves');
    }

    public function show(Request $request): void
    {
        $this->requirePermission('eleves.voir');
        $db    = Database::getInstance();
        $id    = (int) $request->param('id');
        $stmt = $db->prepare("SELECT e.*, et.nom as etablissement_nom FROM eleves e JOIN etablissements et ON et.id=e.etablissement_id WHERE e.id=? AND e.deleted_at IS NULL");
        $stmt->execute([$id]);
        $eleve = $stmt->fetch();
        if (!$eleve) abort(404, 'Élève introuvable.');
        $this->checkEtab($eleve['etablissement_id']);

        // Historique inscriptions
        $stmt = $db->prepare("SELECT i.*, c.nom as classe, a.libelle as annee FROM inscriptions i JOIN classes c ON c.id=i.classe_id JOIN annees_scolaires a ON a.id=i.annee_scolaire_id WHERE i.eleve_id=? ORDER BY a.date_debut DESC");
        $stmt->execute([$id]);
        $inscriptions = $stmt->fetchAll();

        // Paiements
        $stmt = $db->prepare("SELECT dp.*, p.montant as total_paye, p.numero_recu, p.date_paiement, p.mode_paiement FROM dossiers_paiement dp LEFT JOIN paiements p ON p.dossier_paiement_id=dp.id JOIN inscriptions i ON i.id=dp.inscription_id WHERE i.eleve_id=? ORDER BY dp.created_at DESC LIMIT 10");
        $stmt->execute([$id]);
        $paiements = $stmt->fetchAll();

        // Notes récentes
        $stmt = $db->prepare("SELECT n.*, m.nom as matiere, ev.titre as evaluation, ev.date_evaluation FROM notes n JOIN evaluations ev ON ev.id=n.evaluation_id JOIN affectations_cours ac ON ac.id=ev.affectation_id JOIN matieres m ON m.id=ac.matiere_id WHERE n.eleve_id=? ORDER BY ev.date_evaluation DESC LIMIT 10");
        $stmt->execute([$id]);
        $notes = $stmt->fetchAll();

        $this->view('eleves/show', [
            'pageTitle'    => $eleve['prenoms'] . ' ' . $eleve['nom'],
            'eleve'        => $eleve,
            'inscriptions' => $inscriptions,
            'paiements'    => $paiements,
            'notes'        => $notes,
        ]);
    }

    public function edit(Request $request): void
    {
        $this->requirePermission('eleves.modifier');
        $db   = Database::getInstance();
        $id   = (int) $request->param('id');
        $stmt = $db->prepare("SELECT * FROM eleves WHERE id=? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        $eleve = $stmt->fetch();
        if (!$eleve) abort(404, 'Élève introuvable.');
        $this->checkEtab($eleve['etablissement_id']);

        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);
        $stmtC  = $db->prepare("SELECT * FROM classes WHERE etablissement_id=? AND annee_scolaire_id=? ORDER BY nom");
        $stmtC->execute([$etabId, $annee['id'] ?? 0]);

        $this->view('eleves/edit', [
            'pageTitle' => 'Modifier — ' . $eleve['prenoms'] . ' ' . $eleve['nom'],
            'eleve'     => $eleve,
            'classes'   => $stmtC->fetchAll(),
        ]);
    }

    public function update(Request $request): void
    {
        $this->requirePermission('eleves.modifier');
        $db   = Database::getInstance();
        $id   = (int) $request->param('id');
        $stmt = $db->prepare("SELECT * FROM eleves WHERE id=? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        $eleve = $stmt->fetch();
        if (!$eleve) abort(404);
        $this->checkEtab($eleve['etablissement_id']);

        $data  = $request->all();
        $photo = $eleve['photo'];
        if (!empty($_FILES['photo']['tmp_name'])) {
            $photo = uploadFile($_FILES['photo'], 'photos', ['jpg','jpeg','png','webp']) ?? $photo;
        }

        $db->prepare("UPDATE eleves SET nom=?, prenoms=?, sexe=?, date_naissance=?, lieu_naissance=?, nationalite=?,
            adresse=?, telephone=?, email=?, parent1_nom=?, parent1_telephone=?, parent1_email=?, parent1_profession=?,
            parent2_nom=?, parent2_telephone=?, parent2_email=?, groupe_sanguin=?, notes_medicales=?, photo=?, updated_at=NOW()
            WHERE id=?")
           ->execute([
               $data['nom'], $data['prenoms'], $data['sexe'], $data['date_naissance'] ?: null,
               $data['lieu_naissance'] ?? null, $data['nationalite'] ?? null,
               $data['adresse'] ?? null, $data['telephone'] ?? null, $data['email'] ?? null,
               $data['parent1_nom'] ?? null, $data['parent1_telephone'] ?? null, $data['parent1_email'] ?? null,
               $data['parent1_profession'] ?? null, $data['parent2_nom'] ?? null, $data['parent2_telephone'] ?? null,
               $data['parent2_email'] ?? null, $data['groupe_sanguin'] ?? null, $data['notes_medicales'] ?? null,
               $photo, $id,
           ]);

        Session::flash('success', 'Dossier élève mis à jour.');
        redirect("/eleves/$id");
    }

    public function destroy(Request $request): void
    {
        $this->requirePermission('eleves.supprimer');
        $db   = Database::getInstance();
        $id   = (int) $request->param('id');
        $stmt = $db->prepare("SELECT etablissement_id FROM eleves WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) abort(404);
        $this->checkEtab($row['etablissement_id']);
        $db->prepare("UPDATE eleves SET deleted_at=NOW() WHERE id=?")->execute([$id]);
        Session::flash('success', 'Élève supprimé (soft delete).');
        redirect('/eleves');
    }

    public function export(Request $request): void
    {
        $this->requirePermission('eleves.exporter');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $stmt = $db->prepare("
            SELECT e.matricule, e.nom, e.prenoms, e.sexe,
                   e.date_naissance, e.lieu_naissance, e.nationalite,
                   e.telephone, e.email, e.adresse,
                   e.parent1_nom, e.parent1_telephone,
                   e.parent2_nom, e.parent2_telephone,
                   e.groupe_sanguin,
                   c.nom as classe, n.nom as niveau,
                   i.statut as statut_inscription, i.date_inscription
            FROM eleves e
            LEFT JOIN inscriptions i ON i.eleve_id=e.id AND i.annee_scolaire_id=?
            LEFT JOIN classes c ON c.id=i.classe_id
            LEFT JOIN niveaux n ON n.id=c.niveau_id
            WHERE e.etablissement_id=? AND e.deleted_at IS NULL
            ORDER BY c.nom, e.nom
        ");
        $stmt->execute([$annee['id'] ?? 0, $etabId]);
        $eleves = $stmt->fetchAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Élèves');

        $headers = ['Matricule','Nom','Prénoms','Sexe','Date naissance','Lieu naissance','Nationalité',
                    'Téléphone','Email','Adresse','Parent 1','Tél. parent 1','Parent 2','Tél. parent 2',
                    'Groupe sanguin','Classe','Niveau','Statut','Date inscription'];

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1D4ED8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $row = 2;
        $cols = ['matricule','nom','prenoms','sexe','date_naissance','lieu_naissance','nationalite',
                 'telephone','email','adresse','parent1_nom','parent1_telephone','parent2_nom','parent2_telephone',
                 'groupe_sanguin','classe','niveau','statut_inscription','date_inscription'];

        foreach ($eleves as $e) {
            $col = 'A';
            foreach ($cols as $c) {
                $sheet->setCellValue($col . $row, $e[$c] ?? '');
                $col++;
            }
            // Alterner couleurs lignes
            if ($row % 2 === 0) {
                $sheet->getStyle('A' . $row . ':S' . $row)->getFill()
                      ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F0F4FF');
            }
            $row++;
        }

        Logger::info('Export élèves Excel généré', ['nb' => count($eleves)]);

        $anneeLib = $annee['libelle'] ?? date('Y');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Eleves_' . $anneeLib . '_' . date('Ymd') . '.xlsx"');
        header('Cache-Control: max-age=0');
        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }

    public function showImport(Request $request): void
    {
        $this->requirePermission('eleves.creer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $stmtC = $db->prepare("SELECT * FROM classes WHERE etablissement_id=? AND annee_scolaire_id=? ORDER BY nom");
        $stmtC->execute([$etabId, $annee['id'] ?? 0]);

        $this->view('eleves/import', [
            'pageTitle' => 'Import CSV des élèves',
            'classes'   => $stmtC->fetchAll(),
            'annee'     => $annee,
        ]);
    }

    public function importCsv(Request $request): void
    {
        $this->requirePermission('eleves.creer');

        if (empty($_FILES['fichier_csv']['tmp_name']) || $_FILES['fichier_csv']['error'] !== UPLOAD_ERR_OK) {
            Session::flash('error', 'Aucun fichier CSV fourni.');
            redirect('/eleves/import');
        }

        $db      = Database::getInstance();
        $etabId  = Auth::etablissementId();
        $annee   = $this->anneeEnCours($db, $etabId);
        $classeId = (int) $request->post('classe_id', 0);

        $etablStmt = $db->prepare("SELECT code_etablissement FROM etablissements WHERE id=?");
        $etablStmt->execute([$etabId]);
        $code = $etablStmt->fetchColumn() ?: 'ETB';

        $handle = fopen($_FILES['fichier_csv']['tmp_name'], 'r');
        if (!$handle) {
            Session::flash('error', 'Impossible de lire le fichier.');
            redirect('/eleves/import');
        }

        // Ignorer la première ligne (en-têtes)
        $headers = fgetcsv($handle, 0, ';');
        if (!$headers) {
            Session::flash('error', 'Fichier CSV vide ou invalide.');
            redirect('/eleves/import');
        }

        $imported = 0;
        $errors   = [];
        $line     = 1;

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $line++;
            if (count($row) < 4) {
                $errors[] = "Ligne {$line} : données insuffisantes.";
                continue;
            }

            // Format attendu : nom;prenoms;sexe;date_naissance;[lieu_naissance];[telephone];[parent1_nom];[parent1_telephone]
            [$nom, $prenoms, $sexe] = array_map('trim', $row);
            $dateNaissance = isset($row[3]) ? trim($row[3]) : null;

            if (empty($nom) || empty($prenoms) || !in_array(strtoupper($sexe), ['M','F'])) {
                $errors[] = "Ligne {$line} : nom, prénoms ou sexe invalide.";
                continue;
            }

            // Générer matricule
            $cntStmt = $db->prepare("SELECT COUNT(*) FROM eleves WHERE etablissement_id=?");
            $cntStmt->execute([$etabId]);
            $count   = (int) $cntStmt->fetchColumn() + 1;
            $anneeCode = substr($annee['libelle'] ?? date('Y'), 2, 2) . substr($annee['libelle'] ?? date('Y'), 7, 2);
            $matricule = strtoupper($code) . $anneeCode . str_pad($count, 4, '0', STR_PAD_LEFT);

            $d = [
                'nom'             => $nom,
                'prenoms'         => $prenoms,
                'sexe'            => strtoupper($sexe),
                'date_naissance'  => $dateNaissance ?: null,
                'lieu_naissance'  => $row[4] ?? null,
                'telephone'       => $row[5] ?? null,
                'parent1_nom'     => $row[6] ?? null,
                'parent1_telephone' => $row[7] ?? null,
            ];

            try {
                $eleveId = $this->insertEleve($db, $etabId, $matricule, $d, null);

                if ($classeId && $annee) {
                    $db->prepare("INSERT INTO inscriptions (eleve_id, classe_id, annee_scolaire_id, date_inscription, statut) VALUES (?,?,?,CURDATE(),'inscrit')")
                       ->execute([$eleveId, $classeId, $annee['id']]);
                }
                $imported++;
            } catch (\Throwable $e) {
                $errors[] = "Ligne {$line} : " . $e->getMessage();
            }
        }
        fclose($handle);

        Logger::info('Import CSV élèves', ['importés' => $imported, 'erreurs' => count($errors)]);

        $msg = "{$imported} élève(s) importé(s) avec succès.";
        if ($errors) {
            $msg .= ' ' . count($errors) . ' ligne(s) ignorée(s).';
        }
        Session::flash('success', $msg);
        redirect('/eleves');
    }

    // -------------------------------------------------------
    // Helpers privés
    // -------------------------------------------------------

    private function anneeEnCours(\PDO $db, ?int $etabId): ?array
    {
        if (!$etabId) return null;
        $stmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1");
        $stmt->execute([$etabId]);
        return $stmt->fetch() ?: null;
    }

    private function checkEtab(int $eleveEtabId): void
    {
        if (!Auth::isSuperAdmin() && Auth::etablissementId() !== $eleveEtabId) {
            abort(403, 'Accès refusé.');
        }
    }

    private function insertEleve(\PDO $db, int $etabId, string $matricule, array $d, ?string $photo): int
    {
        $db->prepare("INSERT INTO eleves (etablissement_id, matricule, nom, prenoms, sexe, date_naissance,
            lieu_naissance, nationalite, photo, adresse, telephone, email,
            parent1_nom, parent1_telephone, parent1_email, parent1_profession,
            parent2_nom, parent2_telephone, parent2_email, groupe_sanguin, notes_medicales)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")
           ->execute([
               $etabId, $matricule, $d['nom'], $d['prenoms'], $d['sexe'],
               $d['date_naissance'] ?: null, $d['lieu_naissance'] ?? null, $d['nationalite'] ?? null,
               $photo, $d['adresse'] ?? null, $d['telephone'] ?? null, $d['email'] ?? null,
               $d['parent1_nom'] ?? null, $d['parent1_telephone'] ?? null, $d['parent1_email'] ?? null,
               $d['parent1_profession'] ?? null, $d['parent2_nom'] ?? null, $d['parent2_telephone'] ?? null,
               $d['parent2_email'] ?? null, $d['groupe_sanguin'] ?? null, $d['notes_medicales'] ?? null,
           ]);
        return (int) $db->lastInsertId();
    }
}
