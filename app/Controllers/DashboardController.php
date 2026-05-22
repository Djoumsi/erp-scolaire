<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $role   = Auth::role();

        // Données communes
        $data = ['pageTitle' => 'Tableau de bord', 'role' => $role];

        if ($role === 'super_admin') {
            $data['total_etablissements'] = (int) $db->query("SELECT COUNT(*) FROM etablissements WHERE actif=1")->fetchColumn();
            $data['total_users']          = (int) $db->query("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL")->fetchColumn();
            $data['etablissements']       = $db->query("SELECT e.*, COUNT(i.id) as nb_inscrits FROM etablissements e LEFT JOIN annees_scolaires a ON a.etablissement_id=e.id AND a.en_cours=1 LEFT JOIN classes c ON c.annee_scolaire_id=a.id LEFT JOIN inscriptions i ON i.classe_id=c.id GROUP BY e.id ORDER BY e.nom LIMIT 10")->fetchAll();
            $this->view('dashboard/super_admin', $data);
            return;
        }

        // Année scolaire en cours
        $annee = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id = ? AND en_cours = 1 LIMIT 1");
        $annee->execute([$etabId]);
        $annee = $annee->fetch();
        $data['annee'] = $annee;

        if (!$annee) {
            $this->view('dashboard/no_annee', $data);
            return;
        }

        $anneeId = $annee['id'];

        // Stats globales
        $stmt = $db->prepare("SELECT COUNT(*) FROM inscriptions i JOIN classes c ON c.id=i.classe_id WHERE c.etablissement_id=? AND i.annee_scolaire_id=? AND i.statut='inscrit'");
        $stmt->execute([$etabId, $anneeId]);
        $data['total_eleves'] = (int) $stmt->fetchColumn();

        $stmt = $db->prepare("SELECT COUNT(*) FROM personnel WHERE etablissement_id=? AND deleted_at IS NULL");
        $stmt->execute([$etabId]);
        $data['total_personnel'] = (int) $stmt->fetchColumn();

        $stmt = $db->prepare("SELECT COUNT(*) FROM classes WHERE etablissement_id=? AND annee_scolaire_id=?");
        $stmt->execute([$etabId, $anneeId]);
        $data['total_classes'] = (int) $stmt->fetchColumn();

        // Paiements du mois
        $stmt = $db->prepare("
            SELECT COALESCE(SUM(p.montant),0) as total
            FROM paiements p
            JOIN dossiers_paiement dp ON dp.id=p.dossier_paiement_id
            JOIN inscriptions i ON i.id=dp.inscription_id
            JOIN classes c ON c.id=i.classe_id
            WHERE c.etablissement_id=? AND MONTH(p.date_paiement)=MONTH(CURDATE()) AND YEAR(p.date_paiement)=YEAR(CURDATE()) AND p.annule=0
        ");
        $stmt->execute([$etabId]);
        $data['paiements_mois'] = (float) $stmt->fetchColumn();

        // Absences du jour
        $stmt = $db->prepare("
            SELECT COUNT(*) FROM presences pr
            JOIN seances s ON s.id=pr.seance_id
            JOIN affectations_cours ac ON ac.id=s.affectation_id
            JOIN classes c ON c.id=ac.classe_id
            WHERE c.etablissement_id=? AND s.date_seance=CURDATE() AND pr.statut='absent'
        ");
        $stmt->execute([$etabId]);
        $data['absences_jour'] = (int) $stmt->fetchColumn();

        // Dernières inscriptions
        $stmt = $db->prepare("
            SELECT e.nom, e.prenoms, e.matricule, c.nom as classe, i.date_inscription
            FROM inscriptions i
            JOIN eleves e ON e.id=i.eleve_id
            JOIN classes c ON c.id=i.classe_id
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=?
            ORDER BY i.created_at DESC LIMIT 5
        ");
        $stmt->execute([$etabId, $anneeId]);
        $data['dernieres_inscriptions'] = $stmt->fetchAll();

        // Paiements en retard
        $stmt = $db->prepare("
            SELECT COUNT(*) FROM tranches_paiement tp
            JOIN dossiers_paiement dp ON dp.id=tp.dossier_paiement_id
            JOIN inscriptions i ON i.id=dp.inscription_id
            JOIN classes c ON c.id=i.classe_id
            WHERE c.etablissement_id=? AND tp.date_echeance < CURDATE() AND tp.statut='en_attente'
        ");
        $stmt->execute([$etabId]);
        $data['retards_paiement'] = (int) $stmt->fetchColumn();

        // Taux de paiement global (% dossiers soldés)
        $stmt = $db->prepare("
            SELECT
                COUNT(*) as total,
                SUM(CASE WHEN dp.statut='solde' THEN 1 ELSE 0 END) as soldes,
                ROUND(COALESCE(SUM(dp.montant_paye),0) / NULLIF(SUM(dp.montant_total),0) * 100, 1) as taux
            FROM dossiers_paiement dp
            JOIN inscriptions i ON i.id=dp.inscription_id
            JOIN classes c ON c.id=i.classe_id
            WHERE c.etablissement_id=? AND i.annee_scolaire_id=?
        ");
        $stmt->execute([$etabId, $anneeId]);
        $paiRow = $stmt->fetch();
        $data['taux_paiement']   = (float) ($paiRow['taux'] ?? 0);
        $data['dossiers_soldes'] = (int)   ($paiRow['soldes'] ?? 0);
        $data['dossiers_total']  = (int)   ($paiRow['total'] ?? 0);

        // Messages non lus (pour le badge dans le menu)
        $stmt = $db->prepare("SELECT COUNT(*) FROM messages WHERE destinataire_id=? AND lu=0");
        $stmt->execute([Auth::id()]);
        $data['messages_non_lus'] = (int) $stmt->fetchColumn();

        // Annonces récentes
        $stmt = $db->prepare("
            SELECT * FROM annonces
            WHERE etablissement_id=? AND (expire_le IS NULL OR expire_le > NOW())
            ORDER BY created_at DESC LIMIT 4
        ");
        $stmt->execute([$etabId]);
        $data['annonces'] = $stmt->fetchAll();

        // Dashboard spécifique au rôle enseignant
        if ($role === 'enseignant') {
            $user = Auth::user();
            $stmt = $db->prepare("
                SELECT ac.*, c.nom as classe_nom, m.nom as matiere_nom
                FROM affectations_cours ac
                JOIN classes c ON c.id=ac.classe_id
                JOIN matieres m ON m.id=ac.matiere_id
                JOIN personnel p ON p.id=ac.personnel_id
                WHERE p.user_id=? AND ac.annee_scolaire_id=?
                ORDER BY c.nom, m.nom
            ");
            $stmt->execute([$user['id'], $anneeId]);
            $data['mes_cours'] = $stmt->fetchAll();
        }

        $this->view('dashboard/admin', $data);
    }
}
