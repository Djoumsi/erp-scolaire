<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class BulletinController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('bulletins.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $stmtC = $db->prepare("SELECT c.*, n.nom as niveau_nom, COUNT(i.id) as effectif FROM classes c JOIN niveaux n ON n.id=c.niveau_id LEFT JOIN inscriptions i ON i.classe_id=c.id AND i.annee_scolaire_id=c.annee_scolaire_id WHERE c.etablissement_id=? AND c.annee_scolaire_id=? GROUP BY c.id ORDER BY c.nom");
        $stmtC->execute([$etabId, $annee['id'] ?? 0]);
        $classes = $stmtC->fetchAll();

        $stmtP = $db->prepare("SELECT * FROM periodes WHERE annee_scolaire_id=? ORDER BY ordre");
        $stmtP->execute([$annee['id'] ?? 0]);
        $periodes = $stmtP->fetchAll();

        $this->view('bulletins/index', [
            'pageTitle' => 'Bulletins',
            'classes'   => $classes,
            'periodes'  => $periodes,
            'annee'     => $annee,
        ]);
    }

    public function generer(Request $request): void
    {
        $this->requirePermission('bulletins.generer');
        $db       = Database::getInstance();
        $classeId = (int) $request->param('classeId');
        $periodeId = (int) $request->param('periodeId');

        // Calculer les moyennes
        NoteController::calculerMoyennesClasse($db, $classeId, $periodeId);

        $stmtI = $db->prepare("SELECT i.id, i.eleve_id FROM inscriptions i WHERE i.classe_id=? AND i.statut='inscrit'");
        $stmtI->execute([$classeId]);
        $inscriptions = $stmtI->fetchAll();

        $generated = 0;
        foreach ($inscriptions as $insc) {
            $stmtM = $db->prepare("SELECT moyenne, rang FROM moyennes WHERE inscription_id=? AND affectation_id IS NULL AND periode_id=?");
            $stmtM->execute([$insc['id'], $periodeId]);
            $moy = $stmtM->fetch();

            $mention  = $moy ? mentionBac($moy['moyenne'] ?? 0) : null;
            $effectif = count($inscriptions);

            $db->prepare("INSERT INTO bulletins (inscription_id, periode_id, moyenne_generale, rang, mention, effectif_classe, valide)
                          VALUES (?,?,?,?,?,?,0)
                          ON DUPLICATE KEY UPDATE moyenne_generale=VALUES(moyenne_generale), rang=VALUES(rang), mention=VALUES(mention), effectif_classe=VALUES(effectif_classe)")
               ->execute([$insc['id'], $periodeId, $moy['moyenne'] ?? null, $moy['rang'] ?? null, $mention, $effectif]);
            $generated++;
        }

        Session::flash('success', "$generated bulletin(s) généré(s).");
        redirect('/bulletins');
    }

    public function generate(Request $request): void
    {
        $classeId  = (int) $request->post('classe_id');
        $periodeId = (int) $request->post('periode_id');
        redirect("/bulletins/generer/$classeId/$periodeId");
    }

    public function pdf(Request $request): void
    {
        $this->requirePermission('bulletins.voir');
        $db         = Database::getInstance();
        $bulletinId = (int) $request->param('id');

        $stmt = $db->prepare("
            SELECT b.*, e.nom, e.prenoms, e.matricule, e.sexe, e.date_naissance,
                   c.nom as classe_nom, c.id as classe_id,
                   p.nom as periode_nom, a.libelle as annee,
                   et.nom as etab_nom, et.adresse as etab_adresse, et.logo as etab_logo
            FROM bulletins b
            JOIN inscriptions i ON i.id=b.inscription_id
            JOIN eleves e ON e.id=i.eleve_id
            JOIN classes c ON c.id=i.classe_id
            JOIN periodes p ON p.id=b.periode_id
            JOIN annees_scolaires a ON a.id=p.annee_scolaire_id
            JOIN etablissements et ON et.id=c.etablissement_id
            WHERE b.id=?
        ");
        $stmt->execute([$bulletinId]);
        $bulletin = $stmt->fetch();
        if (!$bulletin) abort(404);

        // Notes par matière avec moyennes et rang
        $stmtN = $db->prepare("
            SELECT m.nom as matiere_nom, ac.coefficient,
                   moy.moyenne, moy.appreciation, moy.rang,
                   u.prenoms as prof_prenom, u.nom as prof_nom
            FROM affectations_cours ac
            JOIN matieres m ON m.id=ac.matiere_id
            JOIN personnel per ON per.id=ac.personnel_id
            JOIN users u ON u.id=per.user_id
            LEFT JOIN moyennes moy ON moy.affectation_id=ac.id
                AND moy.inscription_id=? AND moy.periode_id=?
            WHERE ac.classe_id=?
              AND ac.annee_scolaire_id=(SELECT annee_scolaire_id FROM classes WHERE id=?)
            ORDER BY m.nom
        ");
        $stmtN->execute([
            $bulletin['inscription_id'],
            $bulletin['periode_id'],
            $bulletin['classe_id'],
            $bulletin['classe_id'],
        ]);
        $matieres = $stmtN->fetchAll();

        $this->view('bulletins/pdf/bulletin', [
            'bulletin'  => $bulletin,
            'matieres'  => $matieres,
            'pageTitle' => 'Bulletin — ' . $bulletin['prenoms'] . ' ' . $bulletin['nom'],
        ], 'print');
    }

    public function byClasse(Request $request): void
    {
        $this->requirePermission('bulletins.voir');
        $db        = Database::getInstance();
        $classeId  = (int) $request->param('classeId');
        $periodeId = (int) $request->get('periode', 0);

        $stmtC = $db->prepare("
            SELECT c.*, n.nom as niveau_nom
            FROM classes c JOIN niveaux n ON n.id=c.niveau_id
            WHERE c.id=?
        ");
        $stmtC->execute([$classeId]);
        $classe = $stmtC->fetch();
        if (!$classe) abort(404);

        if ($classe['etablissement_id'] != Auth::etablissementId() && !Auth::isSuperAdmin()) abort(403);

        $stmtP = $db->prepare("SELECT * FROM periodes WHERE annee_scolaire_id=? ORDER BY ordre");
        $stmtP->execute([$classe['annee_scolaire_id']]);
        $periodes = $stmtP->fetchAll();

        $periode = $periodeId
            ? (array_values(array_filter($periodes, fn($p) => $p['id'] == $periodeId))[0] ?? ($periodes[0] ?? null))
            : ($periodes[0] ?? null);

        $bulletins = [];
        if ($periode) {
            $stmtB = $db->prepare("
                SELECT b.*, e.nom, e.prenoms, e.matricule, e.sexe
                FROM bulletins b
                JOIN inscriptions i ON i.id=b.inscription_id
                JOIN eleves e ON e.id=i.eleve_id
                WHERE i.classe_id=? AND b.periode_id=?
                ORDER BY b.rang ASC, e.nom ASC
            ");
            $stmtB->execute([$classeId, $periode['id']]);
            $bulletins = $stmtB->fetchAll();
        }

        $this->view('bulletins/byClasse', [
            'pageTitle' => 'Bulletins — ' . $classe['nom'],
            'classe'    => $classe,
            'periodes'  => $periodes,
            'periode'   => $periode,
            'bulletins' => $bulletins,
        ]);
    }

    private function anneeEnCours(\PDO $db, ?int $etabId): ?array
    {
        if (!$etabId) return null;
        $stmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1");
        $stmt->execute([$etabId]);
        return $stmt->fetch() ?: null;
    }
}
