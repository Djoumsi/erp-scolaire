<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class BibliothequeController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('bibliotheque.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();

        $stmt = $db->prepare("SELECT * FROM livres WHERE etablissement_id=? ORDER BY titre");
        $stmt->execute([$etabId]);
        $livres = $stmt->fetchAll();

        $stmtE = $db->prepare("SELECT em.*, l.titre, u.nom, u.prenoms FROM emprunts em JOIN livres l ON l.id=em.livre_id JOIN users u ON u.id=em.user_id WHERE l.etablissement_id=? AND em.statut='en_cours' ORDER BY em.date_retour_prevu");
        $stmtE->execute([$etabId]);

        $this->view('bibliotheque/index', ['pageTitle' => 'Bibliothèque', 'livres' => $livres, 'emprunts_encours' => $stmtE->fetchAll()]);
    }

    public function storeLivre(Request $request): void
    {
        $this->requirePermission('bibliotheque.gerer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $data   = $request->all();
        $db->prepare("INSERT INTO livres (etablissement_id, isbn, titre, auteur, editeur, annee_edition, categorie, localisation, exemplaires_total, exemplaires_dispo) VALUES (?,?,?,?,?,?,?,?,?,?)")
           ->execute([$etabId, $data['isbn'] ?: null, $data['titre'], $data['auteur'] ?? null, $data['editeur'] ?? null, $data['annee_edition'] ?: null, $data['categorie'] ?? null, $data['localisation'] ?? null, $data['exemplaires'] ?? 1, $data['exemplaires'] ?? 1]);
        Session::flash('success', 'Livre ajouté.');
        redirect('/bibliotheque');
    }

    public function emprunter(Request $request): void
    {
        $this->requirePermission('bibliotheque.gerer');
        $db   = Database::getInstance();
        $data = $request->all();

        $stmt = $db->prepare("SELECT exemplaires_dispo FROM livres WHERE id=?");
        $stmt->execute([(int)$data['livre_id']]);
        $livre = $stmt->fetch();
        if (!$livre || $livre['exemplaires_dispo'] < 1) {
            Session::flash('error', 'Aucun exemplaire disponible.');
            redirect('/bibliotheque');
        }

        $db->prepare("INSERT INTO emprunts (livre_id, user_id, date_emprunt, date_retour_prevu) VALUES (?,?,CURDATE(),?)")
           ->execute([(int)$data['livre_id'], (int)$data['user_id'], $data['date_retour_prevu']]);
        $db->prepare("UPDATE livres SET exemplaires_dispo = exemplaires_dispo - 1 WHERE id=?")->execute([(int)$data['livre_id']]);

        Session::flash('success', 'Emprunt enregistré.');
        redirect('/bibliotheque');
    }

    public function retour(Request $request): void
    {
        $this->requirePermission('bibliotheque.gerer');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("SELECT livre_id FROM emprunts WHERE id=?");
        $stmt->execute([$id]);
        $emprunt = $stmt->fetch();

        $db->prepare("UPDATE emprunts SET statut='rendu', date_retour_effectif=CURDATE() WHERE id=?")->execute([$id]);
        $db->prepare("UPDATE livres SET exemplaires_dispo = exemplaires_dispo + 1 WHERE id=?")->execute([$emprunt['livre_id']]);

        Session::flash('success', 'Retour enregistré.');
        redirect('/bibliotheque');
    }
}
