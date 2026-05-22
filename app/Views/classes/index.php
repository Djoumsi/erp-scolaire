<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Classes</li></ol>

<div class="page-header">
    <h1><i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>Classes <?= $annee ? '<span class="text-muted fs-6 fw-normal">— '.e($annee['libelle']).'</span>' : '' ?></h1>
    <?php if (can('classes.creer')): ?>
    <a href="<?= url('/classes/creer') ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Créer une classe</a>
    <?php endif; ?>
</div>

<?php if (!$annee): ?>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Aucune année scolaire en cours. <a href="<?= url('/parametres') ?>">Configurer les paramètres</a>.</div>
<?php endif; ?>

<div class="table-card">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr><th>Classe</th><th>Cycle / Niveau</th><th>Titulaire</th><th>Effectif</th><th>Max</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php if (empty($classes)): ?>
        <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-grid-3x3-gap fs-1 d-block mb-2 opacity-25"></i>Aucune classe créée</td></tr>
        <?php else: ?>
        <?php foreach ($classes as $c): ?>
        <tr>
            <td class="fw-semibold"><?= e($c['nom']) ?></td>
            <td><span class="text-muted small"><?= e($c['cycle_nom']) ?></span> / <?= e($c['niveau_nom']) ?></td>
            <td class="small"><?= !empty($c['titulaire_nom']) ? e(($c['titulaire_prenom']??'').' '.($c['titulaire_nom']??'')) : '<span class="text-muted">—</span>' ?></td>
            <td>
                <span class="badge <?= ($c['effectif'] >= $c['effectif_max']) ? 'bg-danger' : 'bg-primary' ?> bg-opacity-15 <?= ($c['effectif'] >= $c['effectif_max']) ? 'text-danger' : 'text-primary' ?>">
                    <?= (int)$c['effectif'] ?>
                </span>
            </td>
            <td class="text-muted"><?= (int)$c['effectif_max'] ?></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="<?= url('/classes/'.$c['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Voir"><i class="bi bi-eye"></i></a>
                    <?php if (can('classes.modifier')): ?>
                    <a href="<?= url('/classes/'.$c['id'].'/modifier') ?>" class="btn btn-sm btn-outline-primary" title="Modifier"><i class="bi bi-pencil"></i></a>
                    <?php endif; ?>
                    <?php if (can('classes.supprimer')): ?>
                    <form method="POST" action="<?= url('/classes/'.$c['id'].'/supprimer') ?>" onsubmit="return confirm('Supprimer cette classe ?')">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
