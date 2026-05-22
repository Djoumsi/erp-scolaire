<?php if (\App\Core\Session::hasFlash('success')): ?>
<div class="alert alert-success"><?= e(\App\Core\Session::flash('success')) ?></div>
<?php endif; ?>

<p class="text-muted small mb-4">Entrez votre email pour recevoir un lien de réinitialisation.</p>

<form method="POST" action="<?= url('/mot-de-passe') ?>">
    <?= csrf_field() ?>
    <div class="mb-4">
        <label class="form-label">Adresse email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="votre@email.com" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2">Envoyer le lien</button>
    <div class="text-center mt-3">
        <a href="<?= url('/login') ?>" class="small text-muted"><i class="bi bi-arrow-left me-1"></i>Retour à la connexion</a>
    </div>
</form>
