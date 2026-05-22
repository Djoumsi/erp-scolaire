<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/presences') ?>">Présences</a></li>
    <li class="breadcrumb-item active">Rapport</li>
</ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-bar-chart-fill text-info me-2"></i>Rapport des présences</h1>
        <?php if ($annee): ?>
        <p class="text-muted mb-0">Année scolaire : <strong><?= e($annee['libelle']) ?></strong></p>
        <?php endif; ?>
    </div>
</div>

<!-- Filtre classe -->
<div class="form-card mb-4">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label fw-medium">Filtrer par classe</label>
            <select name="classe" class="form-select">
                <option value="">— Vue globale (toutes les classes) —</option>
                <?php foreach ($classes as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $classeId == $c['id'] ? 'selected' : '' ?>>
                    <?= e($c['nom']) ?> — <?= e($c['niveau_nom']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary"><i class="bi bi-filter me-1"></i>Appliquer</button>
            <?php if ($classeId): ?>
            <a href="<?= url('/presences/rapport') ?>" class="btn btn-outline-secondary ms-1">Réinitialiser</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php if (!$classeId): ?>
<!-- VUE GLOBALE PAR CLASSE -->
<div class="table-card">
    <div class="table-card-header">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-grid-3x3-gap me-2 text-info"></i>Synthèse par classe</h6>
    </div>
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Classe</th>
                <th class="text-center">Élèves</th>
                <th class="text-center">Séances</th>
                <th class="text-center">Absences</th>
                <th class="text-center">Retards</th>
                <th class="text-center">Taux présence</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($rapport)): ?>
        <tr><td colspan="7" class="text-center py-5 text-muted">Aucune donnée disponible</td></tr>
        <?php else: ?>
        <?php foreach ($rapport as $r): ?>
        <?php
            $total  = ($r['nb_eleves'] * $r['nb_seances']);
            $taux   = $total > 0 ? round((($total - $r['nb_absences']) / $total) * 100) : 100;
            $color  = $taux >= 90 ? 'success' : ($taux >= 75 ? 'warning' : 'danger');
        ?>
        <tr>
            <td class="fw-medium"><?= e($r['classe_nom']) ?></td>
            <td class="text-center"><?= (int)$r['nb_eleves'] ?></td>
            <td class="text-center"><?= (int)$r['nb_seances'] ?></td>
            <td class="text-center">
                <?= $r['nb_absences'] > 0 ? '<span class="badge bg-danger">'.(int)$r['nb_absences'].'</span>' : '<span class="text-muted">0</span>' ?>
            </td>
            <td class="text-center">
                <?= $r['nb_retards'] > 0 ? '<span class="badge bg-warning text-dark">'.(int)$r['nb_retards'].'</span>' : '<span class="text-muted">0</span>' ?>
            </td>
            <td class="text-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="progress flex-grow-1" style="height:6px;">
                        <div class="progress-bar bg-<?= $color ?>" style="width:<?= $taux ?>%"></div>
                    </div>
                    <small class="text-<?= $color ?> fw-bold"><?= $taux ?>%</small>
                </div>
            </td>
            <td>
                <a href="<?= url('/presences/rapport?classe='.$r['classe_id']) ?>" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-zoom-in me-1"></i>Détail
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php else: ?>
<!-- VUE DÉTAILLÉE PAR CLASSE -->
<?php
$classeNom = '';
foreach ($classes as $c) { if ($c['id'] == $classeId) { $classeNom = $c['nom']; break; } }

$totalAbs = array_sum(array_column($rapport, 'nb_absences'));
$critiques = array_filter($rapport, fn($r) => $totalSeances > 0 && ($r['nb_absences'] / max(1, $totalSeances)) > 0.20);
?>

<!-- Stats rapides -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-value"><?= count($rapport) ?></div><div class="stat-label">Élèves</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-calendar-check"></i></div>
            <div><div class="stat-value"><?= $totalSeances ?></div><div class="stat-label">Séances appel</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-x-circle-fill"></i></div>
            <div><div class="stat-value"><?= $totalAbs ?></div><div class="stat-label">Total absences</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div><div class="stat-value"><?= count($critiques) ?></div><div class="stat-label">Cas critiques (>20%)</div></div>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="table-card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-person-lines-fill me-2 text-info"></i>Absences par élève — <span class="text-primary"><?= e($classeNom) ?></span></h6>
        <small class="text-muted"><?= $totalSeances ?> séances d'appel effectuées</small>
    </div>
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Élève</th>
                <th class="text-center">Absences</th>
                <th class="text-center">Retards</th>
                <th class="text-center">Excusés</th>
                <th class="text-center">Présents</th>
                <th class="text-center" style="width:180px">Taux présence</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($rapport)): ?>
        <tr><td colspan="6" class="text-center py-5 text-muted">Aucune donnée de présence pour cette classe.</td></tr>
        <?php else: ?>
        <?php foreach ($rapport as $r): ?>
        <?php
            $seancesRef = max(1, $totalSeances);
            $taux = $totalSeances > 0 ? round((($r['nb_presents'] ?? 0) / $seancesRef) * 100) : 100;
            $taux = min(100, max(0, $taux));
            $color = $taux >= 90 ? 'success' : ($taux >= 75 ? 'warning' : 'danger');
            $isAlert = $totalSeances > 0 && ($r['nb_absences'] / $seancesRef) > 0.20;
        ?>
        <tr <?= $isAlert ? 'class="table-danger"' : '' ?>>
            <td class="fw-medium">
                <?= $isAlert ? '<i class="bi bi-exclamation-triangle-fill text-danger me-1"></i>' : '' ?>
                <?= e(strtoupper($r['nom']).' '.$r['prenoms']) ?>
                <div class="text-muted small"><?= e($r['matricule']) ?></div>
            </td>
            <td class="text-center">
                <span class="fw-bold <?= $r['nb_absences'] > 0 ? 'text-danger' : 'text-muted' ?>">
                    <?= (int)$r['nb_absences'] ?>
                </span>
            </td>
            <td class="text-center text-warning fw-medium"><?= (int)$r['nb_retards'] ?></td>
            <td class="text-center text-info"><?= (int)$r['nb_excuses'] ?></td>
            <td class="text-center text-success"><?= (int)($r['nb_presents'] ?? 0) ?></td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div class="progress flex-grow-1" style="height:6px;">
                        <div class="progress-bar bg-<?= $color ?>" style="width:<?= $taux ?>%"></div>
                    </div>
                    <small class="fw-bold text-<?= $color ?>"><?= $taux ?>%</small>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
