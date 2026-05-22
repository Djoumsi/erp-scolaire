<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/notes') ?>">Notes</a></li>
    <li class="breadcrumb-item active"><?= e($evaluation['titre'] ?? $evaluation['type_nom']) ?></li>
</ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-clipboard2-data-fill text-primary me-2"></i><?= e($evaluation['titre'] ?? $evaluation['type_nom']) ?></h1>
        <p class="text-muted mb-0">
            <?= e($evaluation['classe_nom']) ?> — <?= e($evaluation['matiere_nom']) ?>
            · <?= e($evaluation['periode_nom']) ?>
            · Note sur <?= (float)$evaluation['note_sur'] ?>
            · Coeff. <?= (float)$evaluation['coefficient'] ?>
        </p>
    </div>
    <a href="<?= url('/notes/saisir/'.$evaluation['affectation_id'].'?periode='.$evaluation['periode_id']) ?>"
       class="btn btn-outline-primary">
        <i class="bi bi-pencil-square me-1"></i>Modifier les notes
    </a>
</div>

<!-- Statistiques -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-value"><?= count($notes) ?></div><div class="stat-label">Élèves</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-calculator"></i></div>
            <div>
                <div class="stat-value"><?= $stats['moyenne'] !== null ? number_format($stats['moyenne'], 2) : '—' ?></div>
                <div class="stat-label">Moyenne /20</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-arrow-up-circle-fill"></i></div>
            <div>
                <div class="stat-value"><?= $stats['max'] !== null ? number_format($stats['max'], 1) : '—' ?></div>
                <div class="stat-label">Maximum</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon <?= $stats['nb_absent'] > 0 ? 'red' : 'green' ?>"><i class="bi bi-person-x-fill"></i></div>
            <div><div class="stat-value"><?= $stats['nb_absent'] ?></div><div class="stat-label">Absents</div></div>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="table-card-header">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-list-ol me-2 text-primary"></i>Notes des élèves</h6>
        <span class="text-muted small"><?= $stats['nb_notes'] ?> note(s) saisie(s)</span>
    </div>
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Élève</th>
                <th>Matricule</th>
                <th class="text-center">Note / <?= (float)$evaluation['note_sur'] ?></th>
                <th class="text-center">Note / 20</th>
                <th class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
        <?php $rang = 1; foreach ($notes as $n): ?>
        <?php
            $sur20 = ($n['note'] !== null) ? round(($n['note'] / $evaluation['note_sur']) * 20, 2) : null;
            $absent = ($n['statut'] ?? '') === 'absent' || $n['note'] === null;
        ?>
        <tr>
            <td class="text-muted"><?= $absent ? '—' : $rang++ ?></td>
            <td class="fw-medium"><?= e(strtoupper($n['nom']).' '.$n['prenoms']) ?></td>
            <td class="text-muted small"><?= e($n['matricule']) ?></td>
            <td class="text-center">
                <?php if ($absent): ?>
                <span class="text-muted fst-italic">Absent</span>
                <?php else: ?>
                <span class="fw-bold <?= $sur20 >= 10 ? 'text-success' : 'text-danger' ?>">
                    <?= number_format($n['note'], 1) ?>
                </span>
                <?php endif; ?>
            </td>
            <td class="text-center">
                <?= $sur20 !== null ? '<span class="badge bg-'.($sur20>=10?'success':'danger').'">'.$sur20.'</span>' : '—' ?>
            </td>
            <td class="text-center">
                <?php if ($absent): ?>
                    <span class="badge bg-danger">Absent</span>
                <?php elseif (($n['statut'] ?? '') === 'retard'): ?>
                    <span class="badge bg-warning text-dark">Retard</span>
                <?php else: ?>
                    <span class="badge bg-success">Présent</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
