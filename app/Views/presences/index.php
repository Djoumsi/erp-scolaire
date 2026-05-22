<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Présences</li></ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-clipboard2-check-fill text-info me-2"></i>Présences</h1>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <a href="<?= url('/presences/rapport') ?>" class="btn btn-outline-info btn-sm">
            <i class="bi bi-bar-chart-fill me-1"></i>Rapport absentéisme
        </a>
    </div>
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Date :</label>
        <input type="date" name="date" class="form-control w-auto" value="<?= e($date) ?>">
        <button class="btn btn-outline-primary btn-sm">Filtrer</button>
    </form>
</div>

<div class="table-card">
    <div class="table-card-header">
        <h6 class="mb-0 fw-semibold">Cours du <?= dateFormat($date, 'l d F Y') ?></h6>
    </div>
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Classe</th>
                <th>Matière</th>
                <?php if (\App\Core\Auth::role() !== 'enseignant'): ?><th>Enseignant</th><?php endif; ?>
                <th>Séances</th>
                <th>Absences</th>
                <th>Appel</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($cours)): ?>
        <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-calendar-x fs-2 d-block mb-2"></i>Aucun cours enregistré pour ce jour</td></tr>
        <?php else: ?>
        <?php foreach ($cours as $c): ?>
        <tr>
            <td class="fw-medium"><?= e($c['classe_nom']) ?></td>
            <td><?= e($c['matiere_nom']) ?></td>
            <?php if (\App\Core\Auth::role() !== 'enseignant'): ?>
            <td><?= e(($c['prof_prenom'] ?? '') . ' ' . ($c['prof_nom'] ?? '')) ?></td>
            <?php endif; ?>
            <td><?= $c['nb_seances'] ?? '—' ?></td>
            <td><?php
                $abs = $c['nb_absences'] ?? 0;
                echo $abs > 0 ? "<span class='badge bg-danger'>$abs</span>" : '<span class="text-muted">0</span>';
            ?></td>
            <td><?= ($c['appel_fait'] ?? 0) ? '<span class="badge bg-success">Fait</span>' : '<span class="badge bg-warning text-dark">Non fait</span>' ?></td>
            <td>
                <a href="<?= url('/presences/appel/' . $c['affectation_id']) ?>?date=<?= $date ?>"
                   class="btn btn-sm <?= ($c['appel_fait'] ?? 0) ? 'btn-outline-secondary' : 'btn-primary' ?>">
                    <i class="bi bi-clipboard2-check me-1"></i>
                    <?= ($c['appel_fait'] ?? 0) ? 'Voir' : 'Faire l\'appel' ?>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
