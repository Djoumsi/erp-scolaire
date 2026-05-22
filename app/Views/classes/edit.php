<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/classes') ?>">Classes</a></li><li class="breadcrumb-item active">Modifier</li></ol>

<div class="page-header">
    <h1><i class="bi bi-pencil-square text-primary me-2"></i>Modifier — <?= e($classe['nom']) ?></h1>
</div>

<form method="POST" action="<?= url('/classes/'.$classe['id']) ?>">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-card">
                <div class="form-section-title">Informations</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" value="<?= old('nom', $classe['nom']) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Niveau <span class="text-danger">*</span></label>
                        <select name="niveau_id" class="form-select" required>
                            <?php $currentCycle = ''; foreach ($niveaux as $n): ?>
                            <?php if ($n['cycle_nom'] !== $currentCycle): $currentCycle = $n['cycle_nom']; ?>
                            <optgroup label="<?= e($n['cycle_nom']) ?>">
                            <?php endif; ?>
                            <option value="<?= $n['id'] ?>" <?= old('niveau_id',$classe['niveau_id']) == $n['id'] ? 'selected' : '' ?>><?= e($n['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Effectif max</label>
                        <input type="number" name="effectif_max" class="form-control" value="<?= old('effectif_max', $classe['effectif_max']) ?>" min="1">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Professeur titulaire</label>
                        <select name="titulaire_id" class="form-select">
                            <option value="">-- Aucun --</option>
                            <?php foreach ($enseignants as $e): ?>
                            <option value="<?= $e['id'] ?>" <?= old('titulaire_id',$classe['titulaire_id']??'') == $e['id'] ? 'selected' : '' ?>><?= e($e['prenoms'].' '.$e['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
        <a href="<?= url('/classes') ?>" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>
