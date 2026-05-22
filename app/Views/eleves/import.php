<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/eleves') ?>">Élèves</a></li><li class="breadcrumb-item active">Import CSV</li></ol>

<div class="page-header">
    <h1><i class="bi bi-file-earmark-arrow-up text-primary me-2"></i>Import CSV des élèves</h1>
    <a href="<?= url('/eleves') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
</div>

<?php if (\App\Core\Session::hasFlash('error')): ?>
<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i><?= e(\App\Core\Session::flash('error')) ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="form-card">
            <div class="form-section-title">Téléverser le fichier CSV</div>
            <form method="POST" action="<?= url('/eleves/import') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Classe de destination</label>
                    <select name="classe_id" class="form-select">
                        <option value="">-- Aucune (importer sans classe) --</option>
                        <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= e($c['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Si vous choisissez une classe, tous les élèves du fichier y seront inscrits.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fichier CSV <span class="text-danger">*</span></label>
                    <input type="file" name="fichier_csv" class="form-control" accept=".csv" required>
                    <div class="form-text">Fichier CSV séparé par des points-virgules (;). Max 5 Mo.</div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload me-1"></i>Lancer l'import
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="form-card">
            <div class="form-section-title">Format attendu du fichier CSV</div>
            <p class="small text-muted">La première ligne doit être l'en-tête. Les colonnes doivent être séparées par des <strong>points-virgules (;)</strong>.</p>

            <table class="table table-sm table-bordered small">
                <thead class="table-light">
                    <tr><th>#</th><th>Colonne</th><th>Obligatoire</th></tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>nom</td><td><span class="badge bg-danger">Oui</span></td></tr>
                    <tr><td>2</td><td>prenoms</td><td><span class="badge bg-danger">Oui</span></td></tr>
                    <tr><td>3</td><td>sexe (M ou F)</td><td><span class="badge bg-danger">Oui</span></td></tr>
                    <tr><td>4</td><td>date_naissance (YYYY-MM-DD)</td><td><span class="badge bg-secondary">Non</span></td></tr>
                    <tr><td>5</td><td>lieu_naissance</td><td><span class="badge bg-secondary">Non</span></td></tr>
                    <tr><td>6</td><td>telephone</td><td><span class="badge bg-secondary">Non</span></td></tr>
                    <tr><td>7</td><td>parent1_nom</td><td><span class="badge bg-secondary">Non</span></td></tr>
                    <tr><td>8</td><td>parent1_telephone</td><td><span class="badge bg-secondary">Non</span></td></tr>
                </tbody>
            </table>

            <div class="alert alert-secondary small mb-0">
                <strong>Exemple :</strong><br>
                <code>nom;prenoms;sexe;date_naissance;lieu_naissance;telephone;parent1_nom;parent1_telephone</code><br>
                <code>KONÉ;Aminata;F;2008-03-15;Abidjan;0708090010;Koné Paul;0101020304</code><br>
                <code>DIALLO;Moussa;M;2007-11-22;Bouaké;;Diallo Ibrahim;</code>
            </div>
        </div>

        <div class="form-card mt-3">
            <div class="form-section-title">Télécharger le modèle</div>
            <p class="small text-muted">Téléchargez ce fichier modèle et remplissez-le avec vos données.</p>
            <a href="<?= url('/eleves/export/excel') ?>" class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i>Modèle Excel (liste actuelle)
            </a>
        </div>
    </div>
</div>
