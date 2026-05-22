<?php
namespace App\Controllers;

use App\Core\AuditLog;
use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class NoteController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('notes.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $user   = Auth::user();
        $annee  = $this->anneeEnCours($db, $etabId);

        // Enseignant : voir uniquement ses cours
        if (Auth::role() === 'enseignant') {
            $stmt = $db->prepare("
                SELECT ac.*, c.nom as classe_nom, m.nom as matiere_nom,
                       p.nom as periode_libelle,
                       (SELECT COUNT(*) FROM evaluations ev WHERE ev.affectation_id=ac.id AND ev.periode_id=p.id) as nb_evaluations
                FROM affectations_cours ac
                JOIN classes c ON c.id=ac.classe_id
                JOIN matieres m ON m.id=ac.matiere_id
                JOIN periodes p ON p.annee_scolaire_id=ac.annee_scolaire_id
                JOIN personnel per ON per.id=ac.personnel_id
                WHERE per.user_id=? AND ac.annee_scolaire_id=?
                ORDER BY c.nom, m.nom, p.ordre
            ");
            $stmt->execute([$user['id'], $annee['id'] ?? 0]);
        } else {
            $stmt = $db->prepare("
                SELECT ac.*, c.nom as classe_nom, m.nom as matiere_nom,
                       u.prenoms as prof_prenom, u.nom as prof_nom
                FROM affectations_cours ac
                JOIN classes c ON c.id=ac.classe_id
                JOIN matieres m ON m.id=ac.matiere_id
                JOIN personnel per ON per.id=ac.personnel_id
                JOIN users u ON u.id=per.user_id
                WHERE c.etablissement_id=? AND ac.annee_scolaire_id=?
                ORDER BY c.nom, m.nom
            ");
            $stmt->execute([$etabId, $annee['id'] ?? 0]);
        }
        $cours = $stmt->fetchAll();

        // Périodes
        $stmtP = $db->prepare("SELECT * FROM periodes WHERE annee_scolaire_id=? ORDER BY ordre");
        $stmtP->execute([$annee['id'] ?? 0]);
        $periodes = $stmtP->fetchAll();

        $periodeIdActif = (int) $request->get('periode', $periodes[0]['id'] ?? 0);

        $this->view('notes/index', [
            'pageTitle'     => 'Notes',
            'cours'         => $cours,
            'periodes'      => $periodes,
            'periodeActif'  => $periodeIdActif,
            'annee'         => $annee,
        ]);
    }

    public function saisir(Request $request): void
    {
        $this->requirePermission('notes.saisir');
        $db            = Database::getInstance();
        $affectationId = (int) $request->param('affectationId');
        $periodeId     = (int) $request->get('periode', 0);

        $stmtA = $db->prepare("
            SELECT ac.*, c.nom as classe_nom, m.nom as matiere_nom
            FROM affectations_cours ac
            JOIN classes c ON c.id=ac.classe_id
            JOIN matieres m ON m.id=ac.matiere_id
            WHERE ac.id=?
        ");
        $stmtA->execute([$affectationId]);
        $affectation = $stmtA->fetch();
        if (!$affectation) abort(404);

        // Vérifier accès enseignant
        if (Auth::role() === 'enseignant') {
            $stmt = $db->prepare("SELECT id FROM personnel WHERE user_id=?");
            $stmt->execute([Auth::id()]);
            $perso = $stmt->fetch();
            if (!$perso || $perso['id'] != $affectation['personnel_id']) abort(403);
        }

        // Périodes disponibles
        $stmtP = $db->prepare("SELECT * FROM periodes WHERE annee_scolaire_id=? ORDER BY ordre");
        $stmtP->execute([$affectation['annee_scolaire_id']]);
        $periodes = $stmtP->fetchAll();
        $periode  = $periodeId ? array_values(array_filter($periodes, fn($p) => $p['id'] == $periodeId))[0] ?? $periodes[0] ?? null : $periodes[0] ?? null;

        // Évaluations de la période
        $stmtE = $db->prepare("
            SELECT ev.*, te.nom as type_nom
            FROM evaluations ev
            JOIN types_evaluation te ON te.id=ev.type_evaluation_id
            WHERE ev.affectation_id=? AND ev.periode_id=?
            ORDER BY ev.date_evaluation
        ");
        $stmtE->execute([$affectationId, $periode['id'] ?? 0]);
        $evaluations = $stmtE->fetchAll();

        // Élèves de la classe
        $stmtEl = $db->prepare("
            SELECT e.*, i.id as inscription_id
            FROM inscriptions i
            JOIN eleves e ON e.id=i.eleve_id
            WHERE i.classe_id=? AND i.annee_scolaire_id=? AND i.statut='inscrit'
            ORDER BY e.nom, e.prenoms
        ");
        $stmtEl->execute([$affectation['classe_id'], $affectation['annee_scolaire_id']]);
        $eleves = $stmtEl->fetchAll();

        // Notes existantes
        $notes = [];
        foreach ($evaluations as $ev) {
            $stmtN = $db->prepare("SELECT * FROM notes WHERE evaluation_id=?");
            $stmtN->execute([$ev['id']]);
            foreach ($stmtN->fetchAll() as $n) {
                $notes[$ev['id']][$n['eleve_id']] = $n;
            }
        }

        // Types d'évaluation
        $stmtTE = $db->prepare("SELECT * FROM types_evaluation WHERE etablissement_id=? ORDER BY nom");
        $etabId = Auth::etablissementId();
        $stmtTE->execute([$etabId]);
        $typesEval = $stmtTE->fetchAll();

        $this->view('notes/saisir', [
            'pageTitle'    => 'Saisie notes — ' . $affectation['classe_nom'] . ' / ' . $affectation['matiere_nom'],
            'affectation'  => $affectation,
            'periodes'     => $periodes,
            'periode'      => $periode,
            'evaluations'  => $evaluations,
            'eleves'       => $eleves,
            'notes'        => $notes,
            'typesEval'    => $typesEval,
        ]);
    }

    public function storeBulk(Request $request): void
    {
        $this->requirePermission('notes.saisir');
        $db            = Database::getInstance();
        $affectationId = (int) $request->post('affectation_id');
        $evaluationId  = (int) $request->post('evaluation_id');
        $notesData     = $request->post('notes', []);

        $db->beginTransaction();
        try {
            foreach ($notesData as $eleveId => $note) {
                $eleveId = (int) $eleveId;
                $val     = $note === '' ? null : (float) $note;
                $statut  = $val === null ? 'absent' : 'present';

                // Upsert
                $db->prepare("INSERT INTO notes (evaluation_id, eleve_id, note, statut, saisie_par) VALUES (?,?,?,?,?)
                              ON DUPLICATE KEY UPDATE note=VALUES(note), statut=VALUES(statut), saisie_par=VALUES(saisie_par), updated_at=NOW()")
                   ->execute([$evaluationId, $eleveId, $val, $statut, Auth::id()]);
            }
            $db->commit();
            AuditLog::log('saisie_notes', 'notes', $evaluationId, 'evaluations', null, ['nb_notes' => count($notesData)]);
            Session::flash('success', 'Notes enregistrées avec succès.');
        } catch (\Throwable $e) {
            $db->rollBack();
            Session::flash('error', 'Erreur lors de la sauvegarde des notes.');
        }

        redirect("/notes/saisir/$affectationId?periode=" . $request->post('periode_id'));
    }

    public function createEvaluation(Request $request): void
    {
        $this->requirePermission('notes.saisir');
        $db   = Database::getInstance();
        $data = $request->all();

        $db->prepare("INSERT INTO evaluations (affectation_id, periode_id, type_evaluation_id, titre, date_evaluation, note_sur, coefficient) VALUES (?,?,?,?,?,?,?)")
           ->execute([
               (int) $data['affectation_id'],
               (int) $data['periode_id'],
               (int) $data['type_evaluation_id'],
               $data['titre'] ?? null,
               $data['date_evaluation'],
               (float) ($data['note_sur'] ?? 20),
               (float) ($data['coefficient'] ?? 1),
           ]);

        Session::flash('success', 'Évaluation créée.');
        redirect("/notes/saisir/{$data['affectation_id']}?periode={$data['periode_id']}");
    }

    public function byEvaluation(Request $request): void
    {
        $this->requirePermission('notes.voir');
        $db = Database::getInstance();
        $id = (int) $request->param('id');

        $stmtE = $db->prepare("
            SELECT ev.*, te.nom as type_nom,
                   ac.classe_id, c.nom as classe_nom, m.nom as matiere_nom, p.nom as periode_nom
            FROM evaluations ev
            JOIN types_evaluation te ON te.id=ev.type_evaluation_id
            JOIN affectations_cours ac ON ac.id=ev.affectation_id
            JOIN classes c ON c.id=ac.classe_id
            JOIN matieres m ON m.id=ac.matiere_id
            JOIN periodes p ON p.id=ev.periode_id
            WHERE ev.id=?
        ");
        $stmtE->execute([$id]);
        $evaluation = $stmtE->fetch();
        if (!$evaluation) abort(404);

        $stmtN = $db->prepare("
            SELECT n.note, n.statut, e.nom, e.prenoms, e.matricule
            FROM inscriptions i
            JOIN eleves e ON e.id=i.eleve_id
            LEFT JOIN notes n ON n.eleve_id=e.id AND n.evaluation_id=?
            WHERE i.classe_id=? AND i.statut='inscrit'
            ORDER BY e.nom, e.prenoms
        ");
        $stmtN->execute([$id, $evaluation['classe_id']]);
        $notes = $stmtN->fetchAll();

        $valeurs = array_filter(array_map(fn($n) => $n['note'], $notes), fn($v) => $v !== null);
        $stats = [
            'nb_notes'  => count($valeurs),
            'nb_absent' => count(array_filter($notes, fn($n) => ($n['statut'] ?? '') === 'absent' || $n['note'] === null)),
            'moyenne'   => count($valeurs) ? round(array_sum($valeurs) / count($valeurs), 2) : null,
            'max'       => count($valeurs) ? max($valeurs) : null,
            'min'       => count($valeurs) ? min($valeurs) : null,
        ];

        $this->view('notes/byEvaluation', [
            'pageTitle'  => 'Notes — ' . ($evaluation['titre'] ?? $evaluation['type_nom']) . ' (' . $evaluation['classe_nom'] . ')',
            'evaluation' => $evaluation,
            'notes'      => $notes,
            'stats'      => $stats,
        ]);
    }

    public function byClasse(Request $request): void
    {
        $this->requirePermission('notes.voir');
        $db        = Database::getInstance();
        $classeId  = (int) $request->param('classeId');
        $periodeId = (int) $request->get('periode', 0);

        $stmtC = $db->prepare("SELECT c.*, n.nom as niveau_nom FROM classes c JOIN niveaux n ON n.id=c.niveau_id WHERE c.id=?");
        $stmtC->execute([$classeId]);
        $classe = $stmtC->fetch();
        if (!$classe) abort(404);

        if ($classe['etablissement_id'] != Auth::etablissementId() && !Auth::isSuperAdmin()) abort(403);

        $stmtP = $db->prepare("SELECT * FROM periodes WHERE annee_scolaire_id=? ORDER BY ordre");
        $stmtP->execute([$classe['annee_scolaire_id']]);
        $periodes = $stmtP->fetchAll();
        $periode  = $periodeId
            ? (array_values(array_filter($periodes, fn($p) => $p['id'] == $periodeId))[0] ?? ($periodes[0] ?? null))
            : ($periodes[0] ?? null);

        $stmtM = $db->prepare("
            SELECT ac.id, ac.coefficient, m.nom as matiere_nom,
                   u.prenoms as prof_prenom, u.nom as prof_nom
            FROM affectations_cours ac
            JOIN matieres m ON m.id=ac.matiere_id
            JOIN personnel per ON per.id=ac.personnel_id
            JOIN users u ON u.id=per.user_id
            WHERE ac.classe_id=? ORDER BY m.nom
        ");
        $stmtM->execute([$classeId]);
        $matieres = $stmtM->fetchAll();

        $stmtEl = $db->prepare("
            SELECT e.*, i.id as inscription_id
            FROM inscriptions i JOIN eleves e ON e.id=i.eleve_id
            WHERE i.classe_id=? AND i.statut='inscrit' ORDER BY e.nom, e.prenoms
        ");
        $stmtEl->execute([$classeId]);
        $eleves = $stmtEl->fetchAll();

        $moyennes = [];
        if ($periode) {
            $stmtMoy = $db->prepare("
                SELECT moy.inscription_id, moy.affectation_id, moy.moyenne, moy.rang
                FROM moyennes moy
                JOIN inscriptions i ON i.id=moy.inscription_id
                WHERE i.classe_id=? AND moy.periode_id=?
            ");
            $stmtMoy->execute([$classeId, $periode['id']]);
            foreach ($stmtMoy->fetchAll() as $m) {
                $key = $m['affectation_id'] ?? 'gen';
                $moyennes[$m['inscription_id']][$key] = $m['moyenne'];
                if ($m['affectation_id'] === null) {
                    $moyennes[$m['inscription_id']]['rang'] = $m['rang'];
                }
            }
        }

        $this->view('notes/byClasse', [
            'pageTitle' => 'Notes — ' . $classe['nom'],
            'classe'    => $classe,
            'periodes'  => $periodes,
            'periode'   => $periode,
            'matieres'  => $matieres,
            'eleves'    => $eleves,
            'moyennes'  => $moyennes,
        ]);
    }

    // -------------------------------------------------------
    // Calcul des moyennes
    // -------------------------------------------------------

    public static function calculerMoyennesClasse(\PDO $db, int $classeId, int $periodeId): void
    {
        // Récupérer toutes les inscriptions
        $stmt = $db->prepare("SELECT i.id, i.eleve_id FROM inscriptions i WHERE i.classe_id=? AND i.statut='inscrit'");
        $stmt->execute([$classeId]);
        $inscriptions = $stmt->fetchAll();

        // Toutes les affectations de la classe
        $stmt = $db->prepare("SELECT ac.id, ac.coefficient FROM affectations_cours ac WHERE ac.classe_id=? AND ac.annee_scolaire_id=(SELECT annee_scolaire_id FROM classes WHERE id=?)");
        $stmt->execute([$classeId, $classeId]);
        $affectations = $stmt->fetchAll();

        foreach ($inscriptions as $insc) {
            $eleveId     = $insc['eleve_id'];
            $inscId      = $insc['id'];
            $totalPoints = 0;
            $totalCoeff  = 0;

            foreach ($affectations as $ac) {
                // Moyenne matière pour cette période
                $stmtN = $db->prepare("
                    SELECT AVG(CASE WHEN n.statut='present' THEN (n.note/ev.note_sur)*20 END) as moy
                    FROM notes n
                    JOIN evaluations ev ON ev.id=n.evaluation_id
                    WHERE ev.affectation_id=? AND ev.periode_id=? AND n.eleve_id=?
                ");
                $stmtN->execute([$ac['id'], $periodeId, $eleveId]);
                $moy = $stmtN->fetchColumn();
                if ($moy !== null) {
                    $totalPoints += $moy * $ac['coefficient'];
                    $totalCoeff  += $ac['coefficient'];

                    // Stocker moyenne matière
                    $db->prepare("INSERT INTO moyennes (inscription_id, affectation_id, periode_id, moyenne) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE moyenne=VALUES(moyenne)")
                       ->execute([$inscId, $ac['id'], $periodeId, round($moy, 2)]);
                }
            }

            // Moyenne générale
            $moyGen = $totalCoeff > 0 ? round($totalPoints / $totalCoeff, 2) : null;
            $db->prepare("INSERT INTO moyennes (inscription_id, affectation_id, periode_id, moyenne, appreciation) VALUES (?,NULL,?,?,?) ON DUPLICATE KEY UPDATE moyenne=VALUES(moyenne), appreciation=VALUES(appreciation)")
               ->execute([$inscId, $periodeId, $moyGen, $moyGen !== null ? appreciation($moyGen) : null]);
        }

        // Calcul des rangs
        $stmt = $db->prepare("SELECT m.id, m.inscription_id, m.moyenne FROM moyennes m JOIN inscriptions i ON i.id=m.inscription_id WHERE i.classe_id=? AND m.periode_id=? AND m.affectation_id IS NULL ORDER BY m.moyenne DESC");
        $stmt->execute([$classeId, $periodeId]);
        $moyennes = $stmt->fetchAll();
        $rang = 1;
        foreach ($moyennes as $m) {
            $db->prepare("UPDATE moyennes SET rang=? WHERE id=?")->execute([$rang, $m['id']]);
            $rang++;
        }
    }

    private function anneeEnCours(\PDO $db, ?int $etabId): ?array
    {
        if (!$etabId) return null;
        $stmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1");
        $stmt->execute([$etabId]);
        return $stmt->fetch() ?: null;
    }
}
