<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/comptabilite') ?>">Comptabilité</a></li><li class="breadcrumb-item active">Bilan</li></ol>

<div class="page-header">
    <h1><i class="bi bi-bar-chart-line text-primary me-2"></i>Bilan comptable annuel</h1>
    <a href="<?= url('/comptabilite/export') ?>" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-1"></i>Exporter Excel
    </a>
</div>

<?php if (!$annee): ?>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Aucune année scolaire en cours.</div>
<?php return; endif; ?>

<div class="alert alert-info border-0 mb-4">
    <i class="bi bi-calendar3 me-2"></i>Année scolaire : <strong><?= e($annee['libelle']) ?></strong>
</div>

<!-- KPIs -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="form-card text-center py-3">
            <div class="stat-icon green mx-auto mb-2"><i class="bi bi-arrow-down-circle fs-5"></i></div>
            <div class="fs-3 fw-bold text-success"><?= money($totalRecettes) ?></div>
            <div class="text-muted small">Total recettes</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-card text-center py-3">
            <div class="stat-icon orange mx-auto mb-2"><i class="bi bi-arrow-up-circle fs-5"></i></div>
            <div class="fs-3 fw-bold text-danger"><?= money($totalDepenses) ?></div>
            <div class="text-muted small">Total dépenses</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-card text-center py-3">
            <div class="stat-icon <?= $solde >= 0 ? 'green' : 'orange' ?> mx-auto mb-2"><i class="bi bi-calculator fs-5"></i></div>
            <div class="fs-3 fw-bold <?= $solde >= 0 ? 'text-success' : 'text-danger' ?>"><?= money(abs($solde)) ?></div>
            <div class="text-muted small">Solde <?= $solde >= 0 ? '(bénéfice)' : '(déficit)' ?></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Graphique évolution mensuelle -->
    <div class="col-lg-8">
        <div class="form-card">
            <div class="fw-semibold mb-3">Évolution mensuelle</div>
            <canvas id="chartBilan" height="250"></canvas>
        </div>
    </div>
    <!-- Répartition catégories -->
    <div class="col-lg-4">
        <div class="form-card">
            <div class="fw-semibold mb-3">Dépenses par catégorie</div>
            <canvas id="chartCat" height="250"></canvas>
        </div>
    </div>
</div>

<!-- Tableau mensuel -->
<div class="table-card mt-4">
    <div class="px-3 pt-3 pb-2 fw-semibold border-bottom">Récapitulatif mensuel</div>
    <table class="table table-hover mb-0 small">
        <thead class="table-light">
            <tr><th>Mois</th><th class="text-end text-success">Recettes</th><th class="text-end text-danger">Dépenses</th><th class="text-end">Solde</th></tr>
        </thead>
        <tbody>
        <?php $totalR = 0; $totalD = 0; foreach ($parMois as $mois => $data): ?>
        <?php $s = $data['recette'] - $data['depense']; $totalR += $data['recette']; $totalD += $data['depense']; ?>
        <tr>
            <td class="fw-semibold"><?= e($mois) ?></td>
            <td class="text-end text-success"><?= money($data['recette']) ?></td>
            <td class="text-end text-danger"><?= money($data['depense']) ?></td>
            <td class="text-end fw-bold <?= $s >= 0 ? 'text-success' : 'text-danger' ?>"><?= money(abs($s)) ?> <?= $s < 0 ? '<i class="bi bi-arrow-down-right"></i>' : '<i class="bi bi-arrow-up-right"></i>' ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot class="table-dark">
            <tr>
                <td><strong>TOTAL</strong></td>
                <td class="text-end fw-bold text-success"><?= money($totalRecettes) ?></td>
                <td class="text-end fw-bold text-danger"><?= money($totalDepenses) ?></td>
                <td class="text-end fw-bold"><?= money(abs($solde)) ?> <?= $solde >= 0 ? '✓' : '↓' ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const moisLabels = [<?= implode(',', array_map(fn($m) => '"'.e($m).'"', array_keys($parMois))) ?>];
const recettes   = [<?= implode(',', array_map(fn($d) => $d['recette'], array_values($parMois))) ?>];
const depenses   = [<?= implode(',', array_map(fn($d) => $d['depense'], array_values($parMois))) ?>];

new Chart(document.getElementById('chartBilan'), {
    type: 'bar',
    data: {
        labels: moisLabels,
        datasets: [
            { label: 'Recettes', data: recettes, backgroundColor: 'rgba(34,197,94,.7)', borderRadius: 4 },
            { label: 'Dépenses', data: depenses, backgroundColor: 'rgba(239,68,68,.7)', borderRadius: 4 },
        ]
    },
    options: {
        scales: { y: { beginAtZero: true, ticks: { callback: v => (v/1000).toLocaleString() + ' k' } } }
    }
});

<?php $depCat = array_filter($parCategorie, fn($c) => $c['type'] === 'depense'); ?>
new Chart(document.getElementById('chartCat'), {
    type: 'doughnut',
    data: {
        labels: [<?= implode(',', array_map(fn($c) => '"'.e($c['nom']).'"', $depCat)) ?>],
        datasets: [{
            data: [<?= implode(',', array_column($depCat, 'total')) ?>],
            backgroundColor: ['#ef4444','#f97316','#eab308','#84cc16','#22d3ee','#8b5cf6','#ec4899'],
            borderWidth: 0,
        }]
    },
    options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } } }
});
</script>
