<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Mon profil</li></ol>

<div class="page-header">
    <h1><i class="bi bi-person-circle text-primary me-2"></i>Mon profil</h1>
</div>

<div class="row g-4">
    <!-- Infos profil -->
    <div class="col-lg-4">
        <div class="form-card text-center">
            <div class="mb-3">
                <?php if (!empty($user['photo'])): ?>
                <img src="<?= url($user['photo']) ?>" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">
                <?php else: ?>
                <div class="avatar-initials mx-auto" style="width:100px;height:100px;font-size:2rem;">
                    <?= strtoupper(substr($user['prenoms'],0,1).substr($user['nom'],0,1)) ?>
                </div>
                <?php endif; ?>
            </div>
            <h5 class="fw-bold"><?= e($user['prenoms'].' '.$user['nom']) ?></h5>
            <p class="text-muted small"><?= e($user['role_nom'] ?? '') ?></p>
            <dl class="row text-start mb-0 small">
                <dt class="col-5 text-muted">Login</dt><dd class="col-7 font-monospace"><?= e($user['login']) ?></dd>
                <dt class="col-5 text-muted">Email</dt><dd class="col-7"><?= e($user['email'] ?? '—') ?></dd>
                <dt class="col-5 text-muted">Téléphone</dt><dd class="col-7"><?= e($user['telephone'] ?? '—') ?></dd>
            </dl>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Modifier infos -->
        <div class="form-card mb-4">
            <div class="form-section-title">Modifier mes informations</div>
            <form method="POST" action="<?= url('/profil') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Prénom(s) <span class="text-danger">*</span></label>
                        <input type="text" name="prenoms" class="form-control" value="<?= old('prenoms', $user['prenoms']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" value="<?= old('nom', $user['nom']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email', $user['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="<?= old('telephone', $user['telephone'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Photo de profil</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Mettre à jour</button>
                </div>
            </form>
        </div>

        <!-- Changer mot de passe -->
        <div class="form-card">
            <div class="form-section-title">Changer le mot de passe</div>
            <form method="POST" action="<?= url('/profil/mot-de-passe') ?>">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                        <input type="password" name="ancien_mdp" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="nouveau_mdp" class="form-control" required minlength="6">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Confirmer <span class="text-danger">*</span></label>
                        <input type="password" name="confirmation_mdp" class="form-control" required>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-warning"><i class="bi bi-shield-lock me-1"></i>Changer le mot de passe</button>
                </div>
            </form>
        </div>
    </div>
</div>
