<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/classes') ?>">Classes</a></li><li class="breadcrumb-item active">Créer</li></ol>

<div class="page-header">
    <h1><i class="bi bi-plus-square-fill text-primary me-2"></i>Créer une classe</h1>
</div>

<form method="POST" action="<?= url('/classes') ?>">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-card">
                <div class="form-section-title">Informations</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nom de la classe <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" value="<?= old('nom') ?>" required placeholder="Ex: 6ème A, Terminale C, L1 Info…">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Niveau <span class="text-danger">*</span></label>
                        <select name="niveau_id" class="form-select" required>
                            <option value="">-- Choisir un niveau --</option>
                            <?php $currentCycle = ''; foreach ($niveaux as $n): ?>
                            <?php if ($n['cycle_nom'] !== $currentCycle): $currentCycle = $n['cycle_nom']; ?>
                            <optgroup label="<?= e($n['cycle_nom']) ?>">
                            <?php endif; ?>
                            <option value="<?= $n['id'] ?>" <?= old('niveau_id') == $n['id'] ? 'selected' : '' ?>><?= e($n['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Effectif maximum</label>
                        <input type="number" name="effectif_max" class="form-control" value="<?= old('effectif_max', 40) ?>" min="1" max="200">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Professeur titulaire</label>
                        <select name="titulaire_id" class="form-select">
                            <option value="">-- Aucun --</option>
                            <?php foreach ($enseignants as $e): ?>
                            <option value="<?= $e['id'] ?>" <?= old('titulaire_id') == $e['id'] ? 'selected' : '' ?>><?= e($e['prenoms'].' '.$e['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($annee): ?>
        <div class="col-lg-6">
            <div class="alert alert-info border-0">
                <i class="bi bi-info-circle me-2"></i>La classe sera créée pour l'année scolaire <strong><?= e($annee['libelle']) ?></strong>.
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Créer la classe</button>
        <a href="<?= url('/classes') ?>" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>
