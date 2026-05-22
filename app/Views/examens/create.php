<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/examens') ?>">Examens</a></li><li class="breadcrumb-item active">Créer</li></ol>

<div class="page-header">
    <h1><i class="bi bi-journal-plus text-primary me-2"></i>Créer un examen</h1>
</div>

<form method="POST" action="<?= url('/examens') ?>">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-card">
                <div class="form-section-title">Informations</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nom de l'examen <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" value="<?= old('nom') ?>" required placeholder="Ex: Examen du 1er trimestre, Baccalauréat 2024…">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="interne">Examen interne</option>
                            <option value="baccalaureat">Baccalauréat</option>
                            <option value="brevet">Brevet</option>
                            <option value="concours">Concours</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Période</label>
                        <select name="periode_id" class="form-select">
                            <option value="">— Aucune —</option>
                            <?php foreach ($periodes as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= old('periode_id') == $p['id'] ? 'selected' : '' ?>><?= e($p['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date de début <span class="text-danger">*</span></label>
                        <input type="date" name="date_debut" class="form-control" value="<?= old('date_debut') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date de fin <span class="text-danger">*</span></label>
                        <input type="date" name="date_fin" class="form-control" value="<?= old('date_fin') ?>" required>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($annee): ?>
        <div class="col-lg-6">
            <div class="alert alert-info border-0 h-100 d-flex align-items-start gap-2">
                <i class="bi bi-info-circle fs-5 mt-1"></i>
                <div>L'examen sera créé pour l'année scolaire <strong><?= e($annee['libelle']) ?></strong>.<br>
                Vous pourrez ajouter le planning des épreuves après la création.</div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Créer l'examen</button>
        <a href="<?= url('/examens') ?>" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>
