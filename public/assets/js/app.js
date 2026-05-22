/* ERP Scolaire — JS principal */

document.addEventListener('DOMContentLoaded', function () {

    // -------------------------------------------------------
    // Sidebar toggle
    // -------------------------------------------------------
    const toggleBtn = document.getElementById('sidebarToggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                document.body.classList.toggle('sidebar-open');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', document.body.classList.contains('sidebar-collapsed'));
            }
        });
    }

    // Restaurer état sidebar
    if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 768) {
        document.body.classList.add('sidebar-collapsed');
    }

    // Fermer sidebar mobile en cliquant en dehors
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 768 && document.body.classList.contains('sidebar-open')) {
            const sidebar = document.getElementById('sidebar');
            if (sidebar && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                document.body.classList.remove('sidebar-open');
            }
        }
    });

    // -------------------------------------------------------
    // Auto-dismiss alerts
    // -------------------------------------------------------
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });

    // -------------------------------------------------------
    // Notifications
    // -------------------------------------------------------
    loadNotifications();
    setInterval(loadNotifications, 60000); // toutes les 60s

    async function loadNotifications() {
        try {
            const base = window.ERP_BASE || '';
            const res  = await fetch(base + '/notifications?json=1');
            const data = await res.json();
            const badge = document.getElementById('notifBadge');
            const list  = document.getElementById('notifList');
            if (!badge || !list) return;

            if (data.count > 0) {
                badge.textContent = data.count > 9 ? '9+' : data.count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }

            if (data.items && data.items.length > 0) {
                list.innerHTML = data.items.map(n => `
                    <a href="${n.lien || '#'}" class="dropdown-item py-2 border-bottom" onclick="markRead(${n.id})">
                        <div class="small fw-semibold">${escHtml(n.titre)}</div>
                        <div class="text-muted small">${escHtml(n.contenu)}</div>
                    </a>
                `).join('');
            } else {
                list.innerHTML = '<div class="p-3 text-muted text-center small">Aucune notification</div>';
            }
        } catch (e) {
            // Silencieux si API indisponible
        }
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------
    window.escHtml = function (str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    };

    window.markRead = async function (id) {
        await fetch(`/notifications/lire/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `_token=${getCSRF()}`
        });
    };

    function getCSRF() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    }

    // -------------------------------------------------------
    // Confirmation suppression
    // -------------------------------------------------------
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm || 'Êtes-vous sûr ?')) {
                e.preventDefault();
            }
        });
    });

    // -------------------------------------------------------
    // Preview photo upload
    // -------------------------------------------------------
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', function () {
            const preview = document.getElementById(this.dataset.preview);
            if (preview && this.files[0]) {
                preview.src = URL.createObjectURL(this.files[0]);
                preview.style.display = 'block';
            }
        });
    });

    // -------------------------------------------------------
    // Filtres tableaux en temps réel
    // -------------------------------------------------------
    const searchInputs = document.querySelectorAll('[data-table-search]');
    searchInputs.forEach(input => {
        input.addEventListener('input', function () {
            const tableId = this.dataset.tableSearch;
            const table   = document.getElementById(tableId);
            if (!table) return;
            const q     = this.value.toLowerCase();
            const rows  = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    });

    // -------------------------------------------------------
    // Tooltips Bootstrap
    // -------------------------------------------------------
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
});
