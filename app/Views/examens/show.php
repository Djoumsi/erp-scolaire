<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/examens') ?>">Examens</a></li><li class="breadcrumb-item active"><?= e($examen['nom']) ?></li></ol>

<div class="page-header">
    <div>
        <h1><i class="bi bi-journal-bookmark-fill text-primary me-2"></i><?= e($examen['nom']) ?></h1>
        <p class="text-muted mb-0"><?= dateFormat($examen['date_debut']) ?> → <?= dateFormat($examen['date_fin']) ?></p>
    </div>
    <?php if (can('examens.modifier')): ?>
    <div class="d-flex gap-2">
        <a href="<?= url('/examens/'.$examen['id'].'/modifier') ?>" class="btn btn-outline-warning">
            <i class="bi bi-pencil me-1"></i>Modifier
        </a>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEpreuveModal">
            <i class="bi bi-plus-lg me-1"></i>Ajouter une épreuve
        </button>
    </div>
    <?php endif; ?>
</div>

<div class="table-card">
    <div class="table-card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-list-ol me-2 text-primary"></i>Planning des épreuves</h6></div>
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr><th>Matière</th><th>Classe</th><th>Date</th><th>Horaire</th><th>Durée</th><th>Salle</th></tr>
        </thead>
        <tbody>
        <?php if (empty($planning)): ?>
        <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-25"></i>Aucune épreuve planifiée</td></tr>
        <?php else: ?>
        <?php foreach ($planning as $p): ?>
        <tr>
            <td class="fw-semibold"><?= e($p['matiere_nom']) ?></td>
            <td><?= e($p['classe_nom'] ?? '—') ?></td>
            <td><?= dateFormat($p['date_epreuve']) ?></td>
            <td class="small text-muted"><?= e(substr($p['heure_debut'],0,5).' – '.substr($p['heure_fin'],0,5)) ?></td>
            <td><?= $p['duree_minutes'] ? $p['duree_minutes'].'min' : '—' ?></td>
            <td><?= e($p['salle_nom'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (can('examens.modifier')): ?>
<div class="modal fade" id="addEpreuveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Ajouter une épreuve</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST" action="<?= url('/examens/'.$examen['id'].'/planning') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <?php
                    $db = \App\Core\Database::getInstance();
                    $etabId = \App\Core\Auth::etablissementId();
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Matière <span class="text-danger">*</span></label>
                        <select name="matiere_id" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <?php $mats = $db->prepare("SELECT * FROM matieres WHERE etablissement_id=? ORDER BY nom"); $mats->execute([$etabId]); foreach ($mats->fetchAll() as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= e($m['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Classe</label>
                        <select name="classe_id" class="form-select">
                            <option value="">— Toutes les classes —</option>
                            <?php
                            $anneeStmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1"); $anneeStmt->execute([$etabId]); $annee = $anneeStmt->fetch();
                            $cls = $db->prepare("SELECT * FROM classes WHERE etablissement_id=? AND annee_scolaire_id=? ORDER BY nom"); $cls->execute([$etabId, $annee['id']??0]);
                            foreach ($cls->fetchAll() as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= e($c['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-4"><label class="form-label">Date <span class="text-danger">*</span></label><input type="date" name="date_epreuve" class="form-control" required></div>
                        <div class="col-4"><label class="form-label">Début <span class="text-danger">*</span></label><input type="time" name="heure_debut" class="form-control" required></div>
                        <div class="col-4"><label class="form-label">Fin <span class="text-danger">*</span></label><input type="time" name="heure_fin" class="form-control" required></div>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col-6"><label class="form-label">Durée (min)</label><input type="number" name="duree_minutes" class="form-control" placeholder="Ex: 180"></div>
                        <div class="col-6">
                            <label class="form-label">Salle</label>
                            <select name="salle_id" class="form-select">
                                <option value="">— Aucune —</option>
                                <?php $salles = $db->prepare("SELECT * FROM salles WHERE etablissement_id=? ORDER BY nom"); $salles->execute([$etabId]); foreach ($salles->fetchAll() as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= e($s['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
