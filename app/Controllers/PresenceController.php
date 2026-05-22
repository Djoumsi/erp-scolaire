<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class PresenceController extends Controller
{
    public function index(Request $request): void
    {
        $this->requirePermission('presences.voir');
        $db     = Database::getInstance();
        $etabId = Auth::etablissementId();
        $user   = Auth::user();
        $date   = $request->get('date', date('Y-m-d'));

        // Si enseignant : ses cours du jour seulement
        if (Auth::role() === 'enseignant') {
            $stmt = $db->prepare("
                SELECT ac.id as affectation_id, c.nom as classe_nom, m.nom as matiere_nom,
                       ch.heure_debut, ch.heure_fin,
                       (SELECT COUNT(*) FROM seances s WHERE s.affectation_id=ac.id AND s.date_seance=? AND s.appel_fait=1) as appel_fait
                FROM affectations_cours ac
                JOIN classes c ON c.id=ac.classe_id
                JOIN matieres m ON m.id=ac.matiere_id
                JOIN personnel per ON per.id=ac.personnel_id
                LEFT JOIN emplois_du_temps edt ON edt.affectation_id=ac.id AND edt.jour_semaine=DAYOFWEEK(?) - 1
                LEFT JOIN creneaux_horaires ch ON ch.id=edt.creneau_id
                WHERE per.user_id=? AND ac.annee_scolaire_id=(SELECT id FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1)
                ORDER BY ch.heure_debut
            ");
            $stmt->execute([$date, $date, $user['id'], $etabId]);
        } else {
            $stmt = $db->prepare("
                SELECT ac.id as affectation_id, c.nom as classe_nom, m.nom as matiere_nom,
                       u.prenoms as prof_prenom, u.nom as prof_nom,
                       (SELECT COUNT(*) FROM seances s WHERE s.affectation_id=ac.id AND s.date_seance=?) as nb_seances,
                       (SELECT COUNT(*) FROM seances s WHERE s.affectation_id=ac.id AND s.date_seance=? AND s.appel_fait=1) as appel_fait,
                       (SELECT COUNT(*) FROM presences pr JOIN seances s ON s.id=pr.seance_id WHERE s.affectation_id=ac.id AND s.date_seance=? AND pr.statut='absent') as nb_absences
                FROM affectations_cours ac
                JOIN classes c ON c.id=ac.classe_id
                JOIN matieres m ON m.id=ac.matiere_id
                JOIN personnel per ON per.id=ac.personnel_id
                JOIN users u ON u.id=per.user_id
                WHERE c.etablissement_id=? AND ac.annee_scolaire_id=(SELECT id FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1)
                ORDER BY c.nom, m.nom
            ");
            $stmt->execute([$date, $date, $date, $etabId, $etabId]);
        }
        $cours = $stmt->fetchAll();

        $this->view('presences/index', [
            'pageTitle' => 'Présences',
            'cours'     => $cours,
            'date'      => $date,
        ]);
    }

    public function appel(Request $request): void
    {
        $this->requirePermission('presences.saisir');
        $db            = Database::getInstance();
        $affectationId = (int) $request->param('affectationId');
        $date          = $request->get('date', date('Y-m-d'));

        $stmtA = $db->prepare("
            SELECT ac.*, c.nom as classe_nom, m.nom as matiere_nom, c.id as classe_id
            FROM affectations_cours ac
            JOIN classes c ON c.id=ac.classe_id
            JOIN matieres m ON m.id=ac.matiere_id
            WHERE ac.id=?
        ");
        $stmtA->execute([$affectationId]);
        $affectation = $stmtA->fetch();
        if (!$affectation) abort(404);

        // Créer ou retrouver la séance du jour
        $stmtS = $db->prepare("SELECT * FROM seances WHERE affectation_id=? AND date_seance=? LIMIT 1");
        $stmtS->execute([$affectationId, $date]);
        $seance = $stmtS->fetch();

        if (!$seance) {
            // Créer la séance automatiquement
            $db->prepare("INSERT INTO seances (affectation_id, date_seance, heure_debut, heure_fin) VALUES (?,?,?,?)")
               ->execute([$affectationId, $date, '08:00:00', '09:00:00']);
            $seanceId = (int) $db->lastInsertId();
            $stmtS->execute([$affectationId, $date]);
            $seance = $stmtS->fetch();
        }

        // Élèves de la classe
        $stmtEl = $db->prepare("
            SELECT e.*, i.id as inscription_id,
                   pr.statut as presence_statut, pr.motif as presence_motif, pr.heure_arrivee
            FROM inscriptions i
            JOIN eleves e ON e.id=i.eleve_id
            LEFT JOIN presences pr ON pr.eleve_id=e.id AND pr.seance_id=?
            WHERE i.classe_id=? AND i.statut='inscrit'
            ORDER BY e.nom, e.prenoms
        ");
        $stmtEl->execute([$seance['id'], $affectation['classe_id']]);
        $eleves = $stmtEl->fetchAll();

        $this->view('presences/appel', [
            'pageTitle'   => 'Appel — ' . $affectation['classe_nom'] . ' / ' . $affectation['matiere_nom'],
            'affectation' => $affectation,
            'seance'      => $seance,
            'eleves'      => $eleves,
            'date'        => $date,
        ]);
    }

    public function storeAppel(Request $request): void
    {
        $this->requirePermission('presences.saisir');
        $db       = Database::getInstance();
        $seanceId = (int) $request->post('seance_id');
        $presData = $request->post('presence', []);

        $db->beginTransaction();
        try {
            foreach ($presData as $eleveId => $statut) {
                $eleveId = (int) $eleveId;
                $statut  = in_array($statut, ['present','absent','retard','excuse']) ? $statut : 'present';

                $db->prepare("INSERT INTO presences (seance_id, eleve_id, statut) VALUES (?,?,?)
                              ON DUPLICATE KEY UPDATE statut=VALUES(statut)")
                   ->execute([$seanceId, $eleveId, $statut]);
            }
            // Marquer l'appel comme fait
            $db->prepare("UPDATE seances SET appel_fait=1 WHERE id=?")->execute([$seanceId]);
            $db->commit();
            Session::flash('success', 'Appel enregistré avec succès.');
        } catch (\Throwable) {
            $db->rollBack();
            Session::flash('error', 'Erreur lors de l\'enregistrement de l\'appel.');
        }

        $stmt = $db->prepare("SELECT affectation_id, date_seance FROM seances WHERE id=?");
        $stmt->execute([$seanceId]);
        $s = $stmt->fetch();
        redirect("/presences/appel/{$s['affectation_id']}?date={$s['date_seance']}");
    }

    public function rapport(Request $request): void
    {
        $this->requirePermission('presences.voir');
        $db       = Database::getInstance();
        $etabId   = Auth::etablissementId();
        $classeId = (int) $request->get('classe', 0);

        // Année en cours
        $stmtA = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1");
        $stmtA->execute([$etabId]);
        $annee = $stmtA->fetch() ?: null;

        // Toutes les classes
        $stmtC = $db->prepare("SELECT c.*, n.nom as niveau_nom FROM classes c JOIN niveaux n ON n.id=c.niveau_id WHERE c.etablissement_id=? AND c.annee_scolaire_id=? ORDER BY c.nom");
        $stmtC->execute([$etabId, $annee['id'] ?? 0]);
        $classes = $stmtC->fetchAll();

        $rapport = [];
        $totalSeances = 0;

        if ($classeId) {
            // Rapport détaillé par élève pour une classe
            $stmtR = $db->prepare("
                SELECT e.id, e.nom, e.prenoms, e.matricule,
                       COUNT(DISTINCT s.id) as total_seances,
                       COUNT(CASE WHEN pr.statut='absent' THEN 1 END) as nb_absences,
                       COUNT(CASE WHEN pr.statut='retard' THEN 1 END) as nb_retards,
                       COUNT(CASE WHEN pr.statut='excuse' THEN 1 END) as nb_excuses,
                       COUNT(CASE WHEN pr.statut='present' THEN 1 END) as nb_presents
                FROM inscriptions i
                JOIN eleves e ON e.id=i.eleve_id
                LEFT JOIN seances s ON s.affectation_id IN (
                    SELECT id FROM affectations_cours WHERE classe_id=i.classe_id
                ) AND s.appel_fait=1
                LEFT JOIN presences pr ON pr.seance_id=s.id AND pr.eleve_id=e.id
                WHERE i.classe_id=? AND i.statut='inscrit'
                GROUP BY e.id, e.nom, e.prenoms, e.matricule
                ORDER BY nb_absences DESC, e.nom ASC
            ");
            $stmtR->execute([$classeId]);
            $rapport = $stmtR->fetchAll();

            // Total séances de la classe
            $stmtS = $db->prepare("SELECT COUNT(*) FROM seances s JOIN affectations_cours ac ON ac.id=s.affectation_id WHERE ac.classe_id=? AND s.appel_fait=1");
            $stmtS->execute([$classeId]);
            $totalSeances = (int) $stmtS->fetchColumn();
        } else {
            // Rapport global toutes classes
            $stmtR = $db->prepare("
                SELECT c.id as classe_id, c.nom as classe_nom,
                       COUNT(DISTINCT i.eleve_id) as nb_eleves,
                       COUNT(DISTINCT s.id) as nb_seances,
                       COUNT(CASE WHEN pr.statut='absent' THEN 1 END) as nb_absences,
                       COUNT(CASE WHEN pr.statut='retard' THEN 1 END) as nb_retards
                FROM classes c
                LEFT JOIN inscriptions i ON i.classe_id=c.id AND i.statut='inscrit'
                LEFT JOIN affectations_cours ac ON ac.classe_id=c.id
                LEFT JOIN seances s ON s.affectation_id=ac.id AND s.appel_fait=1
                LEFT JOIN presences pr ON pr.seance_id=s.id AND pr.eleve_id=i.eleve_id
                WHERE c.etablissement_id=? AND c.annee_scolaire_id=?
                GROUP BY c.id, c.nom
                ORDER BY c.nom
            ");
            $stmtR->execute([$etabId, $annee['id'] ?? 0]);
            $rapport = $stmtR->fetchAll();
        }

        $this->view('presences/rapport', [
            'pageTitle'    => 'Rapport des présences',
            'classes'      => $classes,
            'classeId'     => $classeId,
            'rapport'      => $rapport,
            'totalSeances' => $totalSeances,
            'annee'        => $annee,
        ]);
    }
}
