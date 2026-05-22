<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li>
    <li class="breadcrumb-item"><a href="<?= url('/notes') ?>">Notes</a></li>
    <li class="breadcrumb-item active">Saisie</li>
</ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-pencil-square text-warning me-2"></i>Saisie des notes</h1>
        <p class="text-muted mb-0"><?= e($affectation['classe_nom']) ?> &mdash; <?= e($affectation['matiere_nom']) ?></p>
    </div>
</div>

<!-- Sélecteur de période -->
<div class="d-flex gap-2 mb-3 flex-wrap">
    <?php foreach ($periodes as $p): ?>
    <a href="<?= url('/notes/saisir/' . $affectation['id']) ?>?periode=<?= $p['id'] ?>"
       class="btn btn-sm <?= ($periode && $p['id'] == $periode['id']) ? 'btn-primary' : 'btn-outline-primary' ?>">
        <?= e($p['nom']) ?>
    </a>
    <?php endforeach; ?>
</div>

<?php if ($periode): ?>
<!-- Créer une évaluation -->
<div class="table-card mb-3">
    <div class="table-card-header">
        <h6 class="mb-0 fw-semibold">Évaluations — <?= e($periode['nom']) ?></h6>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEvaluation">
            <i class="bi bi-plus me-1"></i>Nouvelle évaluation
        </button>
    </div>
    <?php if (!empty($evaluations)): ?>
    <div class="p-3">
        <div class="d-flex gap-2 flex-wrap">
            <?php foreach ($evaluations as $ev): ?>
            <span class="badge bg-light text-dark border p-2" style="font-size:.8rem">
                <?= e($ev['titre'] ?: $ev['type_nom']) ?> — <?= dateFormat($ev['date_evaluation']) ?>
                (coef. <?= $ev['coefficient'] ?>)
            </span>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if (!empty($evaluations)): ?>
<!-- Grille de saisie des notes -->
<div class="table-card">
    <div class="table-card-header">
        <h6 class="mb-0">Grille de notes — <?= count($eleves) ?> élèves</h6>
        <small class="text-muted">Laisser vide = Absent</small>
    </div>
    <div class="table-responsive">
        <form method="POST" action="<?= url('/notes/saisir') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="affectation_id" value="<?= $affectation['id'] ?>">
            <input type="hidden" name="periode_id" value="<?= $periode['id'] ?>">

            <!-- Sélection de l'évaluation -->
            <div class="p-3 border-bottom">
                <label class="form-label fw-medium">Évaluation à saisir</label>
                <select name="evaluation_id" class="form-select w-auto" id="selectEval" required>
                    <option value="">— Choisir une évaluation —</option>
                    <?php foreach ($evaluations as $ev): ?>
                    <option value="<?= $ev['id'] ?>" data-sur="<?= $ev['note_sur'] ?>">
                        <?= e($ev['titre'] ?: $ev['type_nom']) ?> (<?= dateFormat($ev['date_evaluation']) ?>) — /<?= $ev['note_sur'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Élève</th>
                        <th>Matricule</th>
                        <th width="150">Note <span id="noteSur"></span></th>
                        <th>Historique</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eleves as $k => $eleve): ?>
                    <tr>
                        <td class="text-muted"><?= $k + 1 ?></td>
                        <td class="fw-medium"><?= e(strtoupper($eleve['nom']) . ' ' . $eleve['prenoms']) ?></td>
                        <td><code><?= e($eleve['matricule']) ?></code></td>
                        <td>
                            <input type="number" name="notes[<?= $eleve['id'] ?>]"
                                   class="form-control form-control-sm note-input"
                                   min="0" max="20" step="0.25"
                                   placeholder="Abs."
                                   id="evalInput" data-eleve="<?= $eleve['id'] ?>">
                        </td>
                        <td>
                            <!-- Mini historique des notes de l'élève sur cette évaluation -->
                            <?php foreach ($evaluations as $ev): ?>
                            <?php if (isset($notes[$ev['id']][$eleve['id']])): ?>
                            <?php $n = $notes[$ev['id']][$eleve['id']]; ?>
                            <span class="badge bg-light text-dark border" title="<?= e($ev['titre'] ?: $ev['type_nom']) ?>">
                                <?= $n['note'] !== null ? $n['note'] : 'Abs' ?>
                            </span>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="p-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small">Saisie rapide : Tab pour passer à la note suivante, Entrée pour soumettre</span>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check2 me-1"></i>Enregistrer les notes
                </button>
            </div>
        </form>
    </div>
</div>
<?php else: ?>
<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>Créez d'abord une évaluation pour cette période.
</div>
<?php endif; ?>
<?php endif; ?>

<!-- Modal Nouvelle évaluation -->
<div class="modal fade" id="modalEvaluation" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= url('/notes/evaluation') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="affectation_id" value="<?= $affectation['id'] ?>">
                <input type="hidden" name="periode_id" value="<?= $periode['id'] ?? '' ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle évaluation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type_evaluation_id" class="form-select" required>
                            <?php foreach ($typesEval as $te): ?>
                            <option value="<?= $te['id'] ?>"><?= e($te['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titre (optionnel)</label>
                        <input type="text" name="titre" class="form-control" placeholder="ex: Devoir n°1 Chapitre 2">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date_evaluation" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Note /</label>
                            <input type="number" name="note_sur" class="form-control" value="20" min="1" max="100">
                        </div>
                        <div class="col-3">
                            <label class="form-label">Coefficient</label>
                            <input type="number" name="coefficient" class="form-control" value="1" min="0.5" step="0.5">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Mettre à jour le max des inputs selon l'évaluation choisie
document.getElementById('selectEval')?.addEventListener('change', function () {
    const sur = this.options[this.selectedIndex]?.dataset.sur || 20;
    document.querySelectorAll('.note-input').forEach(i => {
        i.max = sur;
        i.placeholder = `/ ${sur}`;
    });
    document.getElementById('noteSur').textContent = '/ ' + sur;
});
</script>
