<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/rapports') ?>">Rapports</a></li><li class="breadcrumb-item active">Présences</li></ol>

<div class="page-header">
    <h1><i class="bi bi-clipboard2-check-fill text-secondary me-2"></i>Rapport Présences</h1>
</div>

<!-- Filtre classe -->
<div class="form-card mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small">Classe</label>
            <select name="classe" class="form-select form-select-sm" onchange="this.form.submit()">
                <?php foreach ($classes as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id'] == $classeId ? 'selected' : '' ?>><?= e($c['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<?php if (empty($parEleve)): ?>
<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Aucune donnée de présence pour cette classe.</div>
<?php return; endif; ?>

<div class="row g-4">
    <!-- Tableau par élève -->
    <div class="col-lg-8">
        <div class="table-card">
            <div class="px-3 pt-3 pb-2 fw-semibold border-bottom">Absentéisme par élève</div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light">
                    <tr><th>Élève</th><th class="text-center">Séances</th><th class="text-center">Absences</th><th class="text-center">Retards</th><th class="text-center">Excusés</th><th class="text-center">Taux abs.</th></tr>
                </thead>
                <tbody>
                <?php foreach ($parEleve as $e): ?>
                <tr class="<?= $e['taux_absence'] > 20 ? 'table-danger' : ($e['taux_absence'] > 10 ? 'table-warning' : '') ?>">
                    <td>
                        <div class="fw-semibold"><?= e($e['prenoms'].' '.$e['nom']) ?></div>
                        <div class="text-muted font-monospace" style="font-size:.75rem"><?= e($e['matricule']) ?></div>
                    </td>
                    <td class="text-center"><?= $e['total_seances'] ?></td>
                    <td class="text-center fw-bold <?= $e['absences'] > 0 ? 'text-danger' : 'text-success' ?>"><?= $e['absences'] ?></td>
                    <td class="text-center text-warning"><?= $e['retards'] ?></td>
                    <td class="text-center text-info"><?= $e['excuses'] ?></td>
                    <td class="text-center">
                        <span class="badge <?= $e['taux_absence'] > 20 ? 'bg-danger' : ($e['taux_absence'] > 10 ? 'bg-warning text-dark' : 'bg-success') ?>">
                            <?= $e['taux_absence'] ?>%
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Absentéisme par matière -->
    <div class="col-lg-4">
        <div class="form-card mb-4">
            <div class="fw-semibold mb-3">Par matière</div>
            <?php foreach ($parMatiere as $m): ?>
            <div class="mb-2">
                <div class="d-flex justify-content-between small mb-1">
                    <span><?= e($m['matiere']) ?></span>
                    <span class="fw-bold"><?= $m['taux'] ?>%</span>
                </div>
                <div class="progress" style="height:6px;border-radius:3px">
                    <div class="progress-bar <?= $m['taux'] > 15 ? 'bg-danger' : ($m['taux'] > 8 ? 'bg-warning' : 'bg-success') ?>"
                         style="width:<?= min(100,$m['taux']*3) ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="form-card">
            <div class="fw-semibold mb-3">Taux d'absentéisme</div>
            <canvas id="chartAbsences" height="200"></canvas>
        </div>
    </div>
</div>

<div class="mt-3 small text-muted">
    <span class="badge bg-danger me-1">&nbsp;</span> Taux &gt; 20% — alerte critique
    <span class="badge bg-warning text-dark ms-2 me-1">&nbsp;</span> Taux &gt; 10% — surveillance
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
<?php
$topAbsences = array_slice($parEleve, 0, 8);
?>
new Chart(document.getElementById('chartAbsences'), {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(fn($e) => '"'.e($e['nom']).'"', $topAbsences)) ?>],
        datasets: [{
            label: 'Absences',
            data: [<?= implode(',', array_column($topAbsences, 'absences')) ?>],
            backgroundColor: 'rgba(239,68,68,.7)',
            borderRadius: 4,
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true } }
    }
});
</script>
