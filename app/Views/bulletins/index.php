<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Bulletins</li></ol>

<div class="page-header">
    <h1><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Bulletins <?= $annee ? '<span class="text-muted fs-6 fw-normal">— '.e($annee['libelle']).'</span>' : '' ?></h1>
</div>

<?php if (!$annee): ?>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Aucune année scolaire en cours.</div>
<?php elseif (empty($periodes)): ?>
<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Aucune période définie. <a href="<?= url('/parametres') ?>">Configurer les paramètres</a>.</div>
<?php elseif (empty($classes)): ?>
<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Aucune classe pour cette année.</div>
<?php else: ?>

<!-- Générer bulletins -->
<?php if (can('bulletins.generer')): ?>
<div class="form-card mb-4">
    <div class="form-section-title">Générer les bulletins</div>
    <form method="POST" action="<?= url('/bulletins/generer') ?>" class="row g-3 align-items-end">
        <?= csrf_field() ?>
        <div class="col-md-4">
            <label class="form-label">Classe</label>
            <select name="classe_id" class="form-select" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($classes as $c): ?>
                <option value="<?= $c['id'] ?>"><?= e($c['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Période</label>
            <select name="periode_id" class="form-select" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($periodes as $p): ?>
                <option value="<?= $p['id'] ?>"><?= e($p['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Calculer les moyennes et générer les bulletins pour cette classe ?')">
                <i class="bi bi-gear me-1"></i>Générer les bulletins
            </button>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Liste des classes -->
<div class="row g-3">
    <?php foreach ($classes as $c): ?>
    <div class="col-md-4 col-lg-3">
        <div class="form-card h-100">
            <div class="fw-bold mb-1"><?= e($c['nom']) ?></div>
            <div class="text-muted small mb-3"><?= e($c['niveau_nom']) ?> — <?= (int)$c['effectif'] ?> élève(s)</div>
            <div class="d-flex flex-column gap-2">
                <?php foreach ($periodes as $p): ?>
                <a href="<?= url('/bulletins/classe/'.$c['id'].'?periode='.$p['id']) ?>"
                   class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-between">
                    <span><?= e($p['nom']) ?></span>
                    <i class="bi bi-arrow-right"></i>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
