<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Annonces</li></ol>

<div class="page-header">
    <h1><i class="bi bi-megaphone-fill text-primary me-2"></i>Annonces</h1>
    <?php if (can('communication.creer')): ?>
    <a href="<?= url('/annonces/creer') ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nouvelle annonce</a>
    <?php endif; ?>
</div>

<?php if (empty($annonces)): ?>
<div class="text-center py-5 text-muted">
    <i class="bi bi-megaphone fs-1 d-block mb-2 opacity-25"></i>
    Aucune annonce publiée
</div>
<?php else: ?>
<div class="d-flex flex-column gap-3">
    <?php foreach ($annonces as $a): ?>
    <div class="form-card">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <?php
                    $prioColors = ['haute'=>'danger','normale'=>'primary','basse'=>'secondary'];
                    $color = $prioColors[$a['priorite']] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?= $color ?> bg-opacity-15 text-<?= $color ?>"><?= e(ucfirst($a['priorite'])) ?></span>
                    <?php if ($a['classe_cible']): ?>
                    <span class="badge bg-light text-dark border"><i class="bi bi-people me-1"></i><?= e($a['classe_cible']) ?></span>
                    <?php else: ?>
                    <span class="badge bg-light text-dark border">Tous</span>
                    <?php endif; ?>
                    <span class="text-muted small"><?= dateFormat($a['created_at']) ?></span>
                </div>
                <h6 class="fw-bold mb-1"><a href="<?= url('/annonces/'.$a['id']) ?>" class="text-decoration-none text-dark"><?= e($a['titre']) ?></a></h6>
                <p class="text-muted small mb-0"><?= e(mb_substr(strip_tags($a['contenu']), 0, 200)).(mb_strlen($a['contenu'])>200?'…':'') ?></p>
            </div>
            <div class="text-muted small text-nowrap">
                Par <?= e(($a['auteur_prenom']??'').' '.($a['auteur_nom']??'')) ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
