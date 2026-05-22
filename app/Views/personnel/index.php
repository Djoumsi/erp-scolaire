<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Personnel</li></ol>

<div class="page-header">
    <h1><i class="bi bi-person-badge-fill text-primary me-2"></i>Personnel</h1>
    <?php if (can('personnel.creer')): ?>
    <a href="<?= url('/personnel/creer') ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajouter</a>
    <?php endif; ?>
</div>

<!-- Filtres -->
<div class="table-card mb-3 p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <input type="text" name="q" class="form-control" placeholder="Rechercher par nom, matricule…" value="<?= e($search) ?>">
        </div>
        <div class="col-md-3">
            <select name="type" class="form-select">
                <option value="">Tous les types</option>
                <?php foreach (['enseignant'=>'Enseignant','administratif'=>'Administratif','direction'=>'Direction','surveillant'=>'Surveillant','autre'=>'Autre'] as $v=>$l): ?>
                <option value="<?= $v ?>" <?= $type === $v ? 'selected' : '' ?>><?= $l ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Filtrer</button>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-card-header d-flex justify-content-between align-items-center">
        <span class="text-muted small"><?= $total ?> membre<?= $total > 1 ? 's' : '' ?> trouvé<?= $total > 1 ? 's' : '' ?></span>
    </div>
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr><th>Matricule</th><th>Nom & Prénom</th><th>Type</th><th>Spécialité</th><th>Contact</th><th>Contrat</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php if (empty($personnel)): ?>
        <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-person-badge fs-1 d-block mb-2 opacity-25"></i>Aucun membre du personnel</td></tr>
        <?php else: ?>
        <?php foreach ($personnel as $p): ?>
        <tr>
            <td><span class="badge bg-light text-dark border font-monospace"><?= e($p['matricule']) ?></span></td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <?php if (!empty($p['photo'])): ?>
                    <img src="<?= url($p['photo']) ?>" class="avatar-sm" alt="">
                    <?php else: ?>
                    <div class="avatar-initials" style="width:32px;height:32px;font-size:.7rem;"><?= strtoupper(substr($p['prenoms'],0,1).substr($p['nom'],0,1)) ?></div>
                    <?php endif; ?>
                    <div>
                        <div class="fw-semibold"><?= e($p['prenoms'].' '.$p['nom']) ?></div>
                        <div class="text-muted small"><?= e($p['email'] ?? '') ?></div>
                    </div>
                </div>
            </td>
            <td><?= statutBadge($p['type'], ['enseignant'=>'primary','administratif'=>'info','direction'=>'warning','surveillant'=>'secondary','autre'=>'light']) ?></td>
            <td class="text-muted small"><?= e($p['specialite'] ?? '—') ?></td>
            <td class="small"><?= e($p['telephone'] ?? '—') ?></td>
            <td><span class="badge bg-light text-dark border"><?= e($p['statut_contrat'] ?? '') ?></span></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="<?= url('/personnel/'.$p['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Voir"><i class="bi bi-eye"></i></a>
                    <?php if (can('personnel.modifier')): ?>
                    <a href="<?= url('/personnel/'.$p['id'].'/modifier') ?>" class="btn btn-sm btn-outline-primary" title="Modifier"><i class="bi bi-pencil"></i></a>
                    <?php endif; ?>
                    <?php if (can('personnel.supprimer')): ?>
                    <form method="POST" action="<?= url('/personnel/'.$p['id'].'/supprimer') ?>" onsubmit="return confirm('Supprimer ce membre du personnel ?')">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                    </form>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <?php if ($totalPages > 1): ?>
    <div class="p-3 border-top">
        <nav>
            <ul class="pagination pagination-sm mb-0 justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page-1 ?>&q=<?= urlencode($search) ?>&type=<?= urlencode($type) ?>">«</a>
                </li>
                <?php for ($i = max(1, $page-2); $i <= min($totalPages, $page+2); $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&q=<?= urlencode($search) ?>&type=<?= urlencode($type) ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page+1 ?>&q=<?= urlencode($search) ?>&type=<?= urlencode($type) ?>">»</a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
