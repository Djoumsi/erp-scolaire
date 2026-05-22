<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item active">Élèves</li>
</ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-people-fill text-primary me-2"></i>Élèves</h1>
        <?php if ($annee): ?>
        <small class="text-muted">Année scolaire : <?= e($annee['libelle']) ?></small>
        <?php endif; ?>
    </div>
    <?php if (can('eleves.creer')): ?>
    <a href="<?= url('/eleves/inscrire') ?>" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i>Inscrire un élève
    </a>
    <?php endif; ?>
</div>

<!-- Filtres -->
<div class="table-card mb-3">
    <div class="p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label mb-1 small fw-medium">Rechercher</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Nom, prénom, matricule…" value="<?= e($search) ?>">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1 small fw-medium">Classe</label>
                <select name="classe" class="form-select">
                    <option value="">Toutes les classes</option>
                    <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $classeId == $c['id'] ? 'selected' : '' ?>><?= e($c['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filtrer</button>
            </div>
            <?php if ($search || $classeId): ?>
            <div class="col-md-2">
                <a href="<?= url('/eleves') ?>" class="btn btn-outline-secondary w-100">Réinitialiser</a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Tableau élèves -->
<div class="table-card">
    <div class="table-card-header">
        <span class="text-muted small"><?= number_format($pagination['total']) ?> élève(s)</span>
        <?php if (can('eleves.exporter')): ?>
        <a href="<?= url('/eleves/export/excel') ?>" class="btn btn-sm btn-outline-success">
            <i class="bi bi-file-earmark-excel me-1"></i>Exporter
        </a>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40"><input type="checkbox" class="form-check-input"></th>
                    <th>Élève</th>
                    <th>Matricule</th>
                    <th>Classe</th>
                    <th>Sexe</th>
                    <th>Statut</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($eleves)): ?>
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-2"></i>
                        Aucun élève trouvé
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($eleves as $eleve): ?>
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <?php if (!empty($eleve['photo'])): ?>
                            <img src="<?= url($eleve['photo']) ?>" class="avatar-sm" alt="">
                            <?php else: ?>
                            <div class="avatar-initials" style="width:32px;height:32px;font-size:.7rem">
                                <?= strtoupper(substr($eleve['nom'], 0, 1) . substr($eleve['prenoms'], 0, 1)) ?>
                            </div>
                            <?php endif; ?>
                            <div>
                                <div class="fw-medium"><?= e(strtoupper($eleve['nom']) . ' ' . $eleve['prenoms']) ?></div>
                                <?php if (!empty($eleve['parent1_telephone'])): ?>
                                <div class="text-muted" style="font-size:.75rem"><i class="bi bi-telephone me-1"></i><?= e($eleve['parent1_telephone']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><code><?= e($eleve['matricule']) ?></code></td>
                    <td><?= e($eleve['classe_nom'] ?? '—') ?></td>
                    <td><?= $eleve['sexe'] === 'M' ? '<span class="badge bg-info">M</span>' : '<span class="badge bg-pink" style="background:#ec4899">F</span>' ?></td>
                    <td><?= statutBadge($eleve['statut'] ?? 'actif') ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?= url('/eleves/' . $eleve['id']) ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Voir le dossier">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if (can('eleves.modifier')): ?>
                            <a href="<?= url('/eleves/' . $eleve['id'] . '/modifier') ?>" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (can('paiements.voir') && $eleve['inscription_id']): ?>
                            <a href="<?= url('/paiements/encaisser/' . $eleve['inscription_id']) ?>" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Paiement">
                                <i class="bi bi-cash"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pagination['last_page'] > 1): ?>
    <div class="p-3 border-top d-flex justify-content-between align-items-center">
        <small class="text-muted">Page <?= $pagination['current_page'] ?> / <?= $pagination['last_page'] ?></small>
        <?= paginationLinks($pagination, url('/eleves') . '?q=' . urlencode($search) . '&classe=' . $classeId) ?>
    </div>
    <?php endif; ?>
</div>
