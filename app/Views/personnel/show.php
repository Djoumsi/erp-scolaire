<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/personnel') ?>">Personnel</a></li><li class="breadcrumb-item active"><?= e($personne['prenoms'].' '.$personne['nom']) ?></li></ol>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <?php if (!empty($personne['photo'])): ?>
        <img src="<?= url($personne['photo']) ?>" class="rounded-circle" style="width:56px;height:56px;object-fit:cover;">
        <?php else: ?>
        <div class="avatar-initials" style="width:56px;height:56px;font-size:1.2rem;"><?= strtoupper(substr($personne['prenoms'],0,1).substr($personne['nom'],0,1)) ?></div>
        <?php endif; ?>
        <div>
            <h1 class="mb-0"><?= e($personne['prenoms'].' '.$personne['nom']) ?></h1>
            <span class="text-muted small"><?= e($personne['matricule']) ?> — <?= e(ucfirst($personne['type'])) ?></span>
        </div>
    </div>
    <?php if (can('personnel.modifier')): ?>
    <a href="<?= url('/personnel/'.$personne['id'].'/modifier') ?>" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Modifier</a>
    <?php endif; ?>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="form-card">
            <div class="form-section-title">Informations</div>
            <dl class="row mb-0 small">
                <dt class="col-5 text-muted">Email</dt><dd class="col-7"><?= e($personne['email'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Téléphone</dt><dd class="col-7"><?= e($personne['telephone'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Login</dt><dd class="col-7 font-monospace"><?= e($personne['login'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Type</dt><dd class="col-7"><?= e(ucfirst($personne['type'])) ?></dd>
                <dt class="col-5 text-muted">Contrat</dt><dd class="col-7"><?= e(ucfirst($personne['statut_contrat'] ?? '')) ?></dd>
                <dt class="col-5 text-muted">Spécialité</dt><dd class="col-7"><?= e($personne['specialite'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Diplôme</dt><dd class="col-7"><?= e($personne['diplome'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Embauche</dt><dd class="col-7"><?= $personne['date_embauche'] ? dateFormat($personne['date_embauche']) : '—' ?></dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="table-card">
            <div class="table-card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-journal-bookmark me-2 text-primary"></i>Cours assignés</h6></div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light"><tr><th>Année</th><th>Classe</th><th>Matière</th></tr></thead>
                <tbody>
                <?php if (empty($cours)): ?>
                <tr><td colspan="3" class="text-center py-3 text-muted">Aucun cours assigné</td></tr>
                <?php else: ?>
                <?php foreach ($cours as $c): ?>
                <tr>
                    <td><?= e($c['annee']) ?></td>
                    <td class="fw-medium"><?= e($c['classe_nom']) ?></td>
                    <td><?= e($c['matiere_nom']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
