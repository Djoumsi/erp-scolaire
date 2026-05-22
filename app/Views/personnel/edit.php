<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/personnel') ?>">Personnel</a></li><li class="breadcrumb-item active">Modifier</li></ol>

<div class="page-header">
    <h1><i class="bi bi-pencil-square text-primary me-2"></i>Modifier — <?= e($personne['prenoms'].' '.$personne['nom']) ?></h1>
</div>

<form method="POST" action="<?= url('/personnel/'.$personne['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="form-card mb-4">
                <div class="form-section-title">Identité</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Prénom(s) <span class="text-danger">*</span></label>
                        <input type="text" name="prenoms" class="form-control" value="<?= old('prenoms', $personne['prenoms']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" value="<?= old('nom', $personne['nom']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email', $personne['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="<?= old('telephone', $personne['telephone'] ?? '') ?>">
                    </div>
                </div>
            </div>
            <div class="form-card">
                <div class="form-section-title">Informations professionnelles</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <?php foreach (['enseignant'=>'Enseignant','administratif'=>'Administratif','direction'=>'Direction','surveillant'=>'Surveillant','autre'=>'Autre'] as $v=>$l): ?>
                            <option value="<?= $v ?>" <?= old('type',$personne['type'])===$v?'selected':'' ?>><?= $l ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Statut contrat</label>
                        <select name="statut_contrat" class="form-select">
                            <?php foreach (['permanent'=>'Permanent','contractuel'=>'Contractuel','vacataire'=>'Vacataire','stagiaire'=>'Stagiaire'] as $v=>$l): ?>
                            <option value="<?= $v ?>" <?= old('statut_contrat',$personne['statut_contrat']??'')===$v?'selected':'' ?>><?= $l ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Spécialité</label>
                        <input type="text" name="specialite" class="form-control" value="<?= old('specialite', $personne['specialite'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Diplôme</label>
                        <input type="text" name="diplome" class="form-control" value="<?= old('diplome', $personne['diplome'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date d'embauche</label>
                        <input type="date" name="date_embauche" class="form-control" value="<?= old('date_embauche', $personne['date_embauche'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-card">
                <div class="form-section-title">Photo</div>
                <div class="text-center">
                    <div id="photoPreview" class="mb-3 d-flex align-items-center justify-content-center border rounded-circle mx-auto" style="width:120px;height:120px;overflow:hidden;background:#f8fafc;">
                        <?php if (!empty($personne['photo'])): ?>
                        <img src="<?= url($personne['photo']) ?>" style="width:120px;height:120px;object-fit:cover;">
                        <?php else: ?><i class="bi bi-person fs-1 text-muted"></i><?php endif; ?>
                    </div>
                    <input type="file" name="photo" id="photoInput" class="form-control form-control-sm" accept="image/*">
                    <small class="text-muted">Laisser vide pour garder la photo actuelle</small>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
        <a href="<?= url('/personnel/'.$personne['id']) ?>" class="btn btn-outline-secondary">Annuler</a>
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
