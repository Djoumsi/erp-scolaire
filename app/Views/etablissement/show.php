<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/etablissements') ?>">Établissements</a></li>
    <li class="breadcrumb-item active"><?= e($etab['nom']) ?></li>
</ol>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <?php if (!empty($etab['logo'])): ?>
        <img src="<?= url($etab['logo']) ?>" alt="" style="height:48px;object-fit:contain;">
        <?php else: ?>
        <div class="stat-icon blue"><i class="bi bi-building-fill"></i></div>
        <?php endif; ?>
        <div>
            <h1 class="mb-0"><?= e($etab['nom']) ?></h1>
            <span class="text-muted small"><?= e(ucfirst($etab['type'])) ?> — <?= e($etab['pays'] ?? '') ?></span>
        </div>
    </div>
    <?php if (can('etablissements.modifier')): ?>
    <a href="<?= url('/etablissements/' . $etab['id'] . '/modifier') ?>" class="btn btn-primary">
        <i class="bi bi-pencil me-1"></i>Modifier
    </a>
    <?php endif; ?>
</div>

<div class="row g-4">
    <!-- Infos -->
    <div class="col-lg-4">
        <div class="form-card h-100">
            <div class="form-section-title">Informations</div>
            <dl class="row mb-0 small">
                <dt class="col-5 text-muted">Code</dt>
                <dd class="col-7"><?= e($etab['code_etablissement'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Adresse</dt>
                <dd class="col-7"><?= e($etab['adresse'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Téléphone</dt>
                <dd class="col-7"><?= e($etab['telephone'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Email</dt>
                <dd class="col-7"><?= e($etab['email'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Site web</dt>
                <dd class="col-7"><?= $etab['site_web'] ? '<a href="' . e($etab['site_web']) . '" target="_blank">' . e($etab['site_web']) . '</a>' : '—' ?></dd>
                <dt class="col-5 text-muted">Devise</dt>
                <dd class="col-7"><?= e($etab['devise'] ?? 'XOF') ?></dd>
                <dt class="col-5 text-muted">Statut</dt>
                <dd class="col-7"><?= $etab['actif'] ? '<span class="badge bg-success bg-opacity-15 text-success">Actif</span>' : '<span class="badge bg-secondary bg-opacity-15 text-secondary">Inactif</span>' ?></dd>
            </dl>
        </div>
    </div>

    <!-- Années scolaires -->
    <div class="col-lg-8">
        <div class="table-card mb-4">
            <div class="table-card-header">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar3 me-2 text-primary"></i>Années scolaires</h6>
            </div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light">
                    <tr><th>Libellé</th><th>Début</th><th>Fin</th><th>Statut</th></tr>
                </thead>
                <tbody>
                <?php if (empty($annees)): ?>
                <tr><td colspan="4" class="text-center py-3 text-muted">Aucune année scolaire</td></tr>
                <?php else: ?>
                <?php foreach ($annees as $a): ?>
                <tr>
                    <td class="fw-medium"><?= e($a['libelle']) ?></td>
                    <td><?= dateFormat($a['date_debut']) ?></td>
                    <td><?= dateFormat($a['date_fin']) ?></td>
                    <td><?= $a['en_cours'] ? '<span class="badge bg-success bg-opacity-15 text-success">En cours</span>' : '<span class="badge bg-light text-muted border">Terminée</span>' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Utilisateurs -->
        <div class="table-card">
            <div class="table-card-header">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-people-fill me-2 text-primary"></i>Utilisateurs</h6>
            </div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light">
                    <tr><th>Nom</th><th>Login</th><th>Rôle</th><th>Statut</th></tr>
                </thead>
                <tbody>
                <?php if (empty($users)): ?>
                <tr><td colspan="4" class="text-center py-3 text-muted">Aucun utilisateur</td></tr>
                <?php else: ?>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= e($u['prenoms'] . ' ' . $u['nom']) ?></td>
                    <td class="text-muted"><?= e($u['login']) ?></td>
                    <td><span class="badge bg-light text-dark border"><?= e($u['role_label']) ?></span></td>
                    <td><?= $u['actif'] ? '<span class="badge bg-success bg-opacity-15 text-success">Actif</span>' : '<span class="badge bg-danger bg-opacity-15 text-danger">Inactif</span>' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
