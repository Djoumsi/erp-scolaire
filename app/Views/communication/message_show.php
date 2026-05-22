<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/messages') ?>">Messagerie</a></li>
    <li class="breadcrumb-item active"><?= e($message['sujet'] ?: '(Sans sujet)') ?></li>
</ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-envelope-open-fill text-primary me-2"></i><?= e($message['sujet'] ?: '(Sans sujet)') ?></h1>
        <p class="text-muted mb-0 small">
            De <strong><?= e($message['exp_prenom'].' '.$message['exp_nom']) ?></strong>
            à <strong><?= e($message['dest_prenom'].' '.$message['dest_nom']) ?></strong>
            — <?= dateTimeFormat($message['created_at']) ?>
        </p>
    </div>
    <a href="<?= url('/messages') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
</div>

<!-- Message principal -->
<div class="form-card mb-4">
    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
        <div class="avatar-initials" style="width:44px;height:44px;font-size:.9rem;flex-shrink:0;">
            <?= strtoupper(substr($message['exp_prenom'],0,1).substr($message['exp_nom'],0,1)) ?>
        </div>
        <div>
            <div class="fw-semibold"><?= e($message['exp_prenom'].' '.$message['exp_nom']) ?></div>
            <div class="text-muted small"><?= dateTimeFormat($message['created_at']) ?></div>
        </div>
    </div>
    <div style="white-space:pre-wrap; line-height:1.7;"><?= e($message['contenu']) ?></div>
</div>

<!-- Réponses -->
<?php if (!empty($reponses)): ?>
<div class="mb-4">
    <h6 class="fw-semibold text-muted small text-uppercase mb-3">
        <i class="bi bi-reply-all me-1"></i>Réponses (<?= count($reponses) ?>)
    </h6>
    <?php foreach ($reponses as $r): ?>
    <div class="form-card mb-2 <?= $r['expediteur_id'] == \App\Core\Auth::id() ? 'border-primary border-opacity-25' : '' ?>">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="avatar-initials" style="width:32px;height:32px;font-size:.7rem;flex-shrink:0;">
                <?= strtoupper(substr($r['exp_prenom'],0,1).substr($r['exp_nom'],0,1)) ?>
            </div>
            <div>
                <span class="fw-medium small"><?= e($r['exp_prenom'].' '.$r['exp_nom']) ?></span>
                <span class="text-muted small ms-2"><?= dateTimeFormat($r['created_at']) ?></span>
                <?php if ($r['expediteur_id'] == \App\Core\Auth::id()): ?>
                <span class="badge bg-primary bg-opacity-15 text-primary ms-1">Vous</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="ms-5 small" style="white-space:pre-wrap;"><?= e($r['contenu']) ?></div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Formulaire de réponse -->
<div class="form-card">
    <div class="form-section-title"><i class="bi bi-reply me-1"></i>Répondre</div>
    <form method="POST" action="<?= url('/messages/envoyer') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="destinataire_id" value="<?= $message['expediteur_id'] == \App\Core\Auth::id() ? $message['destinataire_id'] : $message['expediteur_id'] ?>">
        <input type="hidden" name="parent_id" value="<?= $message['id'] ?>">
        <input type="hidden" name="sujet" value="Re: <?= e($message['sujet'] ?: '') ?>">
        <div class="mb-3">
            <label class="form-label text-muted small">
                À : <strong>
                <?php
                $destId = $message['expediteur_id'] == \App\Core\Auth::id() ? $message['destinataire_id'] : $message['expediteur_id'];
                echo $message['expediteur_id'] == \App\Core\Auth::id()
                    ? e($message['dest_prenom'].' '.$message['dest_nom'])
                    : e($message['exp_prenom'].' '.$message['exp_nom']);
                ?>
                </strong>
            </label>
            <textarea name="contenu" class="form-control" rows="4" required placeholder="Votre réponse…"></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Envoyer la réponse</button>
            <a href="<?= url('/messages') ?>" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
