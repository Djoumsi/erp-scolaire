<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RapportController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('rapports.voir');
        $db    = Database::getInstance();
        $annee = $this->anneeEnCours($db, Auth::etablissementId());
        $this->view('rapports/index', ['pageTitle' => 'Rapports & Statistiques', 'annee' => $annee]);
    }

    // -------------------------------------------------------
    // Rapport Élèves
    // -------------------------------------------------------

    public function eleves(Request $request): void
    {
        $this->requirePermission('rapports.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        if (!$annee) {
            $this->view('rapports/eleves', ['pageTitle' => 'Rapport Élèves', 'annee' => null, 'stats' => []]);
            return;
        }

        // Effectifs par classe
        $stmt = $db->prepare("
            SELECT c.nom as classe, n.nom as niveau,
                   COUNT(i.id) as total,
                   SUM(e.sexe='M') as garcons,
                   SUM(e.sexe='F') as filles
            FROM classes c
            LEFT JOIN inscriptions i ON i.classe_id=c.id AND i.annee_scolaire_id=? AND i.statut='inscrit'
            LEFT JOIN eleves e ON e.id=i.eleve_id
            LEFT JOIN niveaux n ON n.id=c.niveau_id
            WHERE c.etablissement_id=? AND c.annee_scolaire_id=?
            GROUP BY c.id ORDER BY n.ordre, c.nom
        ");
        $stmt->execute([$annee['id'], $etabId, $annee['id']]);
        $parClasse = $stmt->fetchAll();

        // Répartition sexe globale
        $stmt = $db->prepare("
            SELECT e.sexe, COUNT(*) as nb
            FROM inscriptions i
            JOIN classes c ON c.id=i.classe_id
            JOIN eleves e ON e.id=i.eleve_id
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=? AND i.statut='inscrit'
            GROUP BY e.sexe
        ");
        $stmt->execute([$etabId, $annee['id']]);
        $sexes = ['M' => 0, 'F' => 0];
        foreach ($stmt->fetchAll() as $r) {
            $sexes[$r['sexe']] = (int) $r['nb'];
        }

        // Statuts inscriptions
        $stmt = $db->prepare("
            SELECT i.statut, COUNT(*) as nb
            FROM inscriptions i
            JOIN classes c ON c.id=i.classe_id
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=?
            GROUP BY i.statut
        ");
        $stmt->execute([$etabId, $annee['id']]);
        $statuts = [];
        foreach ($stmt->fetchAll() as $r) {
            $statuts[$r['statut']] = (int) $r['nb'];
        }

        // Évolution inscriptions par mois
        $stmt = $db->prepare("
            SELECT DATE_FORMAT(i.created_at,'%Y-%m') as mois, COUNT(*) as nb
            FROM inscriptions i
            JOIN classes c ON c.id=i.classe_id
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=?
            GROUP BY mois ORDER BY mois
        ");
        $stmt->execute([$etabId, $annee['id']]);
        $evolution = $stmt->fetchAll();

        $this->view('rapports/eleves', [
            'pageTitle' => 'Rapport Élèves',
            'annee'     => $annee,
            'parClasse' => $parClasse,
            'sexes'     => $sexes,
            'statuts'   => $statuts,
            'evolution' => $evolution,
            'total'     => array_sum(array_column($parClasse, 'total')),
        ]);
    }

    public function exportEleves(Request $request): void
    {
        $this->requirePermission('rapports.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $stmt = $db->prepare("
            SELECT e.matricule, e.nom, e.prenoms, e.sexe, e.date_naissance, e.telephone,
                   c.nom as classe, n.nom as niveau, i.statut, i.date_inscription
            FROM inscriptions i
            JOIN eleves e ON e.id=i.eleve_id
            JOIN classes c ON c.id=i.classe_id
            LEFT JOIN niveaux n ON n.id=c.niveau_id
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=?
            ORDER BY c.nom, e.nom
        ");
        $stmt->execute([$etabId, $annee['id'] ?? 0]);
        $rows = $stmt->fetchAll();

        $this->exportXlsx('Rapport_Eleves', [
            ['Matricule', 'Nom', 'Prénoms', 'Sexe', 'Date naissance', 'Téléphone', 'Classe', 'Niveau', 'Statut', 'Date inscription'],
        ], $rows, ['matricule','nom','prenoms','sexe','date_naissance','telephone','classe','niveau','statut','date_inscription']);
    }

    // -------------------------------------------------------
    // Rapport Notes
    // -------------------------------------------------------

    public function notes(Request $request): void
    {
        $this->requirePermission('rapports.voir');
        $db      = Database::getInstance();
        $etabId  = Auth::etablissementId();
        $annee   = $this->anneeEnCours($db, $etabId);
        $periodeId = (int) $request->get('periode', 0);

        // Périodes disponibles
        $stmt = $db->prepare("SELECT * FROM periodes WHERE annee_scolaire_id=? ORDER BY ordre");
        $stmt->execute([$annee['id'] ?? 0]);
        $periodes = $stmt->fetchAll();

        if (!$periodeId && $periodes) {
            $periodeId = (int) $periodes[0]['id'];
        }

        // Moyennes par classe et par matière
        $stmt = $db->prepare("
            SELECT c.nom as classe, m.nom as matiere, ac.coefficient,
                   ROUND(AVG(n.note), 2) as moyenne,
                   MIN(n.note) as note_min,
                   MAX(n.note) as note_max,
                   COUNT(n.id) as nb_notes,
                   SUM(n.note >= 10) as nb_admis
            FROM notes n
            JOIN evaluations ev ON ev.id=n.evaluation_id
            JOIN affectations_cours ac ON ac.id=ev.affectation_id
            JOIN classes c ON c.id=ac.classe_id
            JOIN matieres m ON m.id=ac.matiere_id
            WHERE c.etablissement_id=? AND ev.periode_id=?
            GROUP BY c.id, m.id, ac.coefficient
            ORDER BY c.nom, m.nom
        ");
        $stmt->execute([$etabId, $periodeId]);
        $parClasseMatiere = $stmt->fetchAll();

        // Moyennes générales par classe
        $stmt = $db->prepare("
            SELECT c.nom as classe,
                   ROUND(AVG(moy.moy_ponderee), 2) as moyenne_generale,
                   SUM(moy.moy_ponderee >= 10) as nb_admis,
                   COUNT(*) as nb_eleves
            FROM (
                SELECT i.eleve_id, c.id as classe_id, c.nom,
                       ROUND(SUM(n.note * ac.coefficient) / SUM(ac.coefficient), 2) as moy_ponderee
                FROM notes n
                JOIN evaluations ev ON ev.id=n.evaluation_id
                JOIN affectations_cours ac ON ac.id=ev.affectation_id
                JOIN classes c ON c.id=ac.classe_id
                JOIN matieres m ON m.id=ac.matiere_id
                JOIN inscriptions i ON i.classe_id=c.id AND i.annee_scolaire_id=? AND i.eleve_id=n.eleve_id
                WHERE c.etablissement_id=? AND ev.periode_id=?
                GROUP BY i.eleve_id, c.id
            ) moy
            JOIN classes c ON c.id=moy.classe_id
            GROUP BY c.id ORDER BY c.nom
        ");
        $stmt->execute([$annee['id'] ?? 0, $etabId, $periodeId]);
        $parClasse = $stmt->fetchAll();

        $this->view('rapports/notes', [
            'pageTitle'        => 'Rapport Notes',
            'annee'            => $annee,
            'periodes'         => $periodes,
            'periodeId'        => $periodeId,
            'parClasseMatiere' => $parClasseMatiere,
            'parClasse'        => $parClasse,
        ]);
    }

    public function exportNotes(Request $request): void
    {
        $this->requirePermission('rapports.voir');
        $db        = Database::getInstance();
        $etabId    = Auth::etablissementId();
        $periodeId = (int) $request->get('periode', 0);

        $stmt = $db->prepare("
            SELECT c.nom as classe, m.nom as matiere,
                   ROUND(AVG(n.note), 2) as moyenne,
                   MIN(n.note) as note_min, MAX(n.note) as note_max,
                   COUNT(n.id) as nb_notes, SUM(n.note >= 10) as nb_admis
            FROM notes n
            JOIN evaluations ev ON ev.id=n.evaluation_id
            JOIN affectations_cours ac ON ac.id=ev.affectation_id
            JOIN classes c ON c.id=ac.classe_id
            JOIN matieres m ON m.id=ac.matiere_id
            WHERE c.etablissement_id=? AND ev.periode_id=?
            GROUP BY c.id, m.id ORDER BY c.nom, m.nom
        ");
        $stmt->execute([$etabId, $periodeId]);
        $rows = $stmt->fetchAll();

        $this->exportXlsx('Rapport_Notes', [
            ['Classe', 'Matière', 'Moyenne', 'Note Min', 'Note Max', 'Nb évaluations', 'Nb admis'],
        ], $rows, ['classe','matiere','moyenne','note_min','note_max','nb_notes','nb_admis']);
    }

    // -------------------------------------------------------
    // Rapport Paiements
    // -------------------------------------------------------

    public function paiements(Request $request): void
    {
        $this->requirePermission('rapports.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        // Récapitulatif par mois
        $stmt = $db->prepare("
            SELECT DATE_FORMAT(p.date_paiement,'%Y-%m') as mois,
                   SUM(p.montant) as total,
                   COUNT(*) as nb_paiements
            FROM paiements p
            JOIN dossiers_paiement dp ON dp.id=p.dossier_paiement_id
            JOIN inscriptions i ON i.id=dp.inscription_id
            JOIN classes c ON c.id=i.classe_id
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=? AND p.annule=0
            GROUP BY mois ORDER BY mois
        ");
        $stmt->execute([$etabId, $annee['id'] ?? 0]);
        $parMois = $stmt->fetchAll();

        // Total encaissé / attendu / taux recouvrement
        $stmt = $db->prepare("
            SELECT COALESCE(SUM(dp.montant_total),0) as attendu,
                   COALESCE(SUM(dp.montant_paye),0)  as paye
            FROM dossiers_paiement dp
            JOIN inscriptions i ON i.id=dp.inscription_id
            JOIN classes c ON c.id=i.classe_id
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=?
        ");
        $stmt->execute([$etabId, $annee['id'] ?? 0]);
        $global = $stmt->fetch();

        $attendu = (float) $global['attendu'];
        $paye    = (float) $global['paye'];
        $taux    = $attendu > 0 ? round($paye / $attendu * 100, 1) : 0;

        // Dossiers en retard (au moins une tranche échue non payée)
        $stmt = $db->prepare("
            SELECT COUNT(DISTINCT dp.id) as nb
            FROM tranches_paiement tp
            JOIN dossiers_paiement dp ON dp.id=tp.dossier_paiement_id
            JOIN inscriptions i ON i.id=dp.inscription_id
            JOIN classes c ON c.id=i.classe_id
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=?
              AND tp.date_echeance < CURDATE() AND tp.statut='en_attente'
        ");
        $stmt->execute([$etabId, $annee['id'] ?? 0]);
        $nbRetard = (int) $stmt->fetchColumn();

        // Par classe
        $stmt = $db->prepare("
            SELECT c.nom as classe,
                   COALESCE(SUM(dp.montant_total),0) as attendu,
                   COALESCE(SUM(dp.montant_paye),0)  as paye,
                   COUNT(DISTINCT i.id) as nb_eleves
            FROM dossiers_paiement dp
            JOIN inscriptions i ON i.id=dp.inscription_id
            JOIN classes c ON c.id=i.classe_id
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=?
            GROUP BY c.id ORDER BY c.nom
        ");
        $stmt->execute([$etabId, $annee['id'] ?? 0]);
        $parClasse = $stmt->fetchAll();

        $this->view('rapports/paiements', [
            'pageTitle' => 'Rapport Paiements',
            'annee'     => $annee,
            'parMois'   => $parMois,
            'parClasse' => $parClasse,
            'attendu'   => $attendu,
            'paye'      => $paye,
            'taux'      => $taux,
            'nbRetard'  => $nbRetard,
        ]);
    }

    public function exportPaiements(Request $request): void
    {
        $this->requirePermission('rapports.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $stmt = $db->prepare("
            SELECT e.matricule, e.nom, e.prenoms, c.nom as classe,
                   dp.montant_total, dp.montant_paye,
                   (dp.montant_total - dp.montant_paye) as reste,
                   dp.statut
            FROM dossiers_paiement dp
            JOIN inscriptions i ON i.id=dp.inscription_id
            JOIN eleves e ON e.id=i.eleve_id
            JOIN classes c ON c.id=i.classe_id
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=?
            ORDER BY c.nom, e.nom
        ");
        $stmt->execute([$etabId, $annee['id'] ?? 0]);
        $rows = $stmt->fetchAll();

        $this->exportXlsx('Rapport_Paiements', [
            ['Matricule', 'Nom', 'Prénoms', 'Classe', 'Montant attendu', 'Montant payé', 'Reste à payer', 'Statut'],
        ], $rows, ['matricule','nom','prenoms','classe','montant_total','montant_paye','reste','statut']);
    }

    // -------------------------------------------------------
    // Rapport Présences
    // -------------------------------------------------------

    public function presences(Request $request): void
    {
        $this->requirePermission('rapports.voir');
        $db      = Database::getInstance();
        $etabId  = Auth::etablissementId();
        $annee   = $this->anneeEnCours($db, $etabId);
        $classeId = (int) $request->get('classe', 0);

        // Classes pour filtre
        $stmt = $db->prepare("SELECT * FROM classes WHERE etablissement_id=? AND annee_scolaire_id=? ORDER BY nom");
        $stmt->execute([$etabId, $annee['id'] ?? 0]);
        $classes = $stmt->fetchAll();

        if (!$classeId && $classes) {
            $classeId = (int) $classes[0]['id'];
        }

        // Taux absentéisme par élève
        $stmt = $db->prepare("
            SELECT e.nom, e.prenoms, e.matricule,
                   COUNT(pr.id) as total_seances,
                   SUM(pr.statut='absent') as absences,
                   SUM(pr.statut='retard') as retards,
                   SUM(pr.statut='excuse') as excuses,
                   ROUND(SUM(pr.statut='absent') / NULLIF(COUNT(pr.id),0) * 100, 1) as taux_absence
            FROM presences pr
            JOIN seances s ON s.id=pr.seance_id
            JOIN affectations_cours ac ON ac.id=s.affectation_id
            JOIN inscriptions i ON i.classe_id=ac.classe_id AND i.eleve_id=pr.eleve_id AND i.annee_scolaire_id=?
            JOIN eleves e ON e.id=pr.eleve_id
            WHERE ac.classe_id=?
            GROUP BY pr.eleve_id
            ORDER BY absences DESC
        ");
        $stmt->execute([$annee['id'] ?? 0, $classeId]);
        $parEleve = $stmt->fetchAll();

        // Taux par matière
        $stmt = $db->prepare("
            SELECT m.nom as matiere,
                   COUNT(pr.id) as total,
                   SUM(pr.statut='absent') as absences,
                   ROUND(SUM(pr.statut='absent') / NULLIF(COUNT(pr.id),0) * 100, 1) as taux
            FROM presences pr
            JOIN seances s ON s.id=pr.seance_id
            JOIN affectations_cours ac ON ac.id=s.affectation_id
            JOIN matieres m ON m.id=ac.matiere_id
            WHERE ac.classe_id=?
            GROUP BY m.id ORDER BY absences DESC
        ");
        $stmt->execute([$classeId]);
        $parMatiere = $stmt->fetchAll();

        $this->view('rapports/presences', [
            'pageTitle'  => 'Rapport Présences',
            'annee'      => $annee,
            'classes'    => $classes,
            'classeId'   => $classeId,
            'parEleve'   => $parEleve,
            'parMatiere' => $parMatiere,
        ]);
    }

    // -------------------------------------------------------
    // Helper export XLSX
    // -------------------------------------------------------

    private function exportXlsx(string $filename, array $headers, array $rows, array $columns): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // En-tête
        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $col = 'A';
        foreach ($headers[0] as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Données
        $row = 2;
        foreach ($rows as $r) {
            $col = 'A';
            foreach ($columns as $c) {
                $sheet->setCellValue($col . $row, $r[$c] ?? '');
                $col++;
            }
            $row++;
        }

        Logger::info("Export Excel généré : {$filename}", ['rows' => count($rows)]);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // -------------------------------------------------------
    // Helper privé
    // -------------------------------------------------------

    private function anneeEnCours(\PDO $db, ?int $etabId): ?array
    {
        if (!$etabId) return null;
        $stmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1");
        $stmt->execute([$etabId]);
        return $stmt->fetch() ?: null;
    }
}
