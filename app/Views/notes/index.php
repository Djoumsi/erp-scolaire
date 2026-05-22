<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Notes</li></ol>
<div class="page-header">
    <h1><i class="bi bi-pencil-square text-warning me-2"></i>Notes</h1>
    <?php if (can('notes.voir') && !empty($cours)): ?>
    <div class="dropdown">
        <button class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-table me-1"></i>Vue par classe
        </button>
        <ul class="dropdown-menu">
            <?php
            $classes_vues = [];
            foreach ($cours as $c):
                if (!isset($classes_vues[$c['classe_id'] ?? $c['id']])):
                    $classes_vues[$c['classe_id'] ?? $c['id']] = true;
            ?>
            <li><a class="dropdown-item" href="<?= url('/notes/classe/'.($c['classe_id'] ?? $c['id'])) ?>">
                <?= e($c['classe_nom']) ?>
            </a></li>
            <?php endif; endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

<!-- Sélecteur de période -->
<div class="d-flex gap-2 mb-3 flex-wrap align-items-center">
    <span class="text-muted small fw-medium">Période :</span>
    <?php foreach ($periodes as $p): ?>
    <a href="?periode=<?= $p['id'] ?>"
       class="btn btn-sm <?= $p['id'] == $periodeActif ? 'btn-primary' : 'btn-outline-primary' ?>">
        <?= e($p['nom']) ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="table-card">
    <div class="table-card-header"><h6 class="mb-0 fw-semibold">Cours <?= $annee ? '— ' . e($annee['libelle']) : '' ?></h6></div>
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Classe</th>
                <th>Matière</th>
                <?php if (\App\Core\Auth::role() !== 'enseignant'): ?><th>Enseignant</th><?php endif; ?>
                <th>Évaluations</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($cours)): ?>
        <tr><td colspan="5" class="text-center py-5 text-muted">Aucun cours assigné</td></tr>
        <?php else: ?>
        <?php foreach ($cours as $c): ?>
        <tr>
            <td class="fw-medium"><?= e($c['classe_nom']) ?></td>
            <td><?= e($c['matiere_nom']) ?></td>
            <?php if (\App\Core\Auth::role() !== 'enseignant'): ?>
            <td><?= e(($c['prof_prenom'] ?? '') . ' ' . ($c['prof_nom'] ?? '')) ?></td>
            <?php endif; ?>
            <td><?= isset($c['nb_evaluations']) ? '<span class="badge bg-light text-dark border">' . $c['nb_evaluations'] . ' éval.</span>' : '—' ?></td>
            <td>
                <a href="<?= url('/notes/saisir/' . $c['id']) ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil me-1"></i>Saisir les notes
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
