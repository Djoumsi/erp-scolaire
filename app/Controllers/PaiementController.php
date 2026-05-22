<?php
namespace App\Controllers;

use App\Core\AuditLog;
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

class PaiementController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('paiements.voir');
        $db      = Database::getInstance();
        $etabId  = Auth::etablissementId();
        $annee   = $this->anneeEnCours($db, $etabId);
        $page    = max(1, (int) $request->get('page', 1));
        $search  = trim($request->get('q', ''));
        $statut  = $request->get('statut', '');
        $perPage = 20;

        $sql    = "SELECT dp.*, e.nom, e.prenoms, e.matricule, c.nom as classe,
                   COALESCE(SUM(p2.montant),0) as total_paye
                   FROM dossiers_paiement dp
                   JOIN inscriptions i ON i.id=dp.inscription_id
                   JOIN eleves e ON e.id=i.eleve_id
                   JOIN classes c ON c.id=i.classe_id
                   LEFT JOIN paiements p2 ON p2.dossier_paiement_id=dp.id AND p2.annule=0
                   WHERE c.etablissement_id=? AND i.annee_scolaire_id=?";
        $params = [$etabId, $annee['id'] ?? 0];

        if ($search) {
            $sql .= " AND (e.nom LIKE ? OR e.prenoms LIKE ? OR e.matricule LIKE ?)";
            $like = "%$search%";
            $params[] = $like; $params[] = $like; $params[] = $like;
        }
        if ($statut) {
            $sql .= " AND dp.statut = ?";
            $params[] = $statut;
        }
        $sql .= " GROUP BY dp.id ORDER BY dp.updated_at DESC";

        $countStmt = $db->prepare("SELECT COUNT(*) FROM ($sql) t");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $sql .= " LIMIT $perPage OFFSET " . (($page - 1) * $perPage);
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $dossiers = $stmt->fetchAll();

        // Totaux
        $totStmt = $db->prepare("SELECT COALESCE(SUM(montant),0) FROM paiements p JOIN dossiers_paiement dp ON dp.id=p.dossier_paiement_id JOIN inscriptions i ON i.id=dp.inscription_id JOIN classes c ON c.id=i.classe_id WHERE c.etablissement_id=? AND i.annee_scolaire_id=? AND p.annule=0 AND MONTH(p.date_paiement)=MONTH(CURDATE()) AND YEAR(p.date_paiement)=YEAR(CURDATE())");
        $totStmt->execute([$etabId, $annee['id'] ?? 0]);
        $totalMois = (float) $totStmt->fetchColumn();

        $this->view('paiements/index', [
            'pageTitle'  => 'Paiements',
            'dossiers'   => $dossiers,
            'annee'      => $annee,
            'search'     => $search,
            'statut'     => $statut,
            'totalMois'  => $totalMois,
            'pagination' => ['items' => $dossiers, 'total' => $total, 'per_page' => $perPage, 'current_page' => $page, 'last_page' => max(1, (int) ceil($total / $perPage))],
        ]);
    }

    public function create(Request $request): void
    {
        $this->requirePermission('paiements.encaisser');
        $db            = Database::getInstance();
        $inscriptionId = (int) $request->param('inscriptionId');

        $stmt = $db->prepare("
            SELECT dp.*, e.nom, e.prenoms, e.matricule, c.nom as classe, a.libelle as annee
            FROM dossiers_paiement dp
            JOIN inscriptions i ON i.id=dp.inscription_id
            JOIN eleves e ON e.id=i.eleve_id
            JOIN classes c ON c.id=i.classe_id
            JOIN annees_scolaires a ON a.id=i.annee_scolaire_id
            WHERE dp.inscription_id=?
        ");
        $stmt->execute([$inscriptionId]);
        $dossier = $stmt->fetch();
        if (!$dossier) abort(404, 'Dossier de paiement introuvable.');

        // Tranches non payées
        $stmtT = $db->prepare("SELECT tp.*, tf.nom as type_frais FROM tranches_paiement tp JOIN types_frais tf ON tf.id=tp.type_frais_id WHERE tp.dossier_paiement_id=? AND tp.statut != 'paye' ORDER BY tp.date_echeance");
        $stmtT->execute([$dossier['id']]);
        $tranches = $stmtT->fetchAll();

        // Historique paiements
        $stmtH = $db->prepare("SELECT p.*, u.prenoms as caissier_prenom, u.nom as caissier_nom FROM paiements p JOIN users u ON u.id=p.encaisse_par WHERE p.dossier_paiement_id=? ORDER BY p.created_at DESC");
        $stmtH->execute([$dossier['id']]);
        $historique = $stmtH->fetchAll();

        $this->view('paiements/create', [
            'pageTitle'  => 'Encaisser un paiement',
            'dossier'    => $dossier,
            'tranches'   => $tranches,
            'historique' => $historique,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requirePermission('paiements.encaisser');
        $db    = Database::getInstance();
        $data  = $request->all();
        $etabId = Auth::etablissementId();

        $dossierId = (int) $data['dossier_paiement_id'];
        $montant   = (float) $data['montant'];
        $mode      = $data['mode_paiement'];
        $trancheId = !empty($data['tranche_id']) ? (int) $data['tranche_id'] : null;

        if ($montant <= 0) {
            Session::flash('error', 'Le montant doit être positif.');
            $this->back();
        }

        // Vérifier appartenance établissement
        $stmt = $db->prepare("SELECT dp.id, i.annee_scolaire_id, c.etablissement_id FROM dossiers_paiement dp JOIN inscriptions i ON i.id=dp.inscription_id JOIN classes c ON c.id=i.classe_id WHERE dp.id=?");
        $stmt->execute([$dossierId]);
        $check = $stmt->fetch();
        if (!$check || $check['etablissement_id'] != $etabId) abort(403);

        // Générer numéro reçu
        $etablStmt = $db->prepare("SELECT code_etablissement FROM etablissements WHERE id=?");
        $etablStmt->execute([$etabId]);
        $code    = $etablStmt->fetchColumn() ?: 'ETB';
        $numRecu = generateNumeroRecu($code, (int) date('Y'));

        $db->beginTransaction();
        try {
            // Insérer paiement
            $db->prepare("INSERT INTO paiements (dossier_paiement_id, tranche_id, numero_recu, montant, mode_paiement, reference_transaction, date_paiement, encaisse_par, observation) VALUES (?,?,?,?,?,?,CURDATE(),?,?)")
               ->execute([$dossierId, $trancheId, $numRecu, $montant, $mode, $data['reference'] ?? null, Auth::id(), $data['observation'] ?? null]);

            $paiementId = (int) $db->lastInsertId();

            // Mettre à jour dossier
            $db->prepare("UPDATE dossiers_paiement SET montant_paye = montant_paye + ?, updated_at=NOW() WHERE id=?")
               ->execute([$montant, $dossierId]);

            // Statut dossier
            $stmt2 = $db->prepare("SELECT montant_total, montant_paye FROM dossiers_paiement WHERE id=?");
            $stmt2->execute([$dossierId]);
            $d = $stmt2->fetch();
            $statut = $d['montant_paye'] >= $d['montant_total'] ? 'solde' : ($d['montant_paye'] > 0 ? 'partiel' : 'non_paye');
            $db->prepare("UPDATE dossiers_paiement SET statut=? WHERE id=?")->execute([$statut, $dossierId]);

            // Marquer tranche payée
            if ($trancheId) {
                $db->prepare("UPDATE tranches_paiement SET statut='paye' WHERE id=?")->execute([$trancheId]);
            }

            // Transaction comptable
            $stmtCat = $db->prepare("SELECT id FROM categories_comptables WHERE etablissement_id=? AND nom='Scolarité' AND type='recette' LIMIT 1");
            $stmtCat->execute([$etabId]);
            $catId = $stmtCat->fetchColumn();
            if ($catId) {
                $anneeId = $check['annee_scolaire_id'];
                $db->prepare("INSERT INTO transactions (etablissement_id, annee_scolaire_id, categorie_id, libelle, montant, type, date_transaction, paiement_id, saisi_par) VALUES (?,?,?,'Paiement scolarité',?,'recette',CURDATE(),?,?)")
                   ->execute([$etabId, $anneeId, $catId, $montant, $paiementId, Auth::id()]);
            }

            $db->commit();
            AuditLog::log('encaissement', 'paiements', $paiementId, 'paiements', null, ['montant' => $montant, 'recu' => $numRecu, 'mode' => $mode]);
            Logger::info('Paiement enregistré', ['recu' => $numRecu, 'montant' => $montant]);
            Session::flash('success', "Paiement enregistré. Reçu : $numRecu");
            redirect("/paiements/$paiementId/recu");
        } catch (\Throwable $e) {
            $db->rollBack();
            Session::flash('error', 'Erreur lors de l\'enregistrement du paiement.');
            $this->back();
        }
    }

    public function show(Request $request): void
    {
        $this->requirePermission('paiements.voir');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("SELECT p.*, e.nom, e.prenoms, e.matricule, c.nom as classe, a.libelle as annee FROM paiements p JOIN dossiers_paiement dp ON dp.id=p.dossier_paiement_id JOIN inscriptions i ON i.id=dp.inscription_id JOIN eleves e ON e.id=i.eleve_id JOIN classes c ON c.id=i.classe_id JOIN annees_scolaires a ON a.id=i.annee_scolaire_id WHERE p.id=?");
        $stmt->execute([$id]);
        $paiement = $stmt->fetch();
        if (!$paiement) abort(404);

        // Get dossier
        $stmtD = $db->prepare("SELECT dp.*, e.nom, e.prenoms, e.matricule, c.nom as classe, a.libelle as annee FROM dossiers_paiement dp JOIN inscriptions i ON i.id=dp.inscription_id JOIN eleves e ON e.id=i.eleve_id JOIN classes c ON c.id=i.classe_id JOIN annees_scolaires a ON a.id=i.annee_scolaire_id WHERE dp.id=?");
        $stmtD->execute([$paiement['dossier_paiement_id']]);
        $dossier = $stmtD->fetch();

        // Get all paiements for this dossier
        $stmtP = $db->prepare("SELECT * FROM paiements WHERE dossier_paiement_id=? ORDER BY date_paiement DESC");
        $stmtP->execute([$dossier['id']]);
        $paiements = $stmtP->fetchAll();

        $this->view('paiements/show', ['pageTitle' => 'Dossier paiement - ' . $dossier['prenoms'] . ' ' . $dossier['nom'], 'dossier' => $dossier, 'paiements' => $paiements]);
    }

    public function recu(Request $request): void
    {
        $this->requirePermission('paiements.voir');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("
            SELECT p.*, e.nom, e.prenoms, e.matricule, c.nom as classe, a.libelle as annee,
                   et.nom as etablissement_nom, et.adresse as etab_adresse, et.telephone as etab_tel,
                   et.logo as etab_logo, u.prenoms as caissier_prenom, u.nom as caissier_nom
            FROM paiements p
            JOIN dossiers_paiement dp ON dp.id=p.dossier_paiement_id
            JOIN inscriptions i ON i.id=dp.inscription_id
            JOIN eleves e ON e.id=i.eleve_id
            JOIN classes c ON c.id=i.classe_id
            JOIN annees_scolaires a ON a.id=i.annee_scolaire_id
            JOIN etablissements et ON et.id=c.etablissement_id
            JOIN users u ON u.id=p.encaisse_par
            WHERE p.id=?
        ");
        $stmt->execute([$id]);
        $paiement = $stmt->fetch();
        if (!$paiement) abort(404);

        $this->view('paiements/recu', ['pageTitle' => 'Reçu ' . $paiement['numero_recu'], 'paiement' => $paiement], 'print');
    }

    public function annuler(Request $request): void
    {
        $this->requirePermission('paiements.annuler');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("SELECT p.*, dp.inscription_id FROM paiements p JOIN dossiers_paiement dp ON dp.id=p.dossier_paiement_id WHERE p.id=?");
        $stmt->execute([$id]);
        $p = $stmt->fetch();
        if (!$p) abort(404);

        $db->beginTransaction();
        try {
            $db->prepare("UPDATE paiements SET annule=1 WHERE id=?")->execute([$id]);
            $db->prepare("UPDATE dossiers_paiement SET montant_paye = GREATEST(0, montant_paye - ?), updated_at=NOW() WHERE id=?")->execute([$p['montant'], $p['dossier_paiement_id']]);
            $db->commit();
            AuditLog::log('annulation', 'paiements', $id, 'paiements', ['montant' => $p['montant']], null);
            Logger::warning('Paiement annulé', ['paiement_id' => $id, 'montant' => $p['montant']]);
            Session::flash('success', 'Paiement annulé.');
        } catch (\Throwable) {
            $db->rollBack();
            Session::flash('error', 'Erreur annulation.');
        }
        redirect('/paiements');
    }

    public function export(Request $request): void
    {
        $this->requirePermission('paiements.exporter');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $stmt = $db->prepare("
            SELECT e.matricule, e.nom, e.prenoms, c.nom as classe,
                   dp.montant_total, dp.montant_paye,
                   (dp.montant_total - dp.montant_paye) as reste, dp.statut,
                   p.numero_recu, p.montant as montant_versement, p.date_paiement, p.mode_paiement
            FROM dossiers_paiement dp
            JOIN inscriptions i ON i.id=dp.inscription_id
            JOIN eleves e ON e.id=i.eleve_id
            JOIN classes c ON c.id=i.classe_id
            LEFT JOIN paiements p ON p.dossier_paiement_id=dp.id AND p.annule=0
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=?
            ORDER BY c.nom, e.nom, p.date_paiement
        ");
        $stmt->execute([$etabId, $annee['id'] ?? 0]);
        $rows = $stmt->fetchAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Paiements');

        $headers = ['Matricule','Nom','Prénoms','Classe','Total attendu','Total payé','Reste','Statut','N° reçu','Versement','Date paiement','Mode'];
        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '16A34A']],
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
        $cols = ['matricule','nom','prenoms','classe','montant_total','montant_paye','reste','statut','numero_recu','montant_versement','date_paiement','mode_paiement'];
        foreach ($rows as $r) {
            $col = 'A';
            foreach ($cols as $c) {
                $sheet->setCellValue($col . $row, $r[$c] ?? '');
                $col++;
            }
            $row++;
        }

        Logger::info('Export paiements Excel généré', ['nb' => count($rows)]);

        $anneeLib = $annee['libelle'] ?? date('Y');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Paiements_' . $anneeLib . '_' . date('Ymd') . '.xlsx"');
        header('Cache-Control: max-age=0');
        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }

    private function anneeEnCours(\PDO $db, ?int $etabId): ?array
    {
        if (!$etabId) return null;
        $stmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1");
        $stmt->execute([$etabId]);
        return $stmt->fetch() ?: null;
    }
}
