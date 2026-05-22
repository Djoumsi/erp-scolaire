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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ComptabiliteController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('comptabilite.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);
        $mois   = $request->get('mois', date('Y-m'));

        $stmt = $db->prepare("
            SELECT t.*, cc.nom as categorie, cc.type
            FROM transactions t
            JOIN categories_comptables cc ON cc.id=t.categorie_id
            WHERE t.etablissement_id=? AND t.annee_scolaire_id=? AND DATE_FORMAT(t.date_transaction,'%Y-%m')=?
            ORDER BY t.date_transaction DESC
        ");
        $stmt->execute([$etabId, $annee['id'] ?? 0, $mois]);
        $transactions = $stmt->fetchAll();

        $stmtTot = $db->prepare("SELECT cc.type, COALESCE(SUM(t.montant),0) as total FROM transactions t JOIN categories_comptables cc ON cc.id=t.categorie_id WHERE t.etablissement_id=? AND t.annee_scolaire_id=? AND DATE_FORMAT(t.date_transaction,'%Y-%m')=? GROUP BY cc.type");
        $stmtTot->execute([$etabId, $annee['id'] ?? 0, $mois]);
        $totaux = ['recette' => 0, 'depense' => 0];
        foreach ($stmtTot->fetchAll() as $t) { $totaux[$t['type']] = (float)$t['total']; }

        $stmtCat = $db->prepare("SELECT * FROM categories_comptables WHERE etablissement_id=? ORDER BY type, nom");
        $stmtCat->execute([$etabId]);

        $this->view('comptabilite/index', [
            'pageTitle'    => 'Comptabilité',
            'transactions' => $transactions,
            'totaux'       => $totaux,
            'solde'        => $totaux['recette'] - $totaux['depense'],
            'categories'   => $stmtCat->fetchAll(),
            'annee'        => $annee,
            'mois'         => $mois,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requirePermission('comptabilite.creer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);
        $data   = $request->all();

        $stmtCat = $db->prepare("SELECT type FROM categories_comptables WHERE id=?");
        $stmtCat->execute([(int)$data['categorie_id']]);
        $cat = $stmtCat->fetch();

        $db->prepare("INSERT INTO transactions (etablissement_id, annee_scolaire_id, categorie_id, libelle, montant, type, date_transaction, reference, saisi_par) VALUES (?,?,?,?,?,?,?,?,?)")
           ->execute([$etabId, $annee['id'], (int)$data['categorie_id'], $data['libelle'], (float)$data['montant'], $cat['type'], $data['date_transaction'], $data['reference'] ?? null, Auth::id()]);

        Logger::info('Transaction comptable créée', ['montant' => $data['montant'], 'type' => $cat['type']]);
        Session::flash('success', 'Transaction enregistrée.');
        redirect('/comptabilite');
    }

    // -------------------------------------------------------
    // Bilan annuel
    // -------------------------------------------------------

    public function bilan(Request $request): void
    {
        $this->requirePermission('comptabilite.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        if (!$annee) {
            $this->view('comptabilite/bilan', ['pageTitle' => 'Bilan comptable', 'annee' => null, 'parMois' => [], 'parCategorie' => []]);
            return;
        }

        // Récapitulatif par mois
        $stmt = $db->prepare("
            SELECT DATE_FORMAT(t.date_transaction,'%Y-%m') as mois,
                   cc.type,
                   SUM(t.montant) as total
            FROM transactions t
            JOIN categories_comptables cc ON cc.id=t.categorie_id
            WHERE t.etablissement_id=? AND t.annee_scolaire_id=?
            GROUP BY mois, cc.type
            ORDER BY mois
        ");
        $stmt->execute([$etabId, $annee['id']]);
        $rawMois = $stmt->fetchAll();

        // Reformater : ['2025-09' => ['recette' => X, 'depense' => Y], ...]
        $parMois = [];
        foreach ($rawMois as $r) {
            $parMois[$r['mois']] = $parMois[$r['mois']] ?? ['recette' => 0, 'depense' => 0];
            $parMois[$r['mois']][$r['type']] = (float) $r['total'];
        }

        // Par catégorie
        $stmt = $db->prepare("
            SELECT cc.nom, cc.type, SUM(t.montant) as total
            FROM transactions t
            JOIN categories_comptables cc ON cc.id=t.categorie_id
            WHERE t.etablissement_id=? AND t.annee_scolaire_id=?
            GROUP BY cc.id ORDER BY cc.type, total DESC
        ");
        $stmt->execute([$etabId, $annee['id']]);
        $parCategorie = $stmt->fetchAll();

        // Totaux globaux
        $totalRecettes = array_sum(array_column(array_filter($parCategorie, fn($c) => $c['type'] === 'recette'), 'total'));
        $totalDepenses = array_sum(array_column(array_filter($parCategorie, fn($c) => $c['type'] === 'depense'), 'total'));

        $this->view('comptabilite/bilan', [
            'pageTitle'    => 'Bilan comptable',
            'annee'        => $annee,
            'parMois'      => $parMois,
            'parCategorie' => $parCategorie,
            'totalRecettes'=> $totalRecettes,
            'totalDepenses'=> $totalDepenses,
            'solde'        => $totalRecettes - $totalDepenses,
        ]);
    }

    // -------------------------------------------------------
    // Export Excel
    // -------------------------------------------------------

    public function export(Request $request): void
    {
        $this->requirePermission('comptabilite.exporter');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);
        $mois   = $request->get('mois', '');

        $sql    = "SELECT t.date_transaction, cc.nom as categorie, cc.type, t.libelle, t.reference, t.montant FROM transactions t JOIN categories_comptables cc ON cc.id=t.categorie_id WHERE t.etablissement_id=? AND t.annee_scolaire_id=?";
        $params = [$etabId, $annee['id'] ?? 0];

        if ($mois) {
            $sql   .= " AND DATE_FORMAT(t.date_transaction,'%Y-%m')=?";
            $params[] = $mois;
        }
        $sql .= " ORDER BY t.date_transaction";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $filename = 'Comptabilite_' . ($annee['libelle'] ?? date('Y')) . ($mois ? '_'.$mois : '');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Transactions');

        // En-têtes
        $headers = ['Date', 'Catégorie', 'Type', 'Libellé', 'Référence', 'Montant (FCFA)'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Données
        $row = 2;
        $totalRec = 0; $totalDep = 0;
        foreach ($rows as $r) {
            $sheet->setCellValue('A' . $row, $r['date_transaction']);
            $sheet->setCellValue('B' . $row, $r['categorie']);
            $sheet->setCellValue('C' . $row, $r['type'] === 'recette' ? 'Recette' : 'Dépense');
            $sheet->setCellValue('D' . $row, $r['libelle']);
            $sheet->setCellValue('E' . $row, $r['reference'] ?? '');
            $sheet->setCellValue('F' . $row, (float) $r['montant']);

            if ($r['type'] === 'recette') {
                $sheet->getStyle('C' . $row)->getFont()->getColor()->setRGB('16A34A');
                $totalRec += (float) $r['montant'];
            } else {
                $sheet->getStyle('C' . $row)->getFont()->getColor()->setRGB('DC2626');
                $totalDep += (float) $r['montant'];
            }
            $row++;
        }

        // Totaux
        $row++;
        $sheet->setCellValue('E' . $row, 'Total Recettes :');
        $sheet->setCellValue('F' . $row, $totalRec);
        $sheet->getStyle('E' . $row . ':F' . $row)->getFont()->setBold(true)->getColor()->setRGB('16A34A');
        $row++;
        $sheet->setCellValue('E' . $row, 'Total Dépenses :');
        $sheet->setCellValue('F' . $row, $totalDep);
        $sheet->getStyle('E' . $row . ':F' . $row)->getFont()->setBold(true)->getColor()->setRGB('DC2626');
        $row++;
        $sheet->setCellValue('E' . $row, 'Solde :');
        $sheet->setCellValue('F' . $row, $totalRec - $totalDep);
        $sheet->getStyle('E' . $row . ':F' . $row)->getFont()->setBold(true);

        Logger::info('Export comptabilité généré', ['mois' => $mois, 'nb_lignes' => count($rows)]);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        (new Xlsx($spreadsheet))->save('php://output');
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
