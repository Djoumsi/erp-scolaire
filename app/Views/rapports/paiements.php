<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/rapports') ?>">Rapports</a></li><li class="breadcrumb-item active">Paiements</li></ol>

<div class="page-header">
    <h1><i class="bi bi-cash-coin text-success me-2"></i>Rapport Paiements</h1>
    <a href="<?= url('/rapports/paiements/export') ?>" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-1"></i>Exporter Excel
    </a>
</div>

<?php if (!$annee): ?>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Aucune année scolaire en cours.</div>
<?php return; endif; ?>

<!-- KPIs -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="form-card text-center py-3">
            <div class="stat-icon green mx-auto mb-2"><i class="bi bi-cash fs-5"></i></div>
            <div class="fs-4 fw-bold text-success"><?= money($paye) ?></div>
            <div class="text-muted small">Total encaissé</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-card text-center py-3">
            <div class="stat-icon blue mx-auto mb-2"><i class="bi bi-receipt fs-5"></i></div>
            <div class="fs-4 fw-bold"><?= money($attendu) ?></div>
            <div class="text-muted small">Total attendu</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-card text-center py-3">
            <div class="stat-icon <?= $taux >= 80 ? 'green' : ($taux >= 50 ? 'yellow' : 'orange') ?> mx-auto mb-2"><i class="bi bi-percent fs-5"></i></div>
            <div class="fs-4 fw-bold"><?= $taux ?>%</div>
            <div class="text-muted small">Taux de recouvrement</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-card text-center py-3">
            <div class="stat-icon orange mx-auto mb-2"><i class="bi bi-clock-history fs-5"></i></div>
            <div class="fs-4 fw-bold text-danger"><?= $nbRetard ?></div>
            <div class="text-muted small">Dossiers en retard</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Graphique par mois -->
    <div class="col-lg-7">
        <div class="form-card">
            <div class="fw-semibold mb-3">Encaissements par mois</div>
            <canvas id="chartMois" height="220"></canvas>
        </div>
    </div>

    <!-- Taux recouvrement -->
    <div class="col-lg-5">
        <div class="form-card">
            <div class="fw-semibold mb-3">Recouvrement global</div>
            <div class="mb-2 d-flex justify-content-between">
                <span class="small">Payé : <?= money($paye) ?></span>
                <span class="small fw-bold"><?= $taux ?>%</span>
            </div>
            <div class="progress mb-4" style="height:20px;border-radius:10px">
                <div class="progress-bar <?= $taux >= 80 ? 'bg-success' : ($taux >= 50 ? 'bg-warning' : 'bg-danger') ?>"
                     style="width:<?= min(100,$taux) ?>%;border-radius:10px">
                </div>
            </div>
            <div class="text-muted small">Reste à collecter : <strong class="text-danger"><?= money($attendu - $paye) ?></strong></div>
        </div>
    </div>
</div>

<!-- Par classe -->
<div class="table-card mt-4">
    <div class="px-3 pt-3 pb-2 fw-semibold border-bottom">Recouvrement par classe</div>
    <table class="table table-hover mb-0 small">
        <thead class="table-light">
            <tr><th>Classe</th><th class="text-center">Élèves</th><th class="text-end">Attendu</th><th class="text-end">Encaissé</th><th class="text-end">Reste</th><th class="text-center">Taux</th></tr>
        </thead>
        <tbody>
        <?php foreach ($parClasse as $c): ?>
        <?php $t = $c['attendu'] > 0 ? round($c['paye']/$c['attendu']*100) : 0; ?>
        <tr>
            <td class="fw-semibold"><?= e($c['classe']) ?></td>
            <td class="text-center"><?= $c['nb_eleves'] ?></td>
            <td class="text-end"><?= money($c['attendu']) ?></td>
            <td class="text-end text-success fw-semibold"><?= money($c['paye']) ?></td>
            <td class="text-end text-danger"><?= money($c['attendu'] - $c['paye']) ?></td>
            <td class="text-center">
                <div class="progress" style="height:8px;min-width:80px">
                    <div class="progress-bar <?= $t >= 80 ? 'bg-success' : ($t >= 50 ? 'bg-warning' : 'bg-danger') ?>"
                         style="width:<?= min(100,$t) ?>%"></div>
                </div>
                <span class="small"><?= $t ?>%</span>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartMois'), {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(fn($m) => '"'.e($m['mois']).'"', $parMois)) ?>],
        datasets: [{
            label: 'Encaissements',
            data: [<?= implode(',', array_column($parMois, 'total')) ?>],
            backgroundColor: 'rgba(34,197,94,.7)',
            borderRadius: 6,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('fr-FR') + ' F' } } }
    }
});
</script>
