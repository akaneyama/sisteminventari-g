<script>
    function openImageModal(imageSrc) {
        const modal = document.getElementById('globalImageModal');
        const modalImg = document.getElementById('globalModalImage');
        modalImg.src = imageSrc;
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.remove('opacity-0'), 10);
    }

    function closeImageModal() {
        const modal = document.getElementById('globalImageModal');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('globalModalImage').src = '';
        }, 300);
    }

    document.getElementById('globalImageModal').addEventListener('click', function(e) {
        if (e.target === this) closeImageModal();
    });

    // ── Shared accessible modal helpers (openModal / closeModal) ────────────
    const __openModals = [];
    function openModal(el) {
        if (!el || !el.classList) return;
        el.__opener = document.activeElement;
        el.classList.remove('hidden');
        el.setAttribute('role', 'dialog');
        el.setAttribute('aria-modal', 'true');
        if (!el.hasAttribute('tabindex')) el.setAttribute('tabindex', '-1');
        if (__openModals.length === 0) document.body.style.overflow = 'hidden';
        if (__openModals.indexOf(el) === -1) __openModals.push(el);
        const focusable = el.querySelector('input:not([type="hidden"]), textarea, select, button, a[href], [tabindex]:not([tabindex="-1"])');
        if (focusable) focusable.focus();
    }
    function closeModal(el) {
        if (!el || !el.classList) return;
        el.classList.add('hidden');
        el.removeAttribute('role');
        el.removeAttribute('aria-modal');
        const idx = __openModals.indexOf(el);
        if (idx > -1) __openModals.splice(idx, 1);
        if (__openModals.length === 0) document.body.style.overflow = '';
        if (el.__opener && typeof el.__opener.focus === 'function') el.__opener.focus();
        el.__opener = null;
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && __openModals.length > 0) {
            closeModal(__openModals[__openModals.length - 1]);
        }
    });
    document.addEventListener('click', function(e) {
        const closer = e.target.closest('[data-close-modal]');
        if (closer) {
            const modal = closer.closest('.fixed.inset-0');
            if (modal) closeModal(modal);
            return;
        }
        __openModals.slice().forEach(function(m) {
            if (e.target === m) closeModal(m);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const toggleBtn = document.getElementById('mobileMenuBtn');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        backdrop.addEventListener('click', toggleSidebar);

        // User menu dropdown
        const userMenu = document.getElementById('userMenu');
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userMenuDropdown = document.getElementById('userMenuDropdown');

        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenuDropdown.classList.toggle('hidden');
            userMenuBtn.setAttribute('aria-expanded', !userMenuDropdown.classList.contains('hidden') ? 'true' : 'false');
        });

        document.addEventListener('click', function(e) {
            if (userMenu && !userMenu.contains(e.target)) {
                userMenuDropdown.classList.add('hidden');
                userMenuBtn.setAttribute('aria-expanded', 'false');
            }
        });

        // Notification dropdown (topbar bell)
        const notifMenu = document.getElementById('notifMenu');
        const notifBell = document.getElementById('notifBell');
        const notifDropdown = document.getElementById('notifDropdown');

        if (notifBell && notifDropdown) {
            notifBell.addEventListener('click', function(e) {
                e.stopPropagation();
                notifDropdown.classList.toggle('hidden');
            });
        }

        document.addEventListener('click', function(e) {
            if (notifMenu && !notifMenu.contains(e.target)) {
                notifDropdown.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                notifDropdown.classList.add('hidden');
                userMenuDropdown.classList.add('hidden');
                userMenuBtn.setAttribute('aria-expanded', 'false');
            }
        });

        // Render the notification bell dropdown + badge
        function renderNotif(items) {
            const el = document.getElementById('notifList');
            const badge = document.getElementById('notifBadge');
            const active = items.filter(i => i.count > 0);
            const total = active.reduce((sum, i) => sum + i.count, 0);
            if (total > 0) {
                badge.textContent = total;
                badge.classList.remove('hidden');
                badge.classList.add('flex');
            } else {
                badge.classList.add('hidden');
                badge.classList.remove('flex');
            }
            if (!el) return;
            if (active.length === 0) {
                el.innerHTML = '<div class="px-5 py-8 text-center text-sm text-gray-400">Tidak ada notifikasi. Semua beres!</div>';
                return;
            }
            el.innerHTML = active.map(i =>
                '<a href="' + i.href + '" class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors">' +
                    '<span class="text-sm font-medium text-gray-700 min-w-0">' + i.label + '</span>' +
                    '<span class="flex-shrink-0 px-2 py-0.5 text-xs font-bold text-white bg-red-500 rounded-full">' + i.count + '</span>' +
                '</a>'
            ).join('');
        }

        // Sidebar accordion (parent + nested ul)
        document.querySelectorAll('[data-menu-toggle]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const body = btn.nextElementSibling;
                const chevron = btn.querySelector('[data-menu-chevron]');
                if (body) {
                    const isHidden = body.classList.toggle('hidden');
                    if (chevron) chevron.classList.toggle('rotate-180', !isHidden);
                    btn.setAttribute('aria-expanded', String(!isHidden));
                }
            });
        });

        // Auto-expand the group containing the active menu item
        document.querySelectorAll('[data-menu-body]').forEach(function(body) {
            if (body.querySelector('a.bg-blue-50')) {
                body.classList.remove('hidden');
                const btn = body.previousElementSibling;
                const chevron = btn && btn.querySelector('[data-menu-chevron]');
                if (chevron) chevron.classList.add('rotate-180');
                if (btn) btn.setAttribute('aria-expanded', 'true');
            }
        });

        // Polling Notification for Kepsek
        @if(auth()->user()->role === 'Kepsek')
        function fetchNotifications() {
            fetch('{{ route("kepsek.notifications") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                const updateBadge = (id, count) => {
                    const el = document.getElementById(id);
                    if(el) {
                        if(count > 0) {
                            el.textContent = count;
                            el.classList.remove('hidden');
                        } else {
                            el.classList.add('hidden');
                        }
                    }
                };
                updateBadge('badge-pengadaan', data.pengadaan);
                updateBadge('badge-perubahan', data.perubahan);
                updateBadge('badge-mutasi', data.mutasi);
                updateBadge('badge-penghapusan', data.penghapusan);
                renderNotif([
                    { label: 'Persetujuan Pengadaan', href: '/kepsek/approval/pengadaan', count: data.pengadaan },
                    { label: 'Persetujuan Perubahan Data', href: '/kepsek/approval/perubahan', count: data.perubahan },
                    { label: 'Persetujuan Mutasi', href: '/kepsek/approval/mutasi', count: data.mutasi },
                    { label: 'Persetujuan Penghapusan', href: '/kepsek/approval', count: data.penghapusan },
                ]);
            })
            .catch(err => console.error('Error fetching notifications:', err));
        }
        fetchNotifications();
        setInterval(fetchNotifications, 15000);
        @endif

        // Polling Notification for Admin
        @if(auth()->user()->role === 'Admin')
        function fetchAdminNotifications() {
            fetch('{{ route("admin.notifications") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                const updateBadge = (id, count) => {
                    const el = document.getElementById(id);
                    if(el) {
                        if(count > 0) {
                            el.textContent = count;
                            el.classList.remove('hidden');
                        } else {
                            el.classList.add('hidden');
                        }
                    }
                };
                updateBadge('badge-admin-barang', data.barang);
                updateBadge('badge-admin-pengajuan', data.pengajuan);
                updateBadge('badge-admin-perbaikan', data.perbaikan);
                updateBadge('badge-admin-mutasi', data.mutasi);
                renderNotif([
                    { label: 'Barang Rusak Berat', href: '/admin/barang?kondisi=Rusak+Berat', count: data.barang },
                    { label: 'Pengajuan Disetujui (Proses Beli)', href: '/admin/pengajuan', count: data.pengajuan },
                    { label: 'Perbaikan Berjalan', href: '/admin/perbaikan', count: data.perbaikan },
                    { label: 'Mutasi Ditolak', href: '/admin/mutasi', count: data.mutasi },
                ]);
            })
            .catch(err => console.error('Error fetching admin notifications:', err));
        }
        fetchAdminNotifications();
        setInterval(fetchAdminNotifications, 15000);
        @endif
    });
</script>
