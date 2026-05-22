<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/examens') ?>">Examens</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/examens/'.$examen['id']) ?>"><?= e($examen['nom']) ?></a></li>
    <li class="breadcrumb-item active">Modifier</li>
</ol>

<div class="page-header">
    <h1><i class="bi bi-pencil-square text-warning me-2"></i>Modifier l'examen</h1>
</div>

<div class="form-card" style="max-width:640px;">
    <div class="form-section-title">Informations de l'examen</div>
    <form method="POST" action="<?= url('/examens/'.$examen['id']) ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label fw-medium">Nom / Intitulé <span class="text-danger">*</span></label>
            <input type="text" name="nom" class="form-control"
                   value="<?= e(old('nom', $examen['nom'])) ?>" required
                   placeholder="Ex: Composition du 1er Trimestre">
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-medium">Type</label>
                <select name="type" class="form-select">
                    <option value="interne" <?= $examen['type'] === 'interne' ? 'selected' : '' ?>>Interne</option>
                    <option value="bac" <?= $examen['type'] === 'bac' ? 'selected' : '' ?>>Baccalauréat</option>
                    <option value="bepc" <?= $examen['type'] === 'bepc' ? 'selected' : '' ?>>BEPC</option>
                    <option value="cfee" <?= $examen['type'] === 'cfee' ? 'selected' : '' ?>>CEPE/CFEE</option>
                    <option value="concours" <?= $examen['type'] === 'concours' ? 'selected' : '' ?>>Concours</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Période</label>
                <select name="periode_id" class="form-select">
                    <option value="">— Aucune période —</option>
                    <?php foreach ($periodes as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $examen['periode_id'] == $p['id'] ? 'selected' : '' ?>>
                        <?= e($p['nom']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-medium">Date de début <span class="text-danger">*</span></label>
                <input type="date" name="date_debut" class="form-control"
                       value="<?= e(old('date_debut', $examen['date_debut'])) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Date de fin <span class="text-danger">*</span></label>
                <input type="date" name="date_fin" class="form-control"
                       value="<?= e(old('date_fin', $examen['date_fin'])) ?>" required>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
            </button>
            <a href="<?= url('/examens/'.$examen['id']) ?>" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
