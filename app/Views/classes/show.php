<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/classes') ?>">Classes</a></li><li class="breadcrumb-item active"><?= e($classe['nom']) ?></li></ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i><?= e($classe['nom']) ?></h1>
        <p class="text-muted mb-0"><?= e($classe['cycle_nom']) ?> — <?= e($classe['niveau_nom']) ?></p>
    </div>
    <?php if (can('classes.modifier')): ?>
    <a href="<?= url('/classes/'.$classe['id'].'/modifier') ?>" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Modifier</a>
    <?php endif; ?>
</div>

<div class="row g-4">
    <!-- Affecter un cours -->
    <?php if (can('classes.modifier')): ?>
    <div class="col-12">
        <div class="form-card">
            <div class="form-section-title">Affecter un cours</div>
            <form method="POST" action="<?= url('/classes/'.$classe['id'].'/affecter-cours') ?>" class="row g-2 align-items-end">
                <?= csrf_field() ?>
                <div class="col-md-3">
                    <label class="form-label">Enseignant</label>
                    <select name="personnel_id" class="form-select form-select-sm" required>
                        <option value="">-- Choisir --</option>
                        <?php
                        $db = \App\Core\Database::getInstance();
                        $etabId = \App\Core\Auth::etablissementId();
                        $profs = $db->prepare("SELECT p.id, u.nom, u.prenoms FROM personnel p JOIN users u ON u.id=p.user_id WHERE p.etablissement_id=? AND p.type='enseignant' AND p.deleted_at IS NULL ORDER BY u.nom");
                        $profs->execute([$etabId]);
                        foreach ($profs->fetchAll() as $pr): ?>
                        <option value="<?= $pr['id'] ?>"><?= e($pr['prenoms'].' '.$pr['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Matière</label>
                    <select name="matiere_id" class="form-select form-select-sm" required>
                        <option value="">-- Choisir --</option>
                        <?php
                        $mats = $db->prepare("SELECT * FROM matieres WHERE etablissement_id=? ORDER BY nom");
                        $mats->execute([$etabId]);
                        foreach ($mats->fetchAll() as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= e($m['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Coefficient</label>
                    <input type="number" name="coefficient" class="form-control form-control-sm" value="1" min="1" max="10">
                </div>
                <div class="col-md-2">
                    <label class="form-label">H/semaine</label>
                    <input type="number" name="heures_hebdo" class="form-control form-control-sm" placeholder="Ex: 4" min="1">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-success w-100"><i class="bi bi-plus-lg me-1"></i>Affecter</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Matières -->
    <div class="col-lg-6">
        <div class="table-card">
            <div class="table-card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-journal-bookmark me-2 text-warning"></i>Matières & Cours (<?= count($matieres) ?>)</h6></div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light"><tr><th>Matière</th><th>Enseignant</th><th>Coef</th></tr></thead>
                <tbody>
                <?php if (empty($matieres)): ?>
                <tr><td colspan="3" class="text-center py-3 text-muted">Aucune matière affectée</td></tr>
                <?php else: ?>
                <?php foreach ($matieres as $m): ?>
                <tr>
                    <td class="fw-medium"><?= e($m['matiere_nom']) ?></td>
                    <td><?= e(($m['prof_prenom']??'').' '.($m['prof_nom']??'')) ?></td>
                    <td><span class="badge bg-light text-dark border"><?= $m['coefficient'] ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Élèves -->
    <div class="col-lg-6">
        <div class="table-card">
            <div class="table-card-header">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-people-fill me-2 text-primary"></i>Élèves inscrits (<?= count($eleves) ?> / <?= $classe['effectif_max'] ?>)</h6>
            </div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light"><tr><th>Matricule</th><th>Nom & Prénom</th></tr></thead>
                <tbody>
                <?php if (empty($eleves)): ?>
                <tr><td colspan="2" class="text-center py-3 text-muted">Aucun élève inscrit</td></tr>
                <?php else: ?>
                <?php foreach ($eleves as $e): ?>
                <tr>
                    <td class="font-monospace text-muted"><?= e($e['matricule']) ?></td>
                    <td><a href="<?= url('/eleves/'.$e['id']) ?>" class="text-decoration-none fw-medium"><?= e($e['prenoms'].' '.$e['nom']) ?></a></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
