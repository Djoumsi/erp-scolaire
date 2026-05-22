<div class="text-center py-5">
    <i class="bi bi-calendar-x fs-1 text-warning d-block mb-3"></i>
    <h3>Aucune année scolaire active</h3>
    <p class="text-muted mb-4">Configurez d'abord l'année scolaire en cours pour utiliser l'ERP.</p>
    <?php if (can('parametres.modifier')): ?>
    <a href="<?= url('/parametres') ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-gear me-2"></i>Aller aux paramètres
    </a>
    <?php endif; ?>
</div>
