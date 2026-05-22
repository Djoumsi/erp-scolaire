<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item"><a href="<?= url('/annonces') ?>">Annonces</a></li><li class="breadcrumb-item active">Nouvelle annonce</li></ol>

<div class="page-header">
    <h1><i class="bi bi-megaphone-fill text-primary me-2"></i>Nouvelle annonce</h1>
</div>

<form method="POST" action="<?= url('/annonces') ?>">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="titre" class="form-control" value="<?= old('titre') ?>" required placeholder="Titre de l'annonce">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Contenu <span class="text-danger">*</span></label>
                        <textarea name="contenu" class="form-control" rows="8" required placeholder="Contenu de l'annonce…"><?= old('contenu') ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-card">
                <div class="form-section-title">Options</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Priorité</label>
                        <select name="priorite" class="form-select">
                            <option value="normale" <?= old('priorite','normale')==='normale'?'selected':'' ?>>Normale</option>
                            <option value="haute" <?= old('priorite')==='haute'?'selected':'' ?>>Haute</option>
                            <option value="basse" <?= old('priorite')==='basse'?'selected':'' ?>>Basse</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Destinataires</label>
                        <select name="cible" class="form-select">
                            <option value="tous">Tous</option>
                            <option value="enseignants" <?= old('cible')==='enseignants'?'selected':'' ?>>Enseignants</option>
                            <option value="eleves" <?= old('cible')==='eleves'?'selected':'' ?>>Élèves</option>
                            <option value="parents" <?= old('cible')==='parents'?'selected':'' ?>>Parents</option>
                            <option value="classe" <?= old('cible')==='classe'?'selected':'' ?>>Classe spécifique</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Classe (si ciblée)</label>
                        <select name="classe_id" class="form-select">
                            <option value="">— Aucune —</option>
                            <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= old('classe_id') == $c['id'] ? 'selected' : '' ?>><?= e($c['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Expiration</label>
                        <input type="datetime-local" name="expire_le" class="form-control" value="<?= old('expire_le') ?>">
                        <small class="text-muted">Laisser vide = sans expiration</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-send me-1"></i>Publier</button>
        <a href="<?= url('/annonces') ?>" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>
