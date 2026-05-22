<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/eleves') ?>">Élèves</a></li>
    <li class="breadcrumb-item active">Inscrire</li>
</ol>

<div class="page-header">
    <h1><i class="bi bi-person-plus-fill text-primary me-2"></i>Inscrire un élève</h1>
</div>

<form method="POST" action="<?= url('/eleves') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Identité -->
    <div class="form-card mb-3">
        <div class="form-section-title">Identité</div>
        <div class="row g-3">
            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control <?= has_error('nom') ?>" value="<?= old('nom') ?>" required>
                        <?= error_field('nom') ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénoms <span class="text-danger">*</span></label>
                        <input type="text" name="prenoms" class="form-control <?= has_error('prenoms') ?>" value="<?= old('prenoms') ?>" required>
                        <?= error_field('prenoms') ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sexe <span class="text-danger">*</span></label>
                        <select name="sexe" class="form-select" required>
                            <option value="">—</option>
                            <option value="M" <?= old('sexe') === 'M' ? 'selected' : '' ?>>Masculin</option>
                            <option value="F" <?= old('sexe') === 'F' ? 'selected' : '' ?>>Féminin</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" name="date_naissance" class="form-control" value="<?= old('date_naissance') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lieu de naissance</label>
                        <input type="text" name="lieu_naissance" class="form-control" value="<?= old('lieu_naissance') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nationalité</label>
                        <input type="text" name="nationalite" class="form-control" value="<?= old('nationalite', 'Ivoirienne') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Groupe sanguin</label>
                        <select name="groupe_sanguin" class="form-select">
                            <option value="">—</option>
                            <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $g): ?>
                            <option value="<?= $g ?>" <?= old('groupe_sanguin') === $g ? 'selected' : '' ?>><?= $g ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Téléphone élève</label>
                        <input type="tel" name="telephone" class="form-control" value="<?= old('telephone') ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <label class="form-label d-block">Photo</label>
                <img id="photoPreview" src="<?= asset('img/avatar-default.png') ?>"
                     class="rounded mb-2" style="width:120px;height:120px;object-fit:cover;display:block;margin:0 auto">
                <input type="file" name="photo" class="form-control form-control-sm" accept="image/*" data-preview="photoPreview">
            </div>
        </div>
    </div>

    <!-- Inscription -->
    <div class="form-card mb-3">
        <div class="form-section-title">Inscription</div>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Classe <span class="text-danger">*</span></label>
                <select name="classe_id" class="form-select" required>
                    <option value="">Sélectionner une classe</option>
                    <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= old('classe_id') == $c['id'] ? 'selected' : '' ?>><?= e($c['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Adresse</label>
                <input type="text" name="adresse" class="form-control" value="<?= old('adresse') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Email élève</label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>">
            </div>
        </div>
    </div>

    <!-- Parent 1 -->
    <div class="form-card mb-3">
        <div class="form-section-title">Parent / Tuteur principal</div>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                <input type="text" name="parent1_nom" class="form-control" value="<?= old('parent1_nom') ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                <input type="tel" name="parent1_telephone" class="form-control" value="<?= old('parent1_telephone') ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Email</label>
                <input type="email" name="parent1_email" class="form-control" value="<?= old('parent1_email') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Profession</label>
                <input type="text" name="parent1_profession" class="form-control" value="<?= old('parent1_profession') ?>">
            </div>
        </div>
    </div>

    <!-- Parent 2 -->
    <div class="form-card mb-3">
        <div class="form-section-title">Parent / Tuteur secondaire <small class="text-muted">(optionnel)</small></div>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Nom complet</label>
                <input type="text" name="parent2_nom" class="form-control" value="<?= old('parent2_nom') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Téléphone</label>
                <input type="tel" name="parent2_telephone" class="form-control" value="<?= old('parent2_telephone') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="parent2_email" class="form-control" value="<?= old('parent2_email') ?>">
            </div>
        </div>
    </div>

    <!-- Observations médicales -->
    <div class="form-card mb-4">
        <div class="form-section-title">Notes médicales <small class="text-muted">(optionnel)</small></div>
        <textarea name="notes_medicales" class="form-control" rows="3" placeholder="Allergies, traitements, besoins particuliers…"><?= old('notes_medicales') ?></textarea>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check2 me-1"></i>Inscrire l'élève
        </button>
        <a href="<?= url('/eleves') ?>" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>
