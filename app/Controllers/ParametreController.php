<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class ParametreController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('parametres.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();

        // Années scolaires
        $stmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? ORDER BY date_debut DESC");
        $stmt->execute([$etabId]);
        $annees = $stmt->fetchAll();

        // Cycles et niveaux
        $stmt = $db->prepare("SELECT cy.*, GROUP_CONCAT(n.nom ORDER BY n.ordre SEPARATOR ', ') as niveaux FROM cycles cy LEFT JOIN niveaux n ON n.cycle_id=cy.id WHERE cy.etablissement_id=? GROUP BY cy.id ORDER BY cy.ordre");
        $stmt->execute([$etabId]);
        $cycles = $stmt->fetchAll();

        // Matières
        $stmt = $db->prepare("SELECT * FROM matieres WHERE etablissement_id=? ORDER BY nom");
        $stmt->execute([$etabId]);
        $matieres = $stmt->fetchAll();

        // Salles
        $stmt = $db->prepare("SELECT * FROM salles WHERE etablissement_id=? ORDER BY nom");
        $stmt->execute([$etabId]);
        $salles = $stmt->fetchAll();

        // Créneaux horaires
        $stmt = $db->prepare("SELECT * FROM creneaux_horaires WHERE etablissement_id=? ORDER BY ordre");
        $stmt->execute([$etabId]);
        $creneaux = $stmt->fetchAll();

        // Types de frais
        $stmt = $db->prepare("SELECT * FROM types_frais WHERE etablissement_id=? ORDER BY nom");
        $stmt->execute([$etabId]);
        $typesFrais = $stmt->fetchAll();

        // Types évaluation
        $stmt = $db->prepare("SELECT * FROM types_evaluation WHERE etablissement_id=? ORDER BY nom");
        $stmt->execute([$etabId]);
        $typesEval = $stmt->fetchAll();

        // Périodes de l'année en cours
        $anneeEnCours = null;
        foreach ($annees as $a) { if ($a['en_cours']) { $anneeEnCours = $a; break; } }
        $periodes = [];
        if ($anneeEnCours) {
            $stmt = $db->prepare("SELECT * FROM periodes WHERE annee_scolaire_id=? ORDER BY ordre");
            $stmt->execute([$anneeEnCours['id']]);
            $periodes = $stmt->fetchAll();
        }

        // Infos établissement
        $stmt = $db->prepare("SELECT * FROM etablissements WHERE id=?");
        $stmt->execute([$etabId]);
        $etablissement = $stmt->fetch();

        $this->view('parametres/index', [
            'pageTitle'    => 'Paramètres',
            'annees'       => $annees,
            'cycles'       => $cycles,
            'matieres'     => $matieres,
            'salles'       => $salles,
            'creneaux'     => $creneaux,
            'typesFrais'   => $typesFrais,
            'typesEval'    => $typesEval,
            'periodes'     => $periodes,
            'anneeEnCours' => $anneeEnCours,
            'etablissement'=> $etablissement,
        ]);
    }

    public function storeAnnee(Request $request): void
    {
        $this->requirePermission('parametres.modifier');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $data   = $request->all();

        $db->prepare("INSERT INTO annees_scolaires (etablissement_id, libelle, date_debut, date_fin, en_cours) VALUES (?,?,?,?,0)")
           ->execute([$etabId, $data['libelle'], $data['date_debut'], $data['date_fin']]);

        // Créer les catégories comptables par défaut si nouvelle année
        $this->initCategoriesComptables($db, $etabId);

        Session::flash('success', "Année scolaire {$data['libelle']} créée.");
        redirect('/parametres#annees');
    }

    public function activerAnnee(Request $request): void
    {
        $this->requirePermission('parametres.modifier');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $id     = (int) $request->param('id');

        $db->prepare("UPDATE annees_scolaires SET en_cours=0 WHERE etablissement_id=?")->execute([$etabId]);
        $db->prepare("UPDATE annees_scolaires SET en_cours=1 WHERE id=? AND etablissement_id=?")->execute([$id, $etabId]);

        Session::flash('success', 'Année scolaire activée.');
        redirect('/parametres#annees');
    }

    public function storeCycle(Request $request): void
    {
        $this->requirePermission('parametres.modifier');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $data   = $request->all();

        $db->prepare("INSERT INTO cycles (etablissement_id, nom, ordre) VALUES (?,?,?)")
           ->execute([$etabId, $data['nom'], $data['ordre'] ?? 1]);

        Session::flash('success', 'Cycle créé.');
        redirect('/parametres#cycles');
    }

    public function destroyCycle(Request $request): void
    {
        $this->requirePermission('parametres.modifier');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $db->prepare("DELETE FROM cycles WHERE id=?")->execute([$id]);
        Session::flash('success', 'Cycle supprimé.');
        redirect('/parametres#cycles');
    }

    public function storeNiveau(Request $request): void
    {
        $this->requirePermission('parametres.modifier');
        $db   = Database::getInstance();
        $data = $request->all();
        $db->prepare("INSERT INTO niveaux (cycle_id, nom, abreviation, ordre) VALUES (?,?,?,?)")
           ->execute([$data['cycle_id'], $data['nom'], $data['abreviation'] ?? null, $data['ordre'] ?? 1]);
        Session::flash('success', 'Niveau créé.');
        redirect('/parametres#cycles');
    }

    public function storeMatiere(Request $request): void
    {
        $this->requirePermission('parametres.modifier');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $data   = $request->all();
        $db->prepare("INSERT INTO matieres (etablissement_id, nom, code, type) VALUES (?,?,?,?)")
           ->execute([$etabId, $data['nom'], $data['code'] ?? null, $data['type'] ?? 'principale']);
        Session::flash('success', 'Matière créée.');
        redirect('/parametres#matieres');
    }

    public function storeSalle(Request $request): void
    {
        $this->requirePermission('parametres.modifier');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $data   = $request->all();
        $db->prepare("INSERT INTO salles (etablissement_id, nom, capacite, type) VALUES (?,?,?,?)")
           ->execute([$etabId, $data['nom'], $data['capacite'] ?? null, $data['type'] ?? 'cours']);
        Session::flash('success', 'Salle créée.');
        redirect('/parametres#salles');
    }

    public function storeCreneau(Request $request): void
    {
        $this->requirePermission('parametres.modifier');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $data   = $request->all();
        $db->prepare("INSERT INTO creneaux_horaires (etablissement_id, heure_debut, heure_fin, nom, type, ordre) VALUES (?,?,?,?,?,?)")
           ->execute([$etabId, $data['heure_debut'], $data['heure_fin'], $data['nom'] ?? null, $data['type'] ?? 'cours', $data['ordre'] ?? 1]);
        Session::flash('success', 'Créneau créé.');
        redirect('/parametres#creneaux');
    }

    public function storePeriode(Request $request): void
    {
        $this->requirePermission('parametres.modifier');
        $db   = Database::getInstance();
        $data = $request->all();
        $db->prepare("INSERT INTO periodes (annee_scolaire_id, nom, type, date_debut, date_fin, ordre) VALUES (?,?,?,?,?,?)")
           ->execute([$data['annee_scolaire_id'], $data['nom'], $data['type'], $data['date_debut'], $data['date_fin'], $data['ordre'] ?? 1]);
        Session::flash('success', 'Période créée.');
        redirect('/parametres#periodes');
    }

    public function storeTypeFrais(Request $request): void
    {
        $this->requirePermission('parametres.modifier');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $data   = $request->all();
        $db->prepare("INSERT INTO types_frais (etablissement_id, nom, montant_defaut, obligatoire) VALUES (?,?,?,?)")
           ->execute([$etabId, $data['nom'], $data['montant_defaut'] ?? 0, isset($data['obligatoire']) ? 1 : 0]);
        Session::flash('success', 'Type de frais créé.');
        redirect('/parametres#frais');
    }

    public function storeTypeEval(Request $request): void
    {
        $this->requirePermission('parametres.modifier');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $data   = $request->all();
        $db->prepare("INSERT INTO types_evaluation (etablissement_id, nom, coefficient, sur) VALUES (?,?,?,?)")
           ->execute([$etabId, $data['nom'], $data['coefficient'] ?? 1, $data['sur'] ?? 20]);
        Session::flash('success', 'Type d\'évaluation créé.');
        redirect('/parametres#evaluations');
    }

    private function initCategoriesComptables(\PDO $db, int $etabId): void
    {
        $defaults = [
            ['Scolarité', 'recette'], ['Inscription', 'recette'], ['Cantine', 'recette'],
            ['Autre recette', 'recette'], ['Salaires', 'depense'], ['Fournitures', 'depense'],
            ['Eau et Électricité', 'depense'], ['Entretien', 'depense'], ['Autre dépense', 'depense'],
        ];
        foreach ($defaults as [$nom, $type]) {
            $db->prepare("INSERT IGNORE INTO categories_comptables (etablissement_id, nom, type) VALUES (?,?,?)")
               ->execute([$etabId, $nom, $type]);
        }
    }
}
