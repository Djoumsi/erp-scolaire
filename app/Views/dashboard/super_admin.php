<div class="page-header">
    <h1>Tableau de bord — Super Admin</h1>
    <a href="<?= url('/etablissements/creer') ?>" class="btn btn-primary"><i class="bi bi-plus me-1"></i>Nouvel établissement</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-building-fill"></i></div>
            <div><div class="stat-value"><?= $total_etablissements ?></div><div class="stat-label">Établissements actifs</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-value"><?= number_format($total_users) ?></div><div class="stat-label">Utilisateurs</div></div>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="table-card-header"><h6 class="mb-0 fw-semibold">Établissements</h6></div>
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr><th>Établissement</th><th>Type</th><th>Élèves inscrits</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($etablissements as $e): ?>
        <tr>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <?php if ($e['logo']): ?><img src="<?= url($e['logo']) ?>" style="height:28px" alt=""><?php endif; ?>
                    <span class="fw-medium"><?= e($e['nom']) ?></span>
                </div>
            </td>
            <td><span class="badge bg-secondary"><?= strtoupper($e['type']) ?></span></td>
            <td><?= number_format($e['nb_inscrits']) ?></td>
            <td>
                <a href="<?= url('/etablissements/' . $e['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                <a href="<?= url('/etablissements/' . $e['id'] . '/modifier') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
