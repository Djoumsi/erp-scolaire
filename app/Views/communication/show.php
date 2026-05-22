<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/annonces') ?>">Annonces</a></li><li class="breadcrumb-item active"><?= e($annonce['titre']) ?></li></ol>

<div class="page-header">
    <h1><i class="bi bi-megaphone-fill text-primary me-2"></i><?= e($annonce['titre']) ?></h1>
</div>

<div class="form-card">
    <div class="d-flex align-items-center gap-2 mb-3 text-muted small">
        <span>Par <strong><?= e(($annonce['auteur_prenom']??'').' '.($annonce['auteur_nom']??'')) ?></strong></span>
        <span>•</span>
        <span><?= dateFormat($annonce['created_at']) ?></span>
        <?php if ($annonce['expire_le']): ?>
        <span>•</span><span>Expire le <?= dateFormat($annonce['expire_le']) ?></span>
        <?php endif; ?>
    </div>
    <div class="border-top pt-3" style="white-space:pre-wrap;"><?= e($annonce['contenu']) ?></div>
</div>

<div class="mt-3">
    <a href="<?= url('/annonces') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
</div>
