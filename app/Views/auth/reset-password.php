<?php if (\App\Core\Session::hasFlash('error')): ?>
<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= e(\App\Core\Session::flash('error')) ?></div>
<?php endif; ?>
<?php if (\App\Core\Session::hasFlash('success')): ?>
<div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><?= e(\App\Core\Session::flash('success')) ?></div>
<?php endif; ?>

<form method="POST" action="<?= url('/mot-de-passe/reinitialiser') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="token" value="<?= e($token ?? '') ?>">
    <div class="mb-4">
        <label class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="nouveau_mdp" class="form-control" placeholder="Min. 8 caractères" required minlength="8" autofocus>
        </div>
    </div>
    <div class="mb-4">
        <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="confirmation_mdp" class="form-control" placeholder="Répétez le mot de passe" required minlength="8">
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
        <i class="bi bi-shield-check me-2"></i>Enregistrer le nouveau mot de passe
    </button>
    <div class="text-center mt-3">
        <a href="<?= url('/login') ?>" style="color:#60a5fa;font-size:.85rem;text-decoration:none;">
            <i class="bi bi-arrow-left me-1"></i>Retour à la connexion
        </a>
    </div>
</form>
