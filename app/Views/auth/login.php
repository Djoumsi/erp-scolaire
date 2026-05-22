<?php if (isset($error) || \App\Core\Session::hasFlash('error')): ?>
<div class="alert alert-danger">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <?= e(\App\Core\Session::flash('error') ?? $error ?? '') ?>
</div>
<?php endif; ?>

<?php if (\App\Core\Session::hasFlash('success')): ?>
<div class="alert alert-success">
    <i class="bi bi-check-circle-fill me-2"></i>
    <?= e(\App\Core\Session::flash('success')) ?>
</div>
<?php endif; ?>

<form method="POST" action="<?= url('/login') ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Identifiant</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="login" class="form-control" placeholder="Votre identifiant"
                   value="<?= old('login') ?>" required autofocus>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label d-flex justify-content-between">
            <span>Mot de passe</span>
            <a href="<?= url('/mot-de-passe') ?>" class="small text-primary">Oublié ?</a>
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" id="passwordField" class="form-control" placeholder="••••••••" required>
            <button type="button" class="input-group-text" onclick="togglePassword()">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
    </button>
</form>

<script>
function togglePassword() {
    const f = document.getElementById('passwordField');
    const i = document.getElementById('eyeIcon');
    if (f.type === 'password') {
        f.type = 'text';
        i.className = 'bi bi-eye-slash';
    } else {
        f.type = 'password';
        i.className = 'bi bi-eye';
    }
}
</script>
