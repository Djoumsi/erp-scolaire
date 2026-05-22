<ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">Accueil</a></li><li class="breadcrumb-item active">Notifications</li></ol>

<div class="page-header">
    <h1><i class="bi bi-bell-fill text-primary me-2"></i>Notifications</h1>
</div>

<div class="table-card">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Notification</th><th>Date</th><th>Statut</th></tr></thead>
        <tbody>
        <?php if (empty($notifications)): ?>
        <tr><td colspan="3" class="text-center py-5 text-muted"><i class="bi bi-bell-slash fs-1 d-block mb-2 opacity-25"></i>Aucune notification</td></tr>
        <?php else: foreach ($notifications as $n): ?>
        <tr class="<?= !$n['lu'] ? 'fw-semibold' : '' ?>">
            <td>
                <div><?= e($n['titre']) ?></div>
                <?php if ($n['contenu']): ?>
                <div class="text-muted small"><?= e($n['contenu']) ?></div>
                <?php endif; ?>
            </td>
            <td class="text-muted small"><?= dateFormat($n['created_at']) ?></td>
            <td>
                <?php if ($n['lu']): ?>
                <span class="badge bg-light text-muted border">Lu</span>
                <?php else: ?>
                <span class="badge bg-primary bg-opacity-15 text-primary">Nouveau</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
