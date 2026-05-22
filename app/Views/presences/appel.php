<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/presences') ?>">Présences</a></li><li class="breadcrumb-item active">Appel</li></ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-clipboard2-check-fill text-info me-2"></i>Appel</h1>
        <p class="text-muted mb-0"><?= e($affectation['classe_nom']) ?> — <?= e($affectation['matiere_nom']) ?> — <?= dateFormat($date, 'd/m/Y') ?></p>
    </div>
    <?php if ($seance['appel_fait']): ?>
    <span class="badge bg-success fs-6">Appel déjà fait</span>
    <?php endif; ?>
</div>

<form method="POST" action="<?= url('/presences/appel') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="seance_id" value="<?= $seance['id'] ?>">

    <!-- Boutons collectifs -->
    <div class="d-flex gap-2 mb-3">
        <button type="button" class="btn btn-sm btn-outline-success" onclick="setAll('present')"><i class="bi bi-check-all me-1"></i>Tous présents</button>
        <button type="button" class="btn btn-sm btn-outline-danger"  onclick="setAll('absent')"> <i class="bi bi-x-circle me-1"></i>Tous absents</button>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <span class="text-muted small"><?= count($eleves) ?> élève(s)</span>
            <div>
                <span class="badge bg-success me-1" id="cntPresent">0</span> présents
                <span class="badge bg-danger ms-2 me-1" id="cntAbsent">0</span> absents
            </div>
        </div>
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Élève</th>
                    <th>
                        <div class="d-flex gap-3">
                            <span class="text-success">Présent</span>
                            <span class="text-danger">Absent</span>
                            <span class="text-warning">Retard</span>
                            <span class="text-info">Excusé</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($eleves as $k => $eleve): ?>
            <?php $statut = $eleve['presence_statut'] ?? 'present'; ?>
            <tr class="presence-row-tr">
                <td class="text-muted"><?= $k + 1 ?></td>
                <td class="fw-medium"><?= e(strtoupper($eleve['nom']) . ' ' . $eleve['prenoms']) ?></td>
                <td>
                    <div class="d-flex gap-3">
                        <?php foreach (['present' => 'success', 'absent' => 'danger', 'retard' => 'warning', 'excuse' => 'info'] as $val => $color): ?>
                        <div class="form-check">
                            <input class="form-check-input presence-radio" type="radio"
                                   name="presence[<?= $eleve['id'] ?>]"
                                   value="<?= $val ?>"
                                   id="p_<?= $eleve['id'] ?>_<?= $val ?>"
                                   <?= $statut === $val ? 'checked' : '' ?>>
                            <label class="form-check-label text-<?= $color ?>" for="p_<?= $eleve['id'] ?>_<?= $val ?>">
                                <?= ucfirst($val) ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check2 me-1"></i>Enregistrer l'appel
        </button>
        <a href="<?= url('/presences') ?>" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>

<script>
function setAll(val) {
    document.querySelectorAll(`input[type=radio][value=${val}]`).forEach(r => r.checked = true);
    updateCount();
}

function updateCount() {
    let p = 0, a = 0;
    document.querySelectorAll('.presence-row-tr').forEach(row => {
        const checked = row.querySelector('input[type=radio]:checked');
        if (checked?.value === 'present') p++;
        else if (checked?.value === 'absent') a++;
    });
    document.getElementById('cntPresent').textContent = p;
    document.getElementById('cntAbsent').textContent  = a;
}

document.querySelectorAll('.presence-radio').forEach(r => r.addEventListener('change', updateCount));
updateCount();
</script>
