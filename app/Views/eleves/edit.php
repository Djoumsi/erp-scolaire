<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/eleves') ?>">Élèves</a></li><li class="breadcrumb-item active">Modifier</li></ol>

<div class="page-header">
    <h1><i class="bi bi-pencil-square text-primary me-2"></i>Modifier — <?= e($eleve['prenoms'].' '.$eleve['nom']) ?></h1>
</div>

<form method="POST" action="<?= url('/eleves/'.$eleve['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="form-card mb-4">
                <div class="form-section-title">Identité</div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Prénom(s) <span class="text-danger">*</span></label><input type="text" name="prenoms" class="form-control" value="<?= old('prenoms',$eleve['prenoms']) ?>" required></div>
                    <div class="col-md-6"><label class="form-label">Nom <span class="text-danger">*</span></label><input type="text" name="nom" class="form-control" value="<?= old('nom',$eleve['nom']) ?>" required></div>
                    <div class="col-md-4"><label class="form-label">Sexe <span class="text-danger">*</span></label>
                        <select name="sexe" class="form-select" required>
                            <option value="M" <?= old('sexe',$eleve['sexe'])==='M'?'selected':'' ?>>Masculin</option>
                            <option value="F" <?= old('sexe',$eleve['sexe'])==='F'?'selected':'' ?>>Féminin</option>
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Date de naissance</label><input type="date" name="date_naissance" class="form-control" value="<?= old('date_naissance',$eleve['date_naissance']??'') ?>"></div>
                    <div class="col-md-4"><label class="form-label">Lieu de naissance</label><input type="text" name="lieu_naissance" class="form-control" value="<?= old('lieu_naissance',$eleve['lieu_naissance']??'') ?>"></div>
                    <div class="col-md-6"><label class="form-label">Nationalité</label><input type="text" name="nationalite" class="form-control" value="<?= old('nationalite',$eleve['nationalite']??'') ?>"></div>
                    <div class="col-md-6"><label class="form-label">Groupe sanguin</label>
                        <select name="groupe_sanguin" class="form-select">
                            <option value="">—</option>
                            <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $g): ?>
                            <option value="<?= $g ?>" <?= old('groupe_sanguin',$eleve['groupe_sanguin']??'')===$g?'selected':'' ?>><?= $g ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6"><label class="form-label">Téléphone</label><input type="text" name="telephone" class="form-control" value="<?= old('telephone',$eleve['telephone']??'') ?>"></div>
                    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= old('email',$eleve['email']??'') ?>"></div>
                    <div class="col-12"><label class="form-label">Adresse</label><input type="text" name="adresse" class="form-control" value="<?= old('adresse',$eleve['adresse']??'') ?>"></div>
                </div>
            </div>
            <div class="form-card mb-4">
                <div class="form-section-title">Parent 1</div>
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Nom complet</label><input type="text" name="parent1_nom" class="form-control" value="<?= old('parent1_nom',$eleve['parent1_nom']??'') ?>"></div>
                    <div class="col-md-4"><label class="form-label">Téléphone</label><input type="text" name="parent1_telephone" class="form-control" value="<?= old('parent1_telephone',$eleve['parent1_telephone']??'') ?>"></div>
                    <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="parent1_email" class="form-control" value="<?= old('parent1_email',$eleve['parent1_email']??'') ?>"></div>
                    <div class="col-md-4"><label class="form-label">Profession</label><input type="text" name="parent1_profession" class="form-control" value="<?= old('parent1_profession',$eleve['parent1_profession']??'') ?>"></div>
                </div>
            </div>
            <div class="form-card">
                <div class="form-section-title">Parent 2 (optionnel)</div>
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Nom complet</label><input type="text" name="parent2_nom" class="form-control" value="<?= old('parent2_nom',$eleve['parent2_nom']??'') ?>"></div>
                    <div class="col-md-4"><label class="form-label">Téléphone</label><input type="text" name="parent2_telephone" class="form-control" value="<?= old('parent2_telephone',$eleve['parent2_telephone']??'') ?>"></div>
                    <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="parent2_email" class="form-control" value="<?= old('parent2_email',$eleve['parent2_email']??'') ?>"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-card mb-4">
                <div class="form-section-title">Photo</div>
                <div class="text-center">
                    <div id="photoPreview" class="mb-3 d-flex align-items-center justify-content-center border rounded-circle mx-auto" style="width:120px;height:120px;overflow:hidden;background:#f8fafc;">
                        <?php if (!empty($eleve['photo'])): ?><img src="<?= url($eleve['photo']) ?>" style="width:120px;height:120px;object-fit:cover;"><?php else: ?><i class="bi bi-person fs-1 text-muted"></i><?php endif; ?>
                    </div>
                    <input type="file" name="photo" id="photoInput" class="form-control form-control-sm" accept="image/*">
                </div>
            </div>
            <div class="form-card">
                <div class="form-section-title">Classe (inscription en cours)</div>
                <select name="classe_id" class="form-select">
                    <option value="">— Pas de changement —</option>
                    <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= e($c['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted d-block mt-1">Laisser vide pour conserver la classe actuelle.</small>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
        <a href="<?= url('/eleves/'.$eleve['id']) ?>" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>
<script>
document.getElementById('photoInput').addEventListener('change', function() {
    const file = this.files[0]; if (!file) return;
    const r = new FileReader();
    r.onload = e => { document.getElementById('photoPreview').innerHTML = '<img src="'+e.target.result+'" style="width:120px;height:120px;object-fit:cover;">'; };
    r.readAsDataURL(file);
});
</script>
