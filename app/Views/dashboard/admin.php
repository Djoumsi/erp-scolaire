<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Tableau de bord</li>
    </ol>
</nav>

<div class="page-header">
    <div>
        <h1>Tableau de bord</h1>
        <?php if ($annee): ?>
        <p class="text-muted mb-0">Année scolaire : <strong><?= e($annee['libelle']) ?></strong></p>
        <?php endif; ?>
    </div>
    <span class="text-muted small">
        <i class="bi bi-calendar3 me-1"></i><?= dateFormat(date('Y-m-d'), 'l d F Y') ?>
    </span>
</div>

<?php if (!$annee): ?>
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    Aucune année scolaire en cours. <a href="<?= url('/parametres') ?>" class="alert-link">Configurer les paramètres</a>
</div>
<?php endif; ?>

<!-- Statistiques -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="<?= url('/eleves') ?>" class="text-decoration-none">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-value"><?= number_format($total_eleves) ?></div>
                <div class="stat-label">Élèves inscrits</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="<?= url('/personnel') ?>" class="text-decoration-none">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-person-badge-fill"></i></div>
            <div>
                <div class="stat-value"><?= number_format($total_personnel) ?></div>
                <div class="stat-label">Personnel actif</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="<?= url('/classes') ?>" class="text-decoration-none">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-grid-3x3-gap-fill"></i></div>
            <div>
                <div class="stat-value"><?= number_format($total_classes) ?></div>
                <div class="stat-label">Classes actives</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="<?= url('/paiements') ?>" class="text-decoration-none">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="stat-value"><?= $paiements_mois >= 1000000 ? number_format($paiements_mois/1000000,1).'M' : number_format($paiements_mois/1000,0).'k' ?></div>
                <div class="stat-label">Encaissés ce mois</div>
            </div>
        </div>
        </a>
    </div>
</div>

<!-- Deuxième ligne de stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon <?= $taux_paiement >= 75 ? 'green' : ($taux_paiement >= 50 ? 'orange' : 'red') ?>">
                <i class="bi bi-pie-chart-fill"></i>
            </div>
            <div>
                <div class="stat-value"><?= number_format($taux_paiement, 1) ?>%</div>
                <div class="stat-label">Taux de paiement</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <a href="<?= url('/paiements?statut=solde') ?>" class="text-decoration-none">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="stat-value"><?= $dossiers_soldes ?> / <?= $dossiers_total ?></div>
                <div class="stat-label">Dossiers soldés</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="<?= url('/presences') ?>" class="text-decoration-none">
        <div class="stat-card">
            <div class="stat-icon <?= $absences_jour > 0 ? 'orange' : 'green' ?>">
                <i class="bi bi-clipboard2-x-fill"></i>
            </div>
            <div>
                <div class="stat-value"><?= $absences_jour ?></div>
                <div class="stat-label">Absences aujourd'hui</div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="<?= url('/messages') ?>" class="text-decoration-none">
        <div class="stat-card">
            <div class="stat-icon <?= $messages_non_lus > 0 ? 'blue' : 'green' ?>">
                <i class="bi bi-chat-dots-fill"></i>
            </div>
            <div>
                <div class="stat-value"><?= $messages_non_lus ?></div>
                <div class="stat-label">Messages non lus</div>
            </div>
        </div>
        </a>
    </div>
</div>

<!-- Alertes critiques uniquement -->
<?php if ($retards_paiement > 0): ?>
<div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <div>
        <strong><?= $retards_paiement ?> tranche(s)</strong> de paiement en retard d'échéance.
        <a href="<?= url('/paiements') ?>" class="alert-link ms-1">Voir les dossiers →</a>
    </div>
</div>
<?php endif; ?>

