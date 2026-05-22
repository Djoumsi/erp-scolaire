<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/etablissements') ?>">Établissements</a></li>
    <li class="breadcrumb-item active">Modifier</li>
</ol>

<div class="page-header">
    <h1><i class="bi bi-pencil-square text-primary me-2"></i>Modifier — <?= e($etab['nom']) ?></h1>
</div>

<form method="POST" action="<?= url('/etablissements/' . $etab['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-section-title">Informations générales</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" value="<?= old('nom', $etab['nom']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <?php foreach (['primaire','college','lycee','universite','formation'] as $t): ?>
                            <option value="<?= $t ?>" <?= old('type', $etab['type']) === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Code établissement</label>
                        <input type="text" name="code_etablissement" class="form-control" value="<?= old('code_etablissement', $etab['code_etablissement'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control" value="<?= old('adresse', $etab['adresse'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="<?= old('telephone', $etab['telephone'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email', $etab['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Site web</label>
                        <input type="url" name="site_web" class="form-control" value="<?= old('site_web', $etab['site_web'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Devise</label>
                        <select name="devise" class="form-select">
                            <?php foreach (['XOF','EUR','USD','MAD','GNF'] as $d): ?>
                            <option value="<?= $d ?>" <?= old('devise', $etab['devise'] ?? 'XOF') === $d ? 'selected' : '' ?>><?= $d ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Pays</label>
                        <input type="text" name="pays" class="form-control" value="<?= old('pays', $etab['pays'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card">
                <div class="form-section-title">Logo</div>
                <div class="text-center">
                    <div id="logoPreview" class="mb-3 d-flex align-items-center justify-content-center border rounded" style="height:140px;background:#f8fafc;">
                        <?php if (!empty($etab['logo'])): ?>
                        <img src="<?= url($etab['logo']) ?>" style="max-height:130px;max-width:100%;object-fit:contain;">
                        <?php else: ?>
                        <i class="bi bi-image fs-1 text-muted"></i>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="logo" id="logoInput" class="form-control form-control-sm" accept="image/*">
                    <small class="text-muted">Laisser vide pour conserver le logo actuel</small>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-lg me-1"></i>Enregistrer
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
