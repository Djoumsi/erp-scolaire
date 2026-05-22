<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/etablissements') ?>">Établissements</a></li>
    <li class="breadcrumb-item active">Nouvel établissement</li>
</ol>

<div class="page-header">
    <h1><i class="bi bi-building-add text-primary me-2"></i>Nouvel établissement</h1>
</div>

<form method="POST" action="<?= url('/etablissements') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Informations générales -->
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-section-title">Informations générales</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nom de l'établissement <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" value="<?= old('nom') ?>" required placeholder="Ex: Lycée Moderne de Cocody">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <option value="primaire"      <?= old('type') === 'primaire'     ? 'selected' : '' ?>>Primaire</option>
                            <option value="college"       <?= old('type') === 'college'      ? 'selected' : '' ?>>Collège</option>
                            <option value="lycee"         <?= old('type') === 'lycee'        ? 'selected' : '' ?>>Lycée</option>
                            <option value="universite"    <?= old('type') === 'universite'   ? 'selected' : '' ?>>Université</option>
                            <option value="formation"     <?= old('type') === 'formation'    ? 'selected' : '' ?>>Centre de formation</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Code établissement</label>
                        <input type="text" name="code_etablissement" class="form-control" value="<?= old('code_etablissement') ?>" placeholder="Ex: LMC-001">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control" value="<?= old('adresse') ?>" placeholder="Adresse complète">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="<?= old('telephone') ?>" placeholder="+225 07 00 00 00 00">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="contact@etablissement.ci">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Site web</label>
                        <input type="url" name="site_web" class="form-control" value="<?= old('site_web') ?>" placeholder="https://...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Devise</label>
                        <select name="devise" class="form-select">
                            <option value="XOF" <?= (old('devise','XOF') === 'XOF') ? 'selected' : '' ?>>XOF (FCFA)</option>
                            <option value="EUR" <?= old('devise') === 'EUR' ? 'selected' : '' ?>>EUR (€)</option>
                            <option value="USD" <?= old('devise') === 'USD' ? 'selected' : '' ?>>USD ($)</option>
                            <option value="MAD" <?= old('devise') === 'MAD' ? 'selected' : '' ?>>MAD (DH)</option>
                            <option value="GNF" <?= old('devise') === 'GNF' ? 'selected' : '' ?>>GNF</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Pays</label>
                        <input type="text" name="pays" class="form-control" value="<?= old('pays', "Côte d'Ivoire") ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Logo -->
        <div class="col-lg-4">
            <div class="form-card">
                <div class="form-section-title">Logo</div>
                <div class="text-center">
                    <div id="logoPreview" class="mb-3 d-flex align-items-center justify-content-center border rounded" style="height:140px;background:#f8fafc;">
                        <i class="bi bi-image fs-1 text-muted"></i>
                    </div>
                    <input type="file" name="logo" id="logoInput" class="form-control form-control-sm" accept="image/*">
                    <small class="text-muted">JPG, PNG, SVG — max 2 Mo</small>
                </div>
            </div>
        </div>

        <!-- Compte admin -->
        <div class="col-12">
            <div class="form-card">
                <div class="form-section-title">Compte administrateur (optionnel)</div>
                <p class="text-muted small mb-3">Créez un compte admin pour cet établissement. Laissez vide pour le faire plus tard.</p>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="admin_prenom" class="form-control" value="<?= old('admin_prenom') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="admin_nom" class="form-control" value="<?= old('admin_nom') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Login</label>
                        <input type="text" name="admin_login" class="form-control" value="<?= old('admin_login') ?>" autocomplete="off">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="admin_password" class="form-control" autocomplete="new-password">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Email admin</label>
                        <input type="email" name="admin_email" class="form-control" value="<?= old('admin_email') ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-lg me-1"></i>Créer l'établissement
        </button>
        <a href="<?= url('/etablissements') ?>" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>

<script>
document.getElementById('logoInput').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('logoPreview').innerHTML = '<img src="' + e.target.result + '" style="max-height:130px;max-width:100%;object-fit:contain;">';
    };
    reader.readAsDataURL(file);
});
</script>
