<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Messagerie</li></ol>

<div class="page-header">
    <h1><i class="bi bi-chat-dots-fill text-primary me-2"></i>Messagerie
        <?php if ($nonLus > 0): ?>
        <span class="badge bg-danger fs-6 ms-1"><?= $nonLus ?></span>
        <?php endif; ?>
    </h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMessageModal">
        <i class="bi bi-plus-lg me-1"></i>Nouveau message
    </button>
</div>

<!-- Onglets -->
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'recus' ? 'active' : '' ?>" href="?tab=recus">
            <i class="bi bi-inbox me-1"></i>Reçus
            <?php if ($nonLus > 0): ?><span class="badge bg-danger ms-1"><?= $nonLus ?></span><?php endif; ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'envoyes' ? 'active' : '' ?>" href="?tab=envoyes">
            <i class="bi bi-send me-1"></i>Envoyés
        </a>
    </li>
</ul>

<?php if ($tab === 'recus'): ?>
<div class="table-card">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr><th>Expéditeur</th><th>Sujet</th><th>Date</th><th>Statut</th><th></th></tr>
        </thead>
        <tbody>
        <?php if (empty($messages)): ?>
        <tr><td colspan="5" class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>Aucun message reçu
        </td></tr>
        <?php else: foreach ($messages as $m): ?>
        <tr class="<?= !$m['lu'] ? 'fw-semibold bg-primary bg-opacity-5' : '' ?>">
            <td>
                <?php if (!$m['lu']): ?><i class="bi bi-circle-fill text-primary me-1" style="font-size:.5rem;vertical-align:middle;"></i><?php endif; ?>
                <?= e(($m['exp_prenom']??'').' '.($m['exp_nom']??'')) ?>
            </td>
            <td><?= e($m['sujet'] ?? '(Sans sujet)') ?></td>
            <td class="text-muted small"><?= dateTimeFormat($m['created_at']) ?></td>
            <td>
                <?= $m['lu']
                    ? '<span class="badge bg-light text-muted border"><i class="bi bi-check2-all me-1"></i>Lu</span>'
                    : '<span class="badge bg-primary bg-opacity-15 text-primary">Non lu</span>' ?>
            </td>
            <td>
                <a href="<?= url('/messages/'.$m['id']) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>Lire
                </a>
            </td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<?php else: ?>
<div class="table-card">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr><th>Destinataire</th><th>Sujet</th><th>Date</th><th></th></tr>
        </thead>
        <tbody>
        <?php if (empty($envoyes)): ?>
        <tr><td colspan="4" class="text-center py-5 text-muted">
            <i class="bi bi-send fs-1 d-block mb-2 opacity-25"></i>Aucun message envoyé
        </td></tr>
        <?php else: foreach ($envoyes as $m): ?>
        <tr>
            <td><?= e(($m['dest_prenom']??'').' '.($m['dest_nom']??'')) ?></td>
            <td><?= e($m['sujet'] ?? '(Sans sujet)') ?></td>
            <td class="text-muted small"><?= dateTimeFormat($m['created_at']) ?></td>
            <td>
                <a href="<?= url('/messages/'.$m['id']) ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-eye me-1"></i>Voir
                </a>
            </td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<!-- Modal nouveau message -->
<div class="modal fade" id="newMessageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Nouveau message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= url('/messages/envoyer') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Destinataire <span class="text-danger">*</span></label>
                        <select name="destinataire_id" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <?php
                            $db = \App\Core\Database::getInstance();
                            $etabId = \App\Core\Auth::etablissementId();
                            $users = $db->prepare("SELECT u.id, u.prenoms, u.nom FROM users u WHERE u.etablissement_id=? AND u.actif=1 AND u.id != ? ORDER BY u.nom, u.prenoms");
                            $users->execute([$etabId, \App\Core\Auth::id()]);
                            foreach ($users->fetchAll() as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= e($u['prenoms'].' '.$u['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sujet</label>
                        <input type="text" name="sujet" class="form-control" placeholder="Sujet du message">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="contenu" class="form-control" rows="5" required placeholder="Votre message…"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