<div class="row g-3">
    <!-- Dernières inscriptions -->
    <div class="col-lg-8">
        <div class="table-card">
            <div class="table-card-header">
                <h6 class="mb-0 fw-semibold">Dernières inscriptions</h6>
                <?php if (can('eleves.voir')): ?>
                <a href="<?= url('/eleves') ?>" class="btn btn-sm btn-outline-primary">Voir tout</a>
                <?php endif; ?>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Matricule</th>
                            <th>Nom & Prénoms</th>
                            <th>Classe</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dernieres_inscriptions)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Aucune inscription récente</td></tr>
                        <?php else: ?>
                        <?php foreach ($dernieres_inscriptions as $i): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark"><?= e($i['matricule']) ?></span></td>
                            <td class="fw-medium"><?= e($i['prenoms'] . ' ' . strtoupper($i['nom'])) ?></td>
                            <td><?= e($i['classe']) ?></td>
                            <td class="text-muted small"><?= dateFormat($i['date_inscription']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Annonces -->
    <div class="col-lg-4">
        <div class="table-card">
            <div class="table-card-header">
                <h6 class="mb-0 fw-semibold">Annonces récentes</h6>
                <?php if (can('communication.creer')): ?>
                <a href="<?= url('/annonces/creer') ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus"></i>
                </a>
                <?php endif; ?>
            </div>
            <div class="p-0">
                <?php if (empty($annonces)): ?>
                <div class="p-4 text-center text-muted small">Aucune annonce</div>
                <?php else: ?>
                <?php foreach ($annonces as $a): ?>
                <a href="<?= url('/annonces/' . $a['id']) ?>" class="list-group-item list-group-item-action border-0 border-bottom px-3 py-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="fw-medium small"><?= e($a['titre']) ?></span>
                        <?php if ($a['priorite'] === 'urgente'): ?>
                        <span class="badge bg-danger">Urgent</span>
                        <?php elseif ($a['priorite'] === 'importante'): ?>
                        <span class="badge bg-warning text-dark">Important</span>
                        <?php endif; ?>
                    </div>
                    <div class="text-muted" style="font-size:.75rem"><?= dateTimeFormat($a['created_at']) ?></div>
                </a>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<?php if (can('rapports.voir') && $annee): ?>
<div class="row g-3 mt-1 mb-2">
    <div class="col-lg-5">
        <div class="form-card h-100">
            <div class="fw-semibold mb-3 small text-uppercase text-muted"><i class="bi bi-bar-chart-fill me-1 text-success"></i>Encaissements — 6 derniers mois</div>
            <canvas id="chartPaiements" height="140"></canvas>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-card h-100">
            <div class="fw-semibold mb-3 small text-uppercase text-muted"><i class="bi bi-people-fill me-1 text-primary"></i>Répartition élèves</div>
            <canvas id="chartEleves" height="140"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-card h-100">
            <div class="fw-semibold mb-3 small text-uppercase text-muted"><i class="bi bi-cash-stack me-1 text-warning"></i>Taux de paiement par classe</div>
            <canvas id="chartClasses" height="140"></canvas>
        </div>
    </div>
</div>

<script src="<?= APP_URL ?>/assets/js/chart.umd.min.js"></script>
<script>
<?php
$db     = \App\Core\Database::getInstance();
$etabId = \App\Core\Auth::etablissementId();
$anneeId = $annee['id'];

// Paiements 6 derniers mois
$stmtP = $db->prepare("
    SELECT DATE_FORMAT(p.date_paiement,'%b %Y') as mois, SUM(p.montant) as total
    FROM paiements p
    JOIN dossiers_paiement dp ON dp.id=p.dossier_paiement_id
    JOIN inscriptions i ON i.id=dp.inscription_id
    JOIN classes c ON c.id=i.classe_id
    WHERE c.etablissement_id=? AND p.annule=0 AND p.date_paiement >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(p.date_paiement,'%Y-%m'), DATE_FORMAT(p.date_paiement,'%b %Y')
    ORDER BY MIN(p.date_paiement)
");
$stmtP->execute([$etabId]);
$chartPaie = $stmtP->fetchAll();

// Répartition sexe
$stmtS = $db->prepare("SELECT e.sexe, COUNT(*) as nb FROM inscriptions i JOIN classes c ON c.id=i.classe_id JOIN eleves e ON e.id=i.eleve_id WHERE c.etablissement_id=? AND i.annee_scolaire_id=? AND i.statut='inscrit' GROUP BY e.sexe");
$stmtS->execute([$etabId, $anneeId]);
$sexeData = ['M' => 0, 'F' => 0];
foreach ($stmtS->fetchAll() as $r) $sexeData[$r['sexe']] = (int)$r['nb'];

// Taux de paiement par classe
$stmtCl = $db->prepare("
    SELECT c.nom as classe,
           ROUND(COALESCE(SUM(dp.montant_paye),0) / NULLIF(SUM(dp.montant_total),0) * 100, 1) as taux
    FROM classes c
    JOIN inscriptions i ON i.classe_id=c.id AND i.statut='inscrit'
    JOIN dossiers_paiement dp ON dp.inscription_id=i.id
    WHERE c.etablissement_id=? AND c.annee_scolaire_id=?
    GROUP BY c.id, c.nom
    ORDER BY c.nom
");
$stmtCl->execute([$etabId, $anneeId]);
$chartClasses = $stmtCl->fetchAll();
?>

// Chart 1 — Encaissements 6 mois
new Chart(document.getElementById('chartPaiements'), {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(fn($r) => '"'.e($r['mois']).'"', $chartPaie)) ?>],
        datasets: [{
            label: 'Encaissements (FCFA)',
            data: [<?= implode(',', array_column($chartPaie, 'total')) ?>],
            backgroundColor: 'rgba(34,197,94,.75)',
            borderColor: '#16a34a',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => (v/1000).toFixed(0) + 'k' } } }
    }
});

