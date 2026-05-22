<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class PersonnelController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('personnel.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $search = trim($request->get('q', ''));
        $type   = $request->get('type', '');
        $perPage = 20;
        $page    = max(1, (int) $request->get('page', 1));
        $offset  = ($page - 1) * $perPage;

        $sqlBase = "FROM personnel p JOIN users u ON u.id=p.user_id JOIN roles r ON r.id=u.role_id WHERE p.etablissement_id=? AND p.deleted_at IS NULL";
        $params  = [$etabId];

        if ($search) {
            $sqlBase .= " AND (u.nom LIKE ? OR u.prenoms LIKE ? OR p.matricule LIKE ?)";
            $like = "%$search%";
            $params[] = $like; $params[] = $like; $params[] = $like;
        }
        if ($type) { $sqlBase .= " AND p.type=?"; $params[] = $type; }

        // Total
        $stmtCount = $db->prepare("SELECT COUNT(*) $sqlBase");
        $stmtCount->execute($params);
        $total = (int) $stmtCount->fetchColumn();

        // Données paginées
        $stmt = $db->prepare("SELECT p.*, u.nom, u.prenoms, u.email, u.telephone, u.photo, r.label as role_label $sqlBase ORDER BY u.nom, u.prenoms LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);

        $this->view('personnel/index', [
            'pageTitle'  => 'Personnel',
            'personnel'  => $stmt->fetchAll(),
            'search'     => $search,
            'type'       => $type,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => (int) ceil($total / $perPage),
        ]);
    }

    public function create(Request $request): void
    {
        $this->requirePermission('personnel.creer');
        $this->view('personnel/create', ['pageTitle' => 'Ajouter un membre du personnel']);
    }

    public function store(Request $request): void
    {
        $this->requirePermission('personnel.creer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $data   = $request->all();

        // Créer le compte utilisateur
        $roleMap  = ['enseignant' => 'enseignant', 'administratif' => 'admin', 'direction' => 'directeur', 'surveillant' => 'enseignant', 'autre' => 'enseignant'];
        $roleNom  = $roleMap[$data['type']] ?? 'enseignant';
        $roleStmt = $db->prepare("SELECT id FROM roles WHERE nom=?");
        $roleStmt->execute([$roleNom]);
        $roleId  = (int) $roleStmt->fetchColumn();

        $login = strtolower(substr($data['prenoms'], 0, 1) . $data['nom'] . rand(10, 99));
        $login = preg_replace('/[^a-z0-9]/', '', $login);
        $hash  = Auth::hashPassword($data['password'] ?: 'Passe@2025');

        $photo = null;
        if (!empty($_FILES['photo']['tmp_name'])) {
            $photo = uploadFile($_FILES['photo'], 'photos', ['jpg','jpeg','png','webp']);
        }

        $db->prepare("INSERT INTO users (etablissement_id, role_id, nom, prenoms, email, telephone, login, password, photo) VALUES (?,?,?,?,?,?,?,?,?)")
           ->execute([$etabId, $roleId, $data['nom'], $data['prenoms'], $data['email'] ?? null, $data['telephone'] ?? null, $login, $hash, $photo]);
        $userId = (int) $db->lastInsertId();

        // Générer matricule
        $cntStmt = $db->prepare("SELECT COUNT(*) FROM personnel WHERE etablissement_id=?");
        $cntStmt->execute([$etabId]);
        $cnt      = (int) $cntStmt->fetchColumn() + 1;
        $etablStmt = $db->prepare("SELECT code_etablissement FROM etablissements WHERE id=?");
        $etablStmt->execute([$etabId]);
        $code     = $etablStmt->fetchColumn() ?: 'ETB';
        $matricule = strtoupper($code) . 'P' . str_pad($cnt, 4, '0', STR_PAD_LEFT);

        $db->prepare("INSERT INTO personnel (user_id, etablissement_id, matricule, type, specialite, diplome, date_embauche, statut_contrat) VALUES (?,?,?,?,?,?,?,?)")
           ->execute([$userId, $etabId, $matricule, $data['type'], $data['specialite'] ?? null, $data['diplome'] ?? null, $data['date_embauche'] ?: null, $data['statut_contrat'] ?? 'permanent']);

        Session::flash('success', "Personnel ajouté. Login : $login / Mot de passe : " . ($data['password'] ?: 'Passe@2025'));
        redirect('/personnel');
    }

    public function show(Request $request): void
    {
        $this->requirePermission('personnel.voir');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("SELECT p.*, u.nom, u.prenoms, u.email, u.telephone, u.photo, u.login FROM personnel p JOIN users u ON u.id=p.user_id WHERE p.id=?");
        $stmt->execute([$id]);
        $personne = $stmt->fetch();
        if (!$personne) abort(404);

        // Cours assignés
        $stmtC = $db->prepare("SELECT ac.*, c.nom as classe_nom, m.nom as matiere_nom, a.libelle as annee FROM affectations_cours ac JOIN classes c ON c.id=ac.classe_id JOIN matieres m ON m.id=ac.matiere_id JOIN annees_scolaires a ON a.id=ac.annee_scolaire_id WHERE ac.personnel_id=? ORDER BY a.date_debut DESC, c.nom");
        $stmtC->execute([$id]);

        $this->view('personnel/show', [
            'pageTitle' => $personne['prenoms'] . ' ' . $personne['nom'],
            'personne'  => $personne,
            'cours'     => $stmtC->fetchAll(),
        ]);
    }

    public function edit(Request $request): void
    {
        $this->requirePermission('personnel.modifier');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("SELECT p.*, u.nom, u.prenoms, u.email, u.telephone, u.photo FROM personnel p JOIN users u ON u.id=p.user_id WHERE p.id=?");
        $stmt->execute([$id]);
        $personne = $stmt->fetch();
        if (!$personne) abort(404);
        $this->view('personnel/edit', ['pageTitle' => 'Modifier — ' . $personne['prenoms'] . ' ' . $personne['nom'], 'personne' => $personne]);
    }

    public function update(Request $request): void
    {
        $this->requirePermission('personnel.modifier');
        $db   = Database::getInstance();
        $id   = (int) $request->param('id');
        $data = $request->all();

        $stmt = $db->prepare("SELECT user_id, photo FROM personnel p JOIN users u ON u.id=p.user_id WHERE p.id=?");
        $stmt->execute([$id]);
        $old = $stmt->fetch();

        $photo = $old['photo'];
        if (!empty($_FILES['photo']['tmp_name'])) {
            $photo = uploadFile($_FILES['photo'], 'photos') ?? $photo;
        }

        $db->prepare("UPDATE users SET nom=?, prenoms=?, email=?, telephone=?, photo=?, updated_at=NOW() WHERE id=?")
           ->execute([$data['nom'], $data['prenoms'], $data['email'] ?? null, $data['telephone'] ?? null, $photo, $old['user_id']]);
        $db->prepare("UPDATE personnel SET type=?, specialite=?, diplome=?, date_embauche=?, statut_contrat=?, updated_at=NOW() WHERE id=?")
           ->execute([$data['type'], $data['specialite'] ?? null, $data['diplome'] ?? null, $data['date_embauche'] ?: null, $data['statut_contrat'] ?? 'permanent', $id]);

        Session::flash('success', 'Dossier mis à jour.');
        redirect("/personnel/$id");
    }

    public function destroy(Request $request): void
    {
        $this->requirePermission('personnel.supprimer');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $db->prepare("UPDATE personnel SET deleted_at=NOW() WHERE id=?")->execute([$id]);
        Session::flash('success', 'Membre du personnel supprimé.');
        redirect('/personnel');
    }
}
