<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class ClasseController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('classes.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $stmt = $db->prepare("
            SELECT c.*, n.nom as niveau_nom, cy.nom as cycle_nom,
                   u.prenoms as titulaire_prenom, u.nom as titulaire_nom,
                   COUNT(i.id) as effectif
            FROM classes c
            JOIN niveaux n ON n.id=c.niveau_id
            JOIN cycles cy ON cy.id=n.cycle_id
            LEFT JOIN personnel p ON p.id=c.titulaire_id
            LEFT JOIN users u ON u.id=p.user_id
            LEFT JOIN inscriptions i ON i.classe_id=c.id AND i.statut='inscrit'
            WHERE c.etablissement_id=? AND c.annee_scolaire_id=?
            GROUP BY c.id
            ORDER BY cy.ordre, n.ordre, c.nom
        ");
        $stmt->execute([$etabId, $annee['id'] ?? 0]);

        $this->view('classes/index', [
            'pageTitle' => 'Classes',
            'classes'   => $stmt->fetchAll(),
            'annee'     => $annee,
        ]);
    }

    public function create(Request $request): void
    {
        $this->requirePermission('classes.creer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);

        $stmtN = $db->prepare("SELECT n.*, cy.nom as cycle_nom FROM niveaux n JOIN cycles cy ON cy.id=n.cycle_id WHERE cy.etablissement_id=? ORDER BY cy.ordre, n.ordre");
        $stmtN->execute([$etabId]);
        $stmtP = $db->prepare("SELECT p.id, u.nom, u.prenoms FROM personnel p JOIN users u ON u.id=p.user_id WHERE p.etablissement_id=? AND p.type='enseignant' AND p.deleted_at IS NULL ORDER BY u.nom");
        $stmtP->execute([$etabId]);

        $this->view('classes/create', [
            'pageTitle'   => 'Créer une classe',
            'niveaux'     => $stmtN->fetchAll(),
            'enseignants' => $stmtP->fetchAll(),
            'annee'       => $annee,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requirePermission('classes.creer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $data   = $request->all();
        $annee  = $this->anneeEnCours($db, $etabId);

        $db->prepare("INSERT INTO classes (etablissement_id, annee_scolaire_id, niveau_id, nom, effectif_max, titulaire_id) VALUES (?,?,?,?,?,?)")
           ->execute([$etabId, $annee['id'], (int)$data['niveau_id'], $data['nom'], $data['effectif_max'] ?? 40, $data['titulaire_id'] ?: null]);

        Session::flash('success', "Classe \"{$data['nom']}\" créée.");
        redirect('/classes');
    }

    public function show(Request $request): void
    {
        $this->requirePermission('classes.voir');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("SELECT c.*, n.nom as niveau_nom, cy.nom as cycle_nom, u.prenoms as tit_prenom, u.nom as tit_nom FROM classes c JOIN niveaux n ON n.id=c.niveau_id JOIN cycles cy ON cy.id=n.cycle_id LEFT JOIN personnel p ON p.id=c.titulaire_id LEFT JOIN users u ON u.id=p.user_id WHERE c.id=?");
        $stmt->execute([$id]);
        $classe = $stmt->fetch();
        if (!$classe) abort(404);

        // Élèves
        $stmtE = $db->prepare("SELECT e.*, i.statut as statut_insc FROM inscriptions i JOIN eleves e ON e.id=i.eleve_id WHERE i.classe_id=? AND i.statut='inscrit' ORDER BY e.nom, e.prenoms");
        $stmtE->execute([$id]);

        // Matières affectées
        $stmtM = $db->prepare("SELECT ac.*, m.nom as matiere_nom, u.prenoms as prof_prenom, u.nom as prof_nom FROM affectations_cours ac JOIN matieres m ON m.id=ac.matiere_id JOIN personnel p ON p.id=ac.personnel_id JOIN users u ON u.id=p.user_id WHERE ac.classe_id=? ORDER BY m.nom");
        $stmtM->execute([$id]);

        $this->view('classes/show', [
            'pageTitle' => $classe['nom'],
            'classe'    => $classe,
            'eleves'    => $stmtE->fetchAll(),
            'matieres'  => $stmtM->fetchAll(),
        ]);
    }

    public function edit(Request $request): void
    {
        $this->requirePermission('classes.modifier');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("SELECT * FROM classes WHERE id=?");
        $stmt->execute([$id]);
        $classe = $stmt->fetch();
        if (!$classe) abort(404);

        $etabId = Auth::etablissementId();
        $stmtN = $db->prepare("SELECT n.*, cy.nom as cycle_nom FROM niveaux n JOIN cycles cy ON cy.id=n.cycle_id WHERE cy.etablissement_id=? ORDER BY cy.ordre, n.ordre");
        $stmtN->execute([$etabId]);
        $stmtP = $db->prepare("SELECT p.id, u.nom, u.prenoms FROM personnel p JOIN users u ON u.id=p.user_id WHERE p.etablissement_id=? AND p.type='enseignant' ORDER BY u.nom");
        $stmtP->execute([$etabId]);

        $this->view('classes/edit', ['pageTitle' => 'Modifier — ' . $classe['nom'], 'classe' => $classe, 'niveaux' => $stmtN->fetchAll(), 'enseignants' => $stmtP->fetchAll()]);
    }

    public function update(Request $request): void
    {
        $this->requirePermission('classes.modifier');
        $db   = Database::getInstance();
        $id   = (int) $request->param('id');
        $data = $request->all();
        $db->prepare("UPDATE classes SET niveau_id=?, nom=?, effectif_max=?, titulaire_id=? WHERE id=?")
           ->execute([(int)$data['niveau_id'], $data['nom'], $data['effectif_max'] ?? 40, $data['titulaire_id'] ?: null, $id]);
        Session::flash('success', 'Classe mise à jour.');
        redirect('/classes');
    }

    public function destroy(Request $request): void
    {
        $this->requirePermission('classes.supprimer');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $db->prepare("DELETE FROM classes WHERE id=?")->execute([$id]);
        Session::flash('success', 'Classe supprimée.');
        redirect('/classes');
    }

    public function affecterCours(Request $request): void
    {
        $this->requirePermission('classes.modifier');
        $db      = Database::getInstance();
        $classeId = (int) $request->param('id');
        $data     = $request->all();
        $etabId   = Auth::etablissementId();
        $annee    = $this->anneeEnCours($db, $etabId);

        $stmt = $db->prepare("SELECT * FROM classes WHERE id=?");
        $stmt->execute([$classeId]);
        $classe = $stmt->fetch();

        $db->prepare("INSERT INTO affectations_cours (personnel_id, classe_id, matiere_id, annee_scolaire_id, coefficient, heures_hebdo) VALUES (?,?,?,?,?,?)")
           ->execute([(int)$data['personnel_id'], $classeId, (int)$data['matiere_id'], $annee['id'], $data['coefficient'] ?? 1, $data['heures_hebdo'] ?: null]);

        Session::flash('success', 'Cours affecté.');
        redirect("/classes/$classeId");
    }

    private function anneeEnCours(\PDO $db, ?int $etabId): ?array
    {
        if (!$etabId) return null;
        $stmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1");
        $stmt->execute([$etabId]);
        return $stmt->fetch() ?: null;
    }
}