// Chart 2 — Répartition élèves
new Chart(document.getElementById('chartEleves'), {
    type: 'doughnut',
    data: {
        labels: ['Garçons', 'Filles'],
        datasets: [{
            data: [<?= $sexeData['M'] ?>, <?= $sexeData['F'] ?>],
            backgroundColor: ['#3b82f6', '#f472b6'],
            borderWidth: 0,
            hoverOffset: 8,
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
        }
    }
});

// Chart 3 — Taux paiement par classe
<?php if (!empty($chartClasses)): ?>
new Chart(document.getElementById('chartClasses'), {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(fn($r) => '"'.e($r['classe']).'"', $chartClasses)) ?>],
        datasets: [{
            label: 'Taux de paiement (%)',
            data: [<?= implode(',', array_column($chartClasses, 'taux')) ?>],
            backgroundColor: [<?= implode(',', array_map(fn($r) => $r['taux'] >= 80 ? '"rgba(34,197,94,.75)"' : ($r['taux'] >= 50 ? '"rgba(245,158,11,.75)"' : '"rgba(239,68,68,.75)"'), $chartClasses)) ?>],
            borderRadius: 4,
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } } }
    }
});
<?php endif; ?>
</script>
<?php endif; ?>

<!-- Actions rapides -->
<div class="row g-3 mt-1">
    <div class="col-12">
        <h6 class="fw-semibold text-muted small text-uppercase">Actions rapides</h6>
        <div class="d-flex flex-wrap gap-2">
            <?php if (can('eleves.creer')): ?>
            <a href="<?= url('/eleves/inscrire') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-person-plus me-1"></i>Inscrire un élève
            </a>
            <?php endif; ?>
            <?php if (can('paiements.encaisser')): ?>
            <a href="<?= url('/paiements') ?>" class="btn btn-outline-success btn-sm">
                <i class="bi bi-cash me-1"></i>Encaisser un paiement
            </a>
            <?php endif; ?>
            <?php if (can('presences.saisir')): ?>
            <a href="<?= url('/presences') ?>" class="btn btn-outline-info btn-sm">
                <i class="bi bi-clipboard2-check me-1"></i>Faire l'appel
            </a>
            <?php endif; ?>
            <?php if (can('notes.saisir')): ?>
            <a href="<?= url('/notes') ?>" class="btn btn-outline-warning btn-sm">
                <i class="bi bi-pencil me-1"></i>Saisir des notes
            </a>
            <?php endif; ?>
            <?php if (can('bulletins.generer')): ?>
            <a href="<?= url('/bulletins') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-file-earmark-text me-1"></i>Bulletins
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
