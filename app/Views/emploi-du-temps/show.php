<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/emploi-du-temps') ?>">Emploi du temps</a></li><li class="breadcrumb-item active"><?= e($classe['nom'] ?? '') ?></li></ol>

<div class="page-header">
    <h1><i class="bi bi-calendar3-week-fill text-primary me-2"></i>EDT — <?= e($classe['nom'] ?? '') ?></h1>
    <?php if (can('emploi_temps.modifier')): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCoursModal">
        <i class="bi bi-plus-lg me-1"></i>Ajouter un cours
    </button>
    <?php endif; ?>
</div>

<?php if (empty($creneaux)): ?>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Aucun créneau horaire configuré. <a href="<?= url('/parametres') ?>">Configurer les créneaux</a>.</div>
<?php else: ?>
<div class="table-card" style="overflow-x:auto;">
    <table class="table table-bordered mb-0" style="min-width:700px;">
        <thead class="table-dark">
            <tr>
                <th style="width:120px;">Créneau</th>
                <?php foreach ($jours as $j => $nomJ): ?>
                <th class="text-center"><?= $nomJ ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($creneaux as $cr): ?>
        <tr>
            <td class="align-middle small fw-semibold text-muted">
                <?= e($cr['nom'] ?? '') ?><br>
                <span class="text-muted fw-normal"><?= e(substr($cr['heure_debut'],0,5)) ?>–<?= e(substr($cr['heure_fin'],0,5)) ?></span>
            </td>
            <?php foreach ($jours as $j => $nomJ): ?>
            <td class="align-middle p-1" style="min-width:130px;">
                <?php $slot = $grille[$j][$cr['id']] ?? null; ?>
                <?php if ($slot): ?>
                <div class="rounded p-2" style="background:rgba(59,130,246,.1);border-left:3px solid #3b82f6;">
                    <div class="fw-semibold small"><?= e($slot['matiere_nom']) ?></div>
                    <div class="text-muted" style="font-size:.75rem;"><?= e(($slot['prof_prenom']??'').' '.($slot['prof_nom']??'')) ?></div>
                    <?php if (!empty($slot['salle_nom'])): ?>
                    <div class="text-muted" style="font-size:.7rem;"><i class="bi bi-geo-alt"></i> <?= e($slot['salle_nom']) ?></div>
                    <?php endif; ?>
                    <?php if (can('emploi_temps.modifier')): ?>
                    <form method="POST" action="<?= url('/emploi-du-temps/'.$slot['id'].'/supprimer') ?>" class="mt-1">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-link text-danger p-0" style="font-size:.7rem;" onclick="return confirm('Retirer ce cours ?')"><i class="bi bi-x-circle"></i> Retirer</button>
                    </form>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="text-center text-muted" style="font-size:.75rem;">—</div>
                <?php endif; ?>
            </td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php if (can('emploi_temps.modifier')): ?>
<!-- Modal Ajouter cours -->
<div class="modal fade" id="addCoursModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Ajouter un cours</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST" action="<?= url('/emploi-du-temps') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="classe_id" value="<?= $classe['id'] ?>">
                <?php
                $db = \App\Core\Database::getInstance();
                $etabId = \App\Core\Auth::etablissementId();
                $anneeStmt = $db->prepare("SELECT * FROM annees_scolaires WHERE etablissement_id=? AND en_cours=1 LIMIT 1");
                $anneeStmt->execute([$etabId]);
                $annee = $anneeStmt->fetch();
                ?>
                <input type="hidden" name="annee_scolaire_id" value="<?= $annee['id'] ?? 0 ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jour <span class="text-danger">*</span></label>
                        <select name="jour_semaine" class="form-select" required>
                            <?php foreach ($jours as $j => $nom): ?>
                            <option value="<?= $j ?>"><?= $nom ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Créneau <span class="text-danger">*</span></label>
                        <select name="creneau_id" class="form-select" required>
                            <?php foreach ($creneaux as $cr): ?>
                            <option value="<?= $cr['id'] ?>"><?= e($cr['nom'].' — '.substr($cr['heure_debut'],0,5).'-'.substr($cr['heure_fin'],0,5)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cours (Enseignant — Matière) <span class="text-danger">*</span></label>
                        <select name="affectation_id" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <?php
                            $affStmt = $db->prepare("SELECT ac.id, m.nom as matiere, u.prenoms, u.nom as pnom FROM affectations_cours ac JOIN matieres m ON m.id=ac.matiere_id JOIN personnel p ON p.id=ac.personnel_id JOIN users u ON u.id=p.user_id WHERE ac.classe_id=? ORDER BY m.nom");
                            $affStmt->execute([$classe['id']]);
                            foreach ($affStmt->fetchAll() as $af): ?>
                            <option value="<?= $af['id'] ?>"><?= e($af['matiere'].' — '.$af['prenoms'].' '.$af['pnom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Salle</label>
                        <select name="salle_id" class="form-select">
                            <option value="">-- Aucune --</option>
                            <?php
                            $salleStmt = $db->prepare("SELECT * FROM salles WHERE etablissement_id=? ORDER BY nom");
                            $salleStmt->execute([$etabId]);
                            foreach ($salleStmt->fetchAll() as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= e($s['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
