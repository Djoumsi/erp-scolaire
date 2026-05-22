<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class ExamenController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('examens.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $stmt = $db->prepare("SELECT * FROM examens WHERE etablissement_id=? AND annee_scolaire_id=? ORDER BY date_debut DESC");
        $stmt->execute([$etabId, $annee['id'] ?? 0]);

        $this->view('examens/index', ['pageTitle' => 'Examens', 'examens' => $stmt->fetchAll(), 'annee' => $annee]);
    }

    public function create(Request $request): void
    {
        $this->requirePermission('examens.creer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);
        $stmt   = $db->prepare("SELECT * FROM periodes WHERE annee_scolaire_id=? ORDER BY ordre");
        $stmt->execute([$annee['id'] ?? 0]);
        $this->view('examens/create', ['pageTitle' => 'Créer un examen', 'periodes' => $stmt->fetchAll(), 'annee' => $annee]);
    }

    public function store(Request $request): void
    {
        $this->requirePermission('examens.creer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);
        $data   = $request->all();
        $db->prepare("INSERT INTO examens (etablissement_id, annee_scolaire_id, periode_id, nom, type, date_debut, date_fin) VALUES (?,?,?,?,?,?,?)")
           ->execute([$etabId, $annee['id'], $data['periode_id'] ?: null, $data['nom'], $data['type'] ?? 'interne', $data['date_debut'], $data['date_fin']]);
        Session::flash('success', 'Examen créé.');
        redirect('/examens');
    }

    public function show(Request $request): void
    {
        $this->requirePermission('examens.voir');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("SELECT * FROM examens WHERE id=?");
        $stmt->execute([$id]);
        $examen = $stmt->fetch();
        if (!$examen) abort(404);

        $stmtP = $db->prepare("SELECT pe.*, m.nom as matiere_nom, c.nom as classe_nom, s.nom as salle_nom FROM planning_examens pe JOIN matieres m ON m.id=pe.matiere_id LEFT JOIN classes c ON c.id=pe.classe_id LEFT JOIN salles s ON s.id=pe.salle_id WHERE pe.examen_id=? ORDER BY pe.date_epreuve, pe.heure_debut");
        $stmtP->execute([$id]);

        $this->view('examens/show', ['pageTitle' => $examen['nom'], 'examen' => $examen, 'planning' => $stmtP->fetchAll()]);
    }

    public function edit(Request $request): void
    {
        $this->requirePermission('examens.modifier');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("SELECT * FROM examens WHERE id=?");
        $stmt->execute([$id]);
        $examen = $stmt->fetch();
        if (!$examen) abort(404);

        $etabId = Auth::etablissementId();
        $stmtP  = $db->prepare("SELECT * FROM periodes WHERE annee_scolaire_id=? ORDER BY ordre");
        $stmtP->execute([$examen['annee_scolaire_id']]);

        $this->view('examens/edit', [
            'pageTitle' => 'Modifier — ' . $examen['nom'],
            'examen'    => $examen,
            'periodes'  => $stmtP->fetchAll(),
        ]);
    }

    public function update(Request $request): void
    {
        $this->requirePermission('examens.modifier');
        $db   = Database::getInstance();
        $id   = (int) $request->param('id');
        $data = $request->all();

        $stmt = $db->prepare("SELECT id FROM examens WHERE id=?");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) abort(404);

        $db->prepare("UPDATE examens SET nom=?, type=?, periode_id=?, date_debut=?, date_fin=? WHERE id=?")
           ->execute([
               $data['nom'],
               $data['type'] ?? 'interne',
               $data['periode_id'] ?: null,
               $data['date_debut'],
               $data['date_fin'],
               $id,
           ]);

        Session::flash('success', 'Examen mis à jour.');
        redirect("/examens/$id");
    }

    public function addPlanning(Request $request): void
    {
        $this->requirePermission('examens.modifier');
        $db   = Database::getInstance();
        $id   = (int) $request->param('id');
        $data = $request->all();
        $db->prepare("INSERT INTO planning_examens (examen_id, matiere_id, classe_id, salle_id, date_epreuve, heure_debut, heure_fin, duree_minutes) VALUES (?,?,?,?,?,?,?,?)")
           ->execute([$id, (int)$data['matiere_id'], $data['classe_id'] ?: null, $data['salle_id'] ?: null, $data['date_epreuve'], $data['heure_debut'], $data['heure_fin'], $data['duree_minutes'] ?: null]);
        Session::flash('success', 'Épreuve ajoutée au planning.');
        redirect("/examens/$id");
    }

    private function anneeEnCours(\PDO $db, ?int $etabId): ?array
    {
        if (!$etabId) return null;
        $stmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1");
        $stmt->execute([$etabId]);
        return $stmt->fetch() ?: null;
    }
}
