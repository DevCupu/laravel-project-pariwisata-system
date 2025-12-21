<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');
        let isDesktop = window.innerWidth >= 1024;

        const showSidebar = () => {
            sidebar?.classList.remove('-translate-x-full');
            mobileOverlay?.classList.remove('hidden');
        };

        const hideSidebar = () => {
            sidebar?.classList.add('-translate-x-full');
            mobileOverlay?.classList.add('hidden');
        };

        sidebarToggle?.addEventListener('click', () => {
            const isHidden = sidebar?.classList.contains('-translate-x-full');
            if (isHidden) {
                showSidebar();
            } else {
                hideSidebar();
            }
        });

        mobileOverlay?.addEventListener('click', hideSidebar);

        window.addEventListener('resize', () => {
            const nextIsDesktop = window.innerWidth >= 1024;
            if (nextIsDesktop !== isDesktop) {
                isDesktop = nextIsDesktop;
                if (isDesktop) {
                    sidebar?.classList.remove('-translate-x-full');
                    mobileOverlay?.classList.add('hidden');
                } else {
                    hideSidebar();
                }
            }
        });

        if (userMenuButton && userMenuDropdown) {
            userMenuButton.addEventListener('click', event => {
                event.stopPropagation();
                userMenuDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', () => {
                userMenuDropdown.classList.add('hidden');
            });
        }

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && !isDesktop) {
                hideSidebar();
            }
        });
    });

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-emerald-500' : type === 'error' ? 'bg-rose-500' : 'bg-slate-600';
        toast.className = `${bgColor} text-white px-4 py-2 rounded-lg shadow flex items-center space-x-3 text-sm`;
        toast.innerHTML = `
            <span class="flex-1">${message}</span>
            <button type="button" aria-label="Close toast" class="text-white/80" onclick="this.parentElement.remove()">×</button>
        `;
        document.getElementById('toast-container')?.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }
</script>
