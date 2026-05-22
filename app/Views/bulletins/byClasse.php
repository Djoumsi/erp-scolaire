<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/bulletins') ?>">Bulletins</a></li>
    <li class="breadcrumb-item active"><?= e($classe['nom']) ?></li>
</ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Bulletins — <?= e($classe['nom']) ?></h1>
        <p class="text-muted mb-0"><?= e($classe['niveau_nom']) ?></p>
    </div>
    <?php if (can('bulletins.generer') && $periode): ?>
    <form method="POST" action="<?= url('/bulletins/generer') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="classe_id" value="<?= $classe['id'] ?>">
        <input type="hidden" name="periode_id" value="<?= $periode['id'] ?>">
        <button type="submit" class="btn btn-success"
                onclick="return confirm('Recalculer les moyennes et régénérer les bulletins ?')">
            <i class="bi bi-arrow-clockwise me-1"></i>Régénérer
        </button>
    </form>
    <?php endif; ?>
</div>

<!-- Filtre période -->
<?php if (!empty($periodes)): ?>
<div class="mb-3 d-flex gap-2 flex-wrap">
    <?php foreach ($periodes as $p): ?>
    <a href="<?= url('/bulletins/classe/'.$classe['id'].'?periode='.$p['id']) ?>"
       class="btn btn-sm <?= ($periode && $periode['id'] == $p['id']) ? 'btn-primary' : 'btn-outline-primary' ?>">
        <?= e($p['nom']) ?>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (empty($bulletins)): ?>
<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>
    Aucun bulletin généré pour cette période.
    <?php if (can('bulletins.generer') && $periode): ?>
    Utilisez le bouton <strong>Régénérer</strong> pour calculer les moyennes.
    <?php endif; ?>
</div>
<?php else: ?>

<!-- Statistiques rapides -->
<?php
$moyGen = array_filter(array_column($bulletins, 'moyenne_generale'), fn($v) => $v !== null);
$moyClasse = count($moyGen) ? round(array_sum($moyGen) / count($moyGen), 2) : null;
$admis = count(array_filter($moyGen, fn($v) => $v >= 10));
?>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-value"><?= count($bulletins) ?></div><div class="stat-label">Bulletins</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon <?= $moyClasse >= 10 ? 'green' : 'orange' ?>"><i class="bi bi-graph-up"></i></div>
            <div><div class="stat-value"><?= $moyClasse ? number_format($moyClasse, 2) : '—' ?></div><div class="stat-label">Moy. classe</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div><div class="stat-value"><?= $admis ?></div><div class="stat-label">Admis (≥10)</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-x-circle-fill"></i></div>
            <div><div class="stat-value"><?= count($bulletins) - $admis ?></div><div class="stat-label">En difficulté</div></div>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="table-card-header">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-list-ol me-2 text-primary"></i>
            Classement — <?= $periode ? e($periode['nom']) : '' ?>
        </h6>
    </div>
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th width="50">Rang</th>
                <th>Élève</th>
                <th>Matricule</th>
                <th class="text-center">Moy. générale</th>
                <th class="text-center">Mention</th>
                <th class="text-center">Bulletin PDF</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bulletins as $b): ?>
        <?php $ok = ($b['moyenne_generale'] !== null && $b['moyenne_generale'] >= 10); ?>
        <tr>
            <td><span class="badge bg-secondary"><?= $b['rang'] ?? '—' ?></span></td>
            <td class="fw-medium">
                <?= ($b['sexe'] === 'F') ? '<i class="bi bi-gender-female text-pink me-1"></i>' : '<i class="bi bi-gender-male text-primary me-1"></i>' ?>
                <?= e(strtoupper($b['nom']).' '.$b['prenoms']) ?>
            </td>
            <td class="text-muted small"><?= e($b['matricule']) ?></td>
            <td class="text-center">
                <span class="fw-bold fs-6 <?= $ok ? 'text-success' : 'text-danger' ?>">
                    <?= $b['moyenne_generale'] !== null ? number_format($b['moyenne_generale'], 2).'/20' : '—' ?>
                </span>
            </td>
            <td class="text-center"><?= $b['mention'] ? '<span class="badge bg-'.($ok?'success':'danger').'">'.$b['mention'].'</span>' : '—' ?></td>
            <td class="text-center">
                <a href="<?= url('/bulletins/'.$b['id'].'/pdf') ?>" target="_blank"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-printer me-1"></i>PDF
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
