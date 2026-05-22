<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/rapports') ?>">Rapports</a></li><li class="breadcrumb-item active">Notes</li></ol>

<div class="page-header">
    <h1><i class="bi bi-pencil-square text-warning me-2"></i>Rapport Notes</h1>
    <a href="<?= url('/rapports/notes/export?periode=' . $periodeId) ?>" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-1"></i>Exporter Excel
    </a>
</div>

<!-- Filtre période -->
<div class="form-card mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small">Période</label>
            <select name="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                <?php foreach ($periodes as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $p['id'] == $periodeId ? 'selected' : '' ?>><?= e($p['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<?php if (empty($parClasse)): ?>
<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Aucune note saisie pour cette période.</div>
<?php return; endif; ?>

<!-- Moyennes générales par classe -->
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="table-card">
            <div class="px-3 pt-3 pb-2 fw-semibold border-bottom">Moyennes générales par classe</div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light">
                    <tr><th>Classe</th><th class="text-center">Moy. générale</th><th class="text-center">Admis</th><th class="text-center">Effectif</th><th class="text-center">Taux</th></tr>
                </thead>
                <tbody>
                <?php foreach ($parClasse as $c): ?>
                <?php $taux = $c['nb_eleves'] > 0 ? round($c['nb_admis']/$c['nb_eleves']*100) : 0; ?>
                <tr>
                    <td class="fw-semibold"><?= e($c['classe']) ?></td>
                    <td class="text-center">
                        <span class="badge <?= $c['moyenne_generale'] >= 10 ? 'bg-success' : 'bg-danger' ?>">
                            <?= number_format($c['moyenne_generale'], 2) ?>
                        </span>
                    </td>
                    <td class="text-center"><?= $c['nb_admis'] ?></td>
                    <td class="text-center"><?= $c['nb_eleves'] ?></td>
                    <td class="text-center"><?= $taux ?>%</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="form-card">
            <div class="fw-semibold mb-3">Moyennes générales par classe</div>
            <canvas id="chartMoyennes" height="220"></canvas>
        </div>
    </div>
</div>

<!-- Détail par classe et matière -->
<div class="table-card">
    <div class="px-3 pt-3 pb-2 fw-semibold border-bottom">Détail par classe et matière</div>
    <table class="table table-hover mb-0 small">
        <thead class="table-light">
            <tr><th>Classe</th><th>Matière</th><th class="text-center">Coeff.</th><th class="text-center">Moyenne</th><th class="text-center">Min</th><th class="text-center">Max</th><th class="text-center">Notes</th><th class="text-center">Admis</th></tr>
        </thead>
        <tbody>
        <?php $lastClasse = ''; foreach ($parClasseMatiere as $r): ?>
        <?php if ($r['classe'] !== $lastClasse): $lastClasse = $r['classe']; ?>
        <tr class="table-light"><td colspan="8" class="fw-bold"><?= e($r['classe']) ?></td></tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td><?= e($r['matiere']) ?></td>
            <td class="text-center text-muted"><?= $r['coefficient'] ?></td>
            <td class="text-center">
                <span class="fw-semibold <?= $r['moyenne'] >= 10 ? 'text-success' : 'text-danger' ?>">
                    <?= number_format($r['moyenne'], 2) ?>
                </span>
            </td>
            <td class="text-center text-muted"><?= number_format($r['note_min'], 2) ?></td>
            <td class="text-center text-muted"><?= number_format($r['note_max'], 2) ?></td>
            <td class="text-center"><?= $r['nb_notes'] ?></td>
            <td class="text-center"><?= $r['nb_admis'] ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartMoyennes'), {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(fn($c) => '"'.e($c['classe']).'"', $parClasse)) ?>],
        datasets: [{
            label: 'Moyenne générale',
            data: [<?= implode(',', array_column($parClasse, 'moyenne_generale')) ?>],
            backgroundColor: (ctx) => ctx.raw >= 10 ? 'rgba(34,197,94,.7)' : 'rgba(239,68,68,.7)',
            borderRadius: 6,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, max: 20, ticks: { callback: v => v + '/20' } } }
    }
});
</script>
