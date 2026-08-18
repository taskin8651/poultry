<div class="modal fade" id="notificationsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:16px; overflow:hidden; border:none;">
            <div class="modal-header" style="background:#EE7D21; color:#fff; border:none;">
                <h5 class="modal-title"><i class="far fa-bell me-2"></i> Notifications</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="notif-list" class="list-group list-group-flush">
                    <div class="text-center text-muted py-5">No notifications yet.</div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <small class="text-muted" id="notif-count-label"></small>
                <div class="d-flex gap-2">
                    <a href="{{ route('notifications.page') }}" class="btn btn-sm btn-outline-secondary">View all</a>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="notif-mark-all">Mark all as read</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var bellBtns   = document.querySelectorAll('.notif-bell-btn');
    var badgeEls   = document.querySelectorAll('.notif-unread-badge');
    var list       = document.getElementById('notif-list');
    var countLabel = document.getElementById('notif-count-label');
    var markAllBtn = document.getElementById('notif-mark-all');
    var csrf       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var iconMap  = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
    var colorMap = { success: '#16a34a', error: '#dc2626', warning: '#d97706', info: '#2563eb' };

    function updateBadges(count) {
        badgeEls.forEach(function (b) {
            if (count > 0) {
                b.textContent = count > 99 ? '99+' : count;
                b.classList.remove('d-none');
            } else {
                b.classList.add('d-none');
            }
        });
    }

    function escapeText(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function renderList(items) {
        list.innerHTML = '';

        if (!items.length) {
            list.innerHTML = '<div class="text-center text-muted py-5">No notifications yet.</div>';
            countLabel.textContent = '';
            return;
        }

        var unread = items.filter(function (n) { return !n.read; }).length;
        countLabel.textContent = unread + ' unread';

        items.forEach(function (n) {
            var row = document.createElement('div');
            row.className = 'list-group-item';
            row.style.cursor = 'pointer';
            row.style.borderLeft = '4px solid ' + (colorMap[n.type] || colorMap.info);
            if (!n.read) { row.style.background = '#fff7ed'; }

            row.innerHTML =
                '<div class="d-flex gap-2">' +
                    '<i class="far ' + (iconMap[n.type] || iconMap.info) + ' mt-1" style="color:' + (colorMap[n.type] || colorMap.info) + '"></i>' +
                    '<div class="flex-grow-1">' +
                        '<div class="fw-semibold small">' + escapeText(n.title) + '</div>' +
                        '<div class="small text-muted">' + escapeText(n.message) + '</div>' +
                        '<div class="small text-muted mt-1">' + escapeText(n.time) + '</div>' +
                    '</div>' +
                '</div>';

            row.addEventListener('click', function () {
                fetch('/notifications/' + n.id + '/read', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                }).then(function () {
                    if (n.url) {
                        window.location.href = n.url;
                    } else {
                        loadNotifications();
                    }
                });
            });

            list.appendChild(row);
        });
    }

    function loadNotifications() {
        fetch('{{ route('notifications.index') }}', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                updateBadges(data.unread_count);
                renderList(data.notifications);
            })
            .catch(function () {});
    }

    bellBtns.forEach(function (btn) { btn.addEventListener('click', loadNotifications); });

    if (markAllBtn) {
        markAllBtn.addEventListener('click', function () {
            fetch('{{ route('notifications.readAll') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            }).then(loadNotifications);
        });
    }

    fetch('{{ route('notifications.index') }}', { headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (data) { updateBadges(data.unread_count); })
        .catch(function () {});

    setInterval(function () {
        fetch('{{ route('notifications.index') }}', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) { updateBadges(data.unread_count); })
            .catch(function () {});
    }, 60000);
})();
</script>
