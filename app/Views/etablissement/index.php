<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Établissements</li></ol>

<div class="page-header">
    <h1><i class="bi bi-building-fill text-primary me-2"></i>Établissements</h1>
    <?php if (can('etablissements.creer')): ?>
    <a href="<?= url('/etablissements/creer') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nouvel établissement
    </a>
    <?php endif; ?>
</div>

<div class="table-card">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Établissement</th>
                <th>Type</th>
                <th>Code</th>
                <th>Contact</th>
                <th>Élèves</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($etablissements)): ?>
        <tr><td colspan="7" class="text-center py-5 text-muted">
            <i class="bi bi-building fs-1 d-block mb-2 opacity-25"></i>
            Aucun établissement enregistré
        </td></tr>
        <?php else: ?>
        <?php foreach ($etablissements as $e): ?>
        <tr>
            <td>
                <?php if (!empty($e['logo'])): ?>
                <img src="<?= url($e['logo']) ?>" alt="" style="width:32px;height:32px;object-fit:contain;margin-right:.5rem;">
                <?php endif; ?>
                <span class="fw-semibold"><?= e($e['nom']) ?></span>
            </td>
            <td><span class="badge bg-light text-dark border"><?= e(ucfirst($e['type'])) ?></span></td>
            <td class="text-muted small"><?= e($e['code_etablissement'] ?? '—') ?></td>
            <td class="small"><?= e($e['telephone'] ?? '') ?><?= !empty($e['email']) ? '<br><span class="text-muted">' . e($e['email']) . '</span>' : '' ?></td>
            <td><span class="badge bg-primary bg-opacity-10 text-primary"><?= (int)($e['nb_eleves'] ?? 0) ?></span></td>
            <td>
                <?php if ($e['actif']): ?>
                <span class="badge bg-success bg-opacity-15 text-success">Actif</span>
                <?php else: ?>
                <span class="badge bg-secondary bg-opacity-15 text-secondary">Inactif</span>
                <?php endif; ?>
            </td>
            <td>
                <div class="d-flex gap-1">
                    <a href="<?= url('/etablissements/' . $e['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Voir">
                        <i class="bi bi-eye"></i>
                    </a>
                    <?php if (can('etablissements.modifier')): ?>
                    <a href="<?= url('/etablissements/' . $e['id'] . '/modifier') ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (can('etablissements.supprimer') && $e['actif']): ?>
                    <form method="POST" action="<?= url('/etablissements/' . $e['id'] . '/supprimer') ?>" onsubmit="return confirm('Désactiver cet établissement ?')">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-danger" title="Désactiver"><i class="bi bi-slash-circle"></i></button>
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
