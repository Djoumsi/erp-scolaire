<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class EmploiDuTempsController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('emploi_temps.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $stmtC = $db->prepare("SELECT * FROM classes WHERE etablissement_id=? AND annee_scolaire_id=? ORDER BY nom");
        $stmtC->execute([$etabId, $annee['id'] ?? 0]);

        $this->view('emploi-du-temps/index', ['pageTitle' => 'Emploi du Temps', 'classes' => $stmtC->fetchAll(), 'annee' => $annee]);
    }

    public function show(Request $request): void
    {
        $this->requirePermission('emploi_temps.voir');
        $db       = Database::getInstance();
        $classeId = (int) $request->param('classeId');

        $stmtEDT = $db->prepare("
            SELECT edt.*, cr.heure_debut, cr.heure_fin, cr.nom as creneau_nom, cr.ordre,
                   m.nom as matiere_nom, s.nom as salle_nom,
                   u.prenoms as prof_prenom, u.nom as prof_nom
            FROM emplois_du_temps edt
            JOIN creneaux_horaires cr ON cr.id=edt.creneau_id
            JOIN affectations_cours ac ON ac.id=edt.affectation_id
            JOIN matieres m ON m.id=ac.matiere_id
            LEFT JOIN salles s ON s.id=edt.salle_id
            JOIN personnel p ON p.id=ac.personnel_id
            JOIN users u ON u.id=p.user_id
            WHERE edt.classe_id=?
            ORDER BY edt.jour_semaine, cr.ordre
        ");
        $stmtEDT->execute([$classeId]);
        $edt = $stmtEDT->fetchAll();

        $stmtC = $db->prepare("SELECT * FROM creneaux_horaires WHERE etablissement_id=(SELECT etablissement_id FROM classes WHERE id=?) ORDER BY ordre");
        $stmtC->execute([$classeId]);
        $creneaux = $stmtC->fetchAll();

        $stmtCl = $db->prepare("SELECT * FROM classes WHERE id=?");
        $stmtCl->execute([$classeId]);
        $classe = $stmtCl->fetch();

        // Structurer EDT en grille [jour][creneau]
        $grille = [];
        $jours  = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];
        foreach ($jours as $j => $nom) {
            $grille[$j] = [];
            foreach ($creneaux as $cr) {
                $grille[$j][$cr['id']] = null;
            }
        }
        foreach ($edt as $e) {
            $grille[$e['jour_semaine']][$e['creneau_id']] = $e;
        }

        $this->view('emploi-du-temps/show', [
            'pageTitle' => 'EDT — ' . ($classe['nom'] ?? ''),
            'classe'    => $classe,
            'creneaux'  => $creneaux,
            'grille'    => $grille,
            'jours'     => $jours,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requirePermission('emploi_temps.modifier');
        $db   = Database::getInstance();
        $data = $request->all();

        try {
            $db->prepare("INSERT INTO emplois_du_temps (classe_id, annee_scolaire_id, affectation_id, salle_id, creneau_id, jour_semaine) VALUES (?,?,?,?,?,?)")
               ->execute([(int)$data['classe_id'], (int)$data['annee_scolaire_id'], (int)$data['affectation_id'], $data['salle_id'] ?: null, (int)$data['creneau_id'], (int)$data['jour_semaine']]);
            Session::flash('success', 'Cours ajouté à l\'emploi du temps.');
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                Session::flash('error', 'Conflit détecté : la salle ou l\'enseignant est déjà occupé sur ce créneau.');
            } else {
                Session::flash('error', 'Erreur lors de l\'ajout.');
            }
        }
        redirect("/emploi-du-temps/{$data['classe_id']}");
    }

    public function destroy(Request $request): void
    {
        $this->requirePermission('emploi_temps.modifier');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("SELECT classe_id FROM emplois_du_temps WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        $db->prepare("DELETE FROM emplois_du_temps WHERE id=?")->execute([$id]);
        Session::flash('success', 'Cours retiré de l\'emploi du temps.');
        redirect("/emploi-du-temps/" . ($row['classe_id'] ?? ''));
    }

    private function anneeEnCours(\PDO $db, ?int $etabId): ?array
    {
        if (!$etabId) return null;
        $stmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1");
        $stmt->execute([$etabId]);
        return $stmt->fetch() ?: null;
    }
}
