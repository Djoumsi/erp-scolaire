<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/rapports') ?>">Rapports</a></li><li class="breadcrumb-item active">Élèves</li></ol>

<div class="page-header">
    <h1><i class="bi bi-people-fill text-primary me-2"></i>Rapport Élèves</h1>
    <a href="<?= url('/rapports/eleves/export') ?>" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-1"></i>Exporter Excel
    </a>
</div>

<?php if (!$annee): ?>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Aucune année scolaire en cours. Configurez-en une dans les <a href="<?= url('/parametres') ?>">paramètres</a>.</div>
<?php return; endif; ?>

<div class="alert alert-info border-0 mb-4">
    <i class="bi bi-calendar3 me-2"></i>Année scolaire : <strong><?= e($annee['libelle']) ?></strong> — Total inscrits : <strong><?= number_format($total) ?> élèves</strong>
</div>

<!-- Stat cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="form-card text-center py-3">
            <div class="stat-icon blue mx-auto mb-2"><i class="bi bi-people fs-5"></i></div>
            <div class="fs-3 fw-bold"><?= number_format($total) ?></div>
            <div class="text-muted small">Total inscrits</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-card text-center py-3">
            <div class="stat-icon blue mx-auto mb-2"><i class="bi bi-gender-male fs-5"></i></div>
            <div class="fs-3 fw-bold"><?= number_format($sexes['M']) ?></div>
            <div class="text-muted small">Garçons (<?= $total > 0 ? round($sexes['M']/$total*100) : 0 ?>%)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-card text-center py-3">
            <div class="stat-icon orange mx-auto mb-2"><i class="bi bi-gender-female fs-5"></i></div>
            <div class="fs-3 fw-bold"><?= number_format($sexes['F']) ?></div>
            <div class="text-muted small">Filles (<?= $total > 0 ? round($sexes['F']/$total*100) : 0 ?>%)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-card text-center py-3">
            <div class="stat-icon green mx-auto mb-2"><i class="bi bi-building fs-5"></i></div>
            <div class="fs-3 fw-bold"><?= count($parClasse) ?></div>
            <div class="text-muted small">Classes</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Tableau par classe -->
    <div class="col-lg-7">
        <div class="table-card">
            <div class="px-3 pt-3 pb-2 fw-semibold border-bottom">Effectifs par classe</div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light">
                    <tr><th>Classe</th><th>Niveau</th><th class="text-center">Total</th><th class="text-center">Garçons</th><th class="text-center">Filles</th><th class="text-center">%F</th></tr>
                </thead>
                <tbody>
                <?php foreach ($parClasse as $c): ?>
                <tr>
                    <td class="fw-semibold"><?= e($c['classe']) ?></td>
                    <td class="text-muted"><?= e($c['niveau'] ?? '-') ?></td>
                    <td class="text-center"><span class="badge bg-primary bg-opacity-15 text-primary"><?= $c['total'] ?></span></td>
                    <td class="text-center"><?= $c['garcons'] ?></td>
                    <td class="text-center"><?= $c['filles'] ?></td>
                    <td class="text-center"><?= $c['total'] > 0 ? round($c['filles']/$c['total']*100) : 0 ?>%</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Graphique répartition + évolution -->
    <div class="col-lg-5">
        <div class="form-card mb-4">
            <div class="fw-semibold mb-3">Répartition par sexe</div>
            <canvas id="chartSexe" height="200"></canvas>
        </div>
        <div class="form-card">
            <div class="fw-semibold mb-3">Évolution des inscriptions</div>
            <canvas id="chartEvolution" height="200"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartSexe'), {
    type: 'doughnut',
    data: {
        labels: ['Garçons', 'Filles'],
        datasets: [{
            data: [<?= $sexes['M'] ?>, <?= $sexes['F'] ?>],
            backgroundColor: ['#3b82f6','#f59e0b'],
            borderWidth: 0,
        }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('chartEvolution'), {
    type: 'line',
    data: {
        labels: [<?= implode(',', array_map(fn($e) => '"'.e($e['mois']).'"', $evolution)) ?>],
        datasets: [{
            label: 'Inscriptions',
            data: [<?= implode(',', array_column($evolution, 'nb')) ?>],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,.1)',
            fill: true,
            tension: 0.4,
        }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
