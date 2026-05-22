<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Core\View;

class CommunicationController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('communication.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();

        $stmt = $db->prepare("
            SELECT a.*, u.nom as auteur_nom, u.prenoms as auteur_prenom, c.nom as classe_cible
            FROM annonces a
            JOIN users u ON u.id=a.auteur_id
            LEFT JOIN classes c ON c.id=a.classe_id
            WHERE a.etablissement_id=? AND (a.expire_le IS NULL OR a.expire_le > NOW())
            ORDER BY a.priorite DESC, a.created_at DESC
        ");
        $stmt->execute([$etabId]);

        $this->view('communication/index', [
            'pageTitle' => 'Annonces',
            'annonces'  => $stmt->fetchAll(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->requirePermission('communication.creer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $annee  = $this->anneeEnCours($db, $etabId);
        $stmtC  = $db->prepare("SELECT * FROM classes WHERE etablissement_id=? AND annee_scolaire_id=? ORDER BY nom");
        $stmtC->execute([$etabId, $annee['id'] ?? 0]);

        $this->view('communication/create', [
            'pageTitle' => 'Nouvelle annonce',
            'classes'   => $stmtC->fetchAll(),
        ]);
    }

    public function store(Request $request): void
    {
        $this->requirePermission('communication.creer');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $data   = $request->all();

        $db->prepare("INSERT INTO annonces (etablissement_id, auteur_id, titre, contenu, cible, classe_id, priorite, publie_le, expire_le) VALUES (?,?,?,?,?,?,?,NOW(),?)")
           ->execute([$etabId, Auth::id(), $data['titre'], $data['contenu'], $data['cible'] ?? 'tous', $data['classe_id'] ?: null, $data['priorite'] ?? 'normale', $data['expire_le'] ?: null]);

        Session::flash('success', 'Annonce publiée.');
        redirect('/annonces');
    }

    public function show(Request $request): void
    {
        $this->requirePermission('communication.voir');
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $stmt = $db->prepare("SELECT a.*, u.nom as auteur_nom, u.prenoms as auteur_prenom FROM annonces a JOIN users u ON u.id=a.auteur_id WHERE a.id=?");
        $stmt->execute([$id]);
        $annonce = $stmt->fetch();
        if (!$annonce) abort(404);
        $this->view('communication/show', ['pageTitle' => $annonce['titre'], 'annonce' => $annonce]);
    }

    public function messages(Request $request): void
    {
        $this->requireAuth();
        $db     = Database::getInstance();
        $userId = Auth::id();
        $tab    = $request->get('tab', 'recus');

        // Messages reçus
        $stmtR = $db->prepare("
            SELECT m.*, u.nom as exp_nom, u.prenoms as exp_prenom
            FROM messages m
            JOIN users u ON u.id=m.expediteur_id
            WHERE m.destinataire_id=? AND m.parent_id IS NULL
            ORDER BY m.created_at DESC
            LIMIT 50
        ");
        $stmtR->execute([$userId]);

        // Messages envoyés
        $stmtS = $db->prepare("
            SELECT m.*, u.nom as dest_nom, u.prenoms as dest_prenom
            FROM messages m
            JOIN users u ON u.id=m.destinataire_id
            WHERE m.expediteur_id=? AND m.parent_id IS NULL
            ORDER BY m.created_at DESC
            LIMIT 50
        ");
        $stmtS->execute([$userId]);

        // Non lus
        $stmtNl = $db->prepare("SELECT COUNT(*) FROM messages WHERE destinataire_id=? AND lu=0");
        $stmtNl->execute([$userId]);
        $nonLus = (int) $stmtNl->fetchColumn();

        $this->view('communication/messages', [
            'pageTitle' => 'Messagerie',
            'messages'  => $stmtR->fetchAll(),
            'envoyes'   => $stmtS->fetchAll(),
            'nonLus'    => $nonLus,
            'tab'       => $tab,
        ]);
    }

    public function showMessage(Request $request): void
    {
        $this->requireAuth();
        $db     = Database::getInstance();
        $id     = (int) $request->param('id');
        $userId = Auth::id();

        $stmt = $db->prepare("
            SELECT m.*,
                   exp.nom as exp_nom, exp.prenoms as exp_prenom,
                   dest.nom as dest_nom, dest.prenoms as dest_prenom
            FROM messages m
            JOIN users exp  ON exp.id=m.expediteur_id
            JOIN users dest ON dest.id=m.destinataire_id
            WHERE m.id=? AND (m.destinataire_id=? OR m.expediteur_id=?)
        ");
        $stmt->execute([$id, $userId, $userId]);
        $message = $stmt->fetch();
        if (!$message) abort(404, 'Message introuvable.');

        // Marquer comme lu si destinataire
        if ($message['destinataire_id'] == $userId && !$message['lu']) {
            $db->prepare("UPDATE messages SET lu=1, lu_le=NOW() WHERE id=?")->execute([$id]);
        }

        // Réponses (fils)
        $stmtRep = $db->prepare("
            SELECT r.*, u.nom as exp_nom, u.prenoms as exp_prenom
            FROM messages r
            JOIN users u ON u.id=r.expediteur_id
            WHERE r.parent_id=?
            ORDER BY r.created_at ASC
        ");
        $stmtRep->execute([$id]);

        $this->view('communication/message_show', [
            'pageTitle' => 'Message — ' . ($message['sujet'] ?: '(Sans sujet)'),
            'message'   => $message,
            'reponses'  => $stmtRep->fetchAll(),
        ]);
    }

    public function sendMessage(Request $request): void
    {
        $this->requireAuth();
        $db   = Database::getInstance();
        $data = $request->all();

        $db->prepare("INSERT INTO messages (expediteur_id, destinataire_id, sujet, contenu, parent_id) VALUES (?,?,?,?,?)")
           ->execute([Auth::id(), (int) $data['destinataire_id'], $data['sujet'] ?? null, $data['contenu'], $data['parent_id'] ?: null]);

        $destinataire = $db->prepare("SELECT prenoms FROM users WHERE id=?")->execute([(int)$data['destinataire_id']]);

        // Notification
        $db->prepare("INSERT INTO notifications (user_id, type, titre, contenu, lien) VALUES (?,?,?,?,?)")
           ->execute([(int) $data['destinataire_id'], 'message', 'Nouveau message', 'Vous avez reçu un message de ' . (Auth::user()['prenoms'] ?? ''), '/messages']);

        Session::flash('success', 'Message envoyé.');
        redirect('/messages');
    }

    public function notifications(Request $request): void
    {
        $this->requireAuth();
        $db     = Database::getInstance();
        $userId = Auth::id();

        if ($request->isAjax() || $request->get('json')) {
            $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id=? AND lu=0 ORDER BY created_at DESC LIMIT 10");
            $stmt->execute([$userId]);
            $items = $stmt->fetchAll();
            $count = count($items);
            View::json(['count' => $count, 'items' => $items]);
        }

        $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC LIMIT 50");
        $stmt->execute([$userId]);
        $this->view('communication/notifications', ['pageTitle' => 'Notifications', 'notifications' => $stmt->fetchAll()]);
    }

    public function markRead(Request $request): void
    {
        $this->requireAuth();
        $db = Database::getInstance();
        $id = (int) $request->param('id');
        $db->prepare("UPDATE notifications SET lu=1 WHERE id=? AND user_id=?")->execute([$id, Auth::id()]);
        View::json(['ok' => true]);
    }

    private function anneeEnCours(\PDO $db, ?int $etabId): ?array
    {
        if (!$etabId) return null;
        $stmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1");
        $stmt->execute([$etabId]);
        return $stmt->fetch() ?: null;
    }
}
