<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class ProfilController extends Controller
{
    public function show(Request $request): void
    {
        $this->requireAuth();
        $this->view('profil/show', ['pageTitle' => 'Mon profil', 'user' => Auth::user()]);
    }

    public function update(Request $request): void
    {
        $this->requireAuth();
        $db   = Database::getInstance();
        $id   = Auth::id();
        $data = $request->all();

        $photo = Auth::user()['photo'];
        if (!empty($_FILES['photo']['tmp_name'])) {
            $photo = uploadFile($_FILES['photo'], 'photos') ?? $photo;
        }

        $db->prepare("UPDATE users SET nom=?, prenoms=?, email=?, telephone=?, photo=?, updated_at=NOW() WHERE id=?")
           ->execute([$data['nom'], $data['prenoms'], $data['email'] ?? null, $data['telephone'] ?? null, $photo, $id]);

        // Rafraîchir la session
        $stmt = $db->prepare("SELECT u.*, r.nom as role_nom, r.niveau as role_niveau FROM users u JOIN roles r ON r.id=u.role_id WHERE u.id=?");
        $stmt->execute([$id]);
        Session::set('user', $stmt->fetch());

        Session::flash('success', 'Profil mis à jour.');
        redirect('/profil');
    }

    public function changePassword(Request $request): void
    {
        $this->requireAuth();
        $db   = Database::getInstance();
        $id   = Auth::id();
        $data = $request->all();

        $stmt = $db->prepare("SELECT password FROM users WHERE id=?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!password_verify($data['ancien_mdp'], $user['password'])) {
            Session::flash('error', 'Ancien mot de passe incorrect.');
            redirect('/profil');
        }

        if ($data['nouveau_mdp'] !== $data['confirmation_mdp']) {
            Session::flash('error', 'Les nouveaux mots de passe ne correspondent pas.');
            redirect('/profil');
        }

        if (strlen($data['nouveau_mdp']) < 6) {
            Session::flash('error', 'Le nouveau mot de passe doit contenir au moins 6 caractères.');
            redirect('/profil');
        }

        $hash = Auth::hashPassword($data['nouveau_mdp']);
        $db->prepare("UPDATE users SET password=?, updated_at=NOW() WHERE id=?")->execute([$hash, $id]);

        Session::flash('success', 'Mot de passe modifié avec succès.');
        redirect('/profil');
    }
}
