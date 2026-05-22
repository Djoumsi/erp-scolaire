<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class EtablissementController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('etablissements.voir');
        $db   = Database::getInstance();
        $stmt = $db->query("
            SELECT e.*,
                   COUNT(DISTINCT u.id) as nb_users,
                   (SELECT COUNT(*) FROM inscriptions i JOIN classes c ON c.id=i.classe_id JOIN annees_scolaires a ON a.id=i.annee_scolaire_id WHERE c.etablissement_id=e.id AND a.en_cours=1) as nb_eleves
            FROM etablissements e
            LEFT JOIN users u ON u.etablissement_id=e.id AND u.deleted_at IS NULL
            GROUP BY e.id ORDER BY e.nom
        ");
        $this->view('etablissement/index', [
            'pageTitle'      => 'Établissements',
            'etablissements' => $stmt->fetchAll(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->requirePermission('etablissements.creer');
        $this->view('etablissement/create', ['pageTitle' => 'Nouvel établissement']);
    }

    public function store(Request $request): void
    {
        $this->requirePermission('etablissements.creer');
        $db   = Database::getInstance();
        $data = $request->all();

        $logo = null;
        if (!empty($_FILES['logo']['tmp_name'])) {
            $logo = uploadFile($_FILES['logo'], 'logos', ['jpg','jpeg','png','svg','webp']);
        }

        $db->prepare("INSERT INTO etablissements (nom, type, logo, adresse, telephone, email, site_web, code_etablissement, devise, pays) VALUES (?,?,?,?,?,?,?,?,?,?)")
           ->execute([$data['nom'], $data['type'], $logo, $data['adresse'] ?? null, $data['telephone'] ?? null, $data['email'] ?? null, $data['site_web'] ?? null, $data['code_etablissement'] ?? null, $data['devise'] ?? 'XOF', $data['pays'] ?? 'Côte d\'Ivoire']);

        $etabId = (int) $db->lastInsertId();

        // Créer un admin pour cet établissement
        if (!empty($data['admin_login']) && !empty($data['admin_password'])) {
            $roleStmt = $db->query("SELECT id FROM roles WHERE nom='admin' LIMIT 1");
            $roleId   = (int) $roleStmt->fetchColumn();
            $hash     = Auth::hashPassword($data['admin_password']);
            $db->prepare("INSERT INTO users (etablissement_id, role_id, nom, prenoms, email, login, password) VALUES (?,?,?,?,?,?,?)")
               ->execute([$etabId, $roleId, $data['admin_nom'] ?? 'Admin', $data['admin_prenom'] ?? '', $data['admin_email'] ?? null, $data['admin_login'], $hash]);
        }

        Session::flash('success', "Établissement \"{$data['nom']}\" créé avec succès.");
        redirect('/etablissements');
    }

    public function show(Request $request): void
    {
        $this->requirePermission('etablissements.voir');
        $db   = Database::getInstance();
        $id   = (int) $request->param('id');
        $stmt = $db->prepare("SELECT * FROM etablissements WHERE id=?");
        $stmt->execute([$id]);
        $etab = $stmt->fetch();
        if (!$etab) abort(404);

        $stmtU = $db->prepare("SELECT u.*, r.label as role_label FROM users u JOIN roles r ON r.id=u.role_id WHERE u.etablissement_id=? AND u.deleted_at IS NULL ORDER BY r.niveau DESC, u.nom");
        $stmtU->execute([$id]);
        $users = $stmtU->fetchAll();

        $stmtA = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? ORDER BY date_debut DESC");
        $stmtA->execute([$id]);
        $annees = $stmtA->fetchAll();

        $this->view('etablissement/show', [
            'pageTitle' => $etab['nom'],
            'etab'      => $etab,
            'users'     => $users,
            'annees'    => $annees,
        ]);
    }

    public function edit(Request $request): void
    {
        $this->requirePermission('etablissements.modifier');
        $db   = Database::getInstance();
        $id   = (int) $request->param('id');
        $stmt = $db->prepare("SELECT * FROM etablissements WHERE id=?");
        $stmt->execute([$id]);
        $etab = $stmt->fetch();
        if (!$etab) abort(404);
        $this->view('etablissement/edit', ['pageTitle' => 'Modifier — ' . $etab['nom'], 'etab' => $etab]);
    }

    public function update(Request $request): void
    {
        $this->requirePermission('etablissements.modifier');
        $db   = Database::getInstance();
        $id   = (int) $request->param('id');
        $data = $request->all();

        $stmt = $db->prepare("SELECT logo FROM etablissements WHERE id=?");
        $stmt->execute([$id]);
        $old = $stmt->fetch();
        $logo = $old['logo'];
        if (!empty($_FILES['logo']['tmp_name'])) {
            $logo = uploadFile($_FILES['logo'], 'logos', ['jpg','jpeg','png','svg','webp']) ?? $logo;
        }

        $db->prepare("UPDATE etablissements SET nom=?, type=?, logo=?, adresse=?, telephone=?, email=?, site_web=?, code_etablissement=?, devise=?, pays=?, updated_at=NOW() WHERE id=?")
           ->execute([$data['nom'], $data['type'], $logo, $data['adresse'] ?? null, $data['telephone'] ?? null, $data['email'] ?? null, $data['site_web'] ?? null, $data['code_etablissement'] ?? null, $data['devise'] ?? 'XOF', $data['pays'] ?? 'Côte d\'Ivoire', $id]);

        Session::flash('success', 'Établissement mis à jour.');
        redirect('/etablissements');
    }

    public function destroy(Request $request): void
    {
        $this->requirePermission('etablissements.supprimer');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $db->prepare("UPDATE etablissements SET actif=0 WHERE id=?")->execute([$id]);
        Session::flash('success', 'Établissement désactivé.');
        redirect('/etablissements');
    }
}
