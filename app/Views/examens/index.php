<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Examens</li></ol>

<div class="page-header">
    <h1><i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Examens <?= $annee ? '<span class="text-muted fs-6 fw-normal">— '.e($annee['libelle']).'</span>' : '' ?></h1>
    <?php if (can('examens.creer')): ?>
    <a href="<?= url('/examens/creer') ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Créer un examen</a>
    <?php endif; ?>
</div>

<div class="table-card">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr><th>Examen</th><th>Type</th><th>Période</th><th>Dates</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php if (empty($examens)): ?>
        <tr><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-journal-bookmark fs-1 d-block mb-2 opacity-25"></i>Aucun examen planifié</td></tr>
        <?php else: ?>
        <?php foreach ($examens as $ex): ?>
        <tr>
            <td class="fw-semibold"><?= e($ex['nom']) ?></td>
            <td><?= statutBadge($ex['type'], ['interne'=>'primary','baccalaureat'=>'warning','brevet'=>'info','concours'=>'purple']) ?></td>
            <td class="text-muted small"><?= e($ex['periode_id'] ? 'Période '.$ex['periode_id'] : '—') ?></td>
            <td class="small"><?= dateFormat($ex['date_debut']) ?> → <?= dateFormat($ex['date_fin']) ?></td>
            <td>
                <a href="<?= url('/examens/'.$ex['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Voir le planning"><i class="bi bi-eye"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
