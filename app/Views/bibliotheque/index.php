<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Bibliothèque</li></ol>

<div class="page-header">
    <h1><i class="bi bi-book-half text-primary me-2"></i>Bibliothèque</h1>
    <?php if (can('bibliotheque.gerer')): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLivreModal">
        <i class="bi bi-plus-lg me-1"></i>Ajouter un livre
    </button>
    <?php endif; ?>
</div>

<div class="row g-4">
    <!-- Emprunts en cours -->
    <div class="col-12">
        <div class="table-card">
            <div class="table-card-header"><h6 class="mb-0 fw-semibold"><i class="bi bi-clock me-2 text-warning"></i>Emprunts en cours (<?= count($emprunts_encours) ?>)</h6></div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light"><tr><th>Livre</th><th>Emprunteur</th><th>Emprunté le</th><th>Retour prévu</th><th>Actions</th></tr></thead>
                <tbody>
                <?php if (empty($emprunts_encours)): ?>
                <tr><td colspan="5" class="text-center py-3 text-muted">Aucun emprunt en cours</td></tr>
                <?php else: foreach ($emprunts_encours as $e): ?>
                <tr class="<?= strtotime($e['date_retour_prevu']) < time() ? 'table-warning' : '' ?>">
                    <td class="fw-medium"><?= e($e['titre']) ?></td>
                    <td><?= e(($e['prenoms']??'').' '.($e['nom']??'')) ?></td>
                    <td><?= dateFormat($e['date_emprunt']) ?></td>
                    <td><?= dateFormat($e['date_retour_prevu']) ?></td>
                    <td>
                        <?php if (can('bibliotheque.gerer')): ?>
                        <form method="POST" action="<?= url('/bibliotheque/retour/'.$e['id']) ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-success"><i class="bi bi-check me-1"></i>Retour</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Catalogue -->
    <div class="col-12">
        <div class="table-card">
            <div class="table-card-header">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-journals me-2 text-primary"></i>Catalogue (<?= count($livres) ?> livres)</h6>
            </div>
            <table class="table table-hover mb-0 small">
                <thead class="table-light"><tr><th>Titre</th><th>Auteur</th><th>Catégorie</th><th>Dispo</th><th>Total</th><th>Actions</th></tr></thead>
                <tbody>
                <?php if (empty($livres)): ?>
                <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-book fs-1 d-block mb-2 opacity-25"></i>Aucun livre enregistré</td></tr>
                <?php else: foreach ($livres as $l): ?>
                <tr>
                    <td class="fw-medium"><?= e($l['titre']) ?><?php if ($l['isbn']): ?><br><span class="text-muted font-monospace" style="font-size:.7rem;"><?= e($l['isbn']) ?></span><?php endif; ?></td>
                    <td><?= e($l['auteur'] ?? '—') ?></td>
                    <td class="text-muted"><?= e($l['categorie'] ?? '—') ?></td>
                    <td><span class="badge <?= $l['exemplaires_dispo'] > 0 ? 'bg-success' : 'bg-danger' ?> bg-opacity-15 <?= $l['exemplaires_dispo'] > 0 ? 'text-success' : 'text-danger' ?>"><?= $l['exemplaires_dispo'] ?></span></td>
                    <td><?= $l['exemplaires_total'] ?></td>
                    <td>
                        <?php if (can('bibliotheque.gerer') && $l['exemplaires_dispo'] > 0): ?>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#emprunterModal" data-livre-id="<?= $l['id'] ?>" data-livre-titre="<?= e($l['titre']) ?>">
                            <i class="bi bi-box-arrow-right me-1"></i>Prêter
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajouter livre -->
<?php if (can('bibliotheque.gerer')): ?>
<div class="modal fade" id="addLivreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Ajouter un livre</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST" action="<?= url('/bibliotheque/livre') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">Titre <span class="text-danger">*</span></label><input type="text" name="titre" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Auteur</label><input type="text" name="auteur" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">ISBN</label><input type="text" name="isbn" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Éditeur</label><input type="text" name="editeur" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Année</label><input type="number" name="annee_edition" class="form-control" placeholder="Ex: 2020"></div>
                        <div class="col-md-6"><label class="form-label">Catégorie</label><input type="text" name="categorie" class="form-control" placeholder="Ex: Roman, Maths…"></div>
                        <div class="col-md-6"><label class="form-label">Nb exemplaires</label><input type="number" name="exemplaires" class="form-control" value="1" min="1"></div>
                        <div class="col-12"><label class="form-label">Localisation</label><input type="text" name="localisation" class="form-control" placeholder="Ex: Étagère A3"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-primary">Ajouter</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Emprunter -->
<div class="modal fade" id="emprunterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Enregistrer un emprunt</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST" action="<?= url('/bibliotheque/emprunter') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="livre_id" id="empruntLivreId">
                <div class="modal-body">
                    <p class="text-muted small">Livre : <strong id="empruntLivreTitre"></strong></p>
                    <div class="mb-3">
                        <label class="form-label">Utilisateur <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <?php
                            $db = \App\Core\Database::getInstance();
                            $etabId = \App\Core\Auth::etablissementId();
                            $users = $db->prepare("SELECT id, prenoms, nom FROM users WHERE etablissement_id=? AND actif=1 ORDER BY nom");
                            $users->execute([$etabId]);
                            foreach ($users->fetchAll() as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= e($u['prenoms'].' '.$u['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date de retour prévue <span class="text-danger">*</span></label>
                        <input type="date" name="date_retour_prevu" class="form-control" value="<?= date('Y-m-d', strtotime('+14 days')) ?>" required>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-primary">Enregistrer</button></div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('emprunterModal').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('empruntLivreId').value = btn.dataset.livreId;
    document.getElementById('empruntLivreTitre').textContent = btn.dataset.livreTitre;
});
</script>
<?php endif; ?>
