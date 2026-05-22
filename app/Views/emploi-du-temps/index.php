<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Emploi du temps</li></ol>

<div class="page-header">
    <h1><i class="bi bi-calendar3-week-fill text-primary me-2"></i>Emploi du temps <?= $annee ? '<span class="text-muted fs-6 fw-normal">— '.e($annee['libelle']).'</span>' : '' ?></h1>
</div>

<?php if (!$annee): ?>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Aucune année scolaire en cours.</div>
<?php elseif (empty($classes)): ?>
<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Aucune classe créée pour cette année. <a href="<?= url('/classes/creer') ?>">Créer des classes</a>.</div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($classes as $c): ?>
    <div class="col-md-4 col-lg-3">
        <a href="<?= url('/emploi-du-temps/'.$c['id']) ?>" class="text-decoration-none">
            <div class="stat-card h-100 justify-content-start flex-column align-items-start gap-2">
                <div class="stat-icon blue"><i class="bi bi-calendar3-week-fill"></i></div>
                <div>
                    <div class="fw-bold fs-6"><?= e($c['nom']) ?></div>
                    <div class="text-muted small">Voir l'emploi du temps</div>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
