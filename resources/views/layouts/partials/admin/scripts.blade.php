<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');
        const confirmationModal = document.getElementById('confirmation-modal');
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

        // --- Confirmation modal (daisyUI modal) ---
        let pendingConfirmForm = null;

        const hideConfirmationModal = () => {
            confirmationModal?.classList.remove('modal-open');
            pendingConfirmForm = null;
        };

        const showConfirmationModal = (message, form) => {
            if (!confirmationModal) {
                if (window.confirm(message)) form.submit();
                return;
            }

            pendingConfirmForm = form;
            const messageEl = confirmationModal.querySelector('[data-confirm-message]');
            if (messageEl) messageEl.textContent = message;

            const confirmBtn = confirmationModal.querySelector('[data-confirm-accept]');
            if (confirmBtn) confirmBtn.disabled = false;

            confirmationModal.classList.add('modal-open');
        };

        document.addEventListener('submit', event => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) return;

            const message = form.getAttribute('data-confirm');
            if (!message) return;

            event.preventDefault();
            showConfirmationModal(message, form);
        });

        confirmationModal?.querySelector('[data-confirm-cancel]')?.addEventListener('click', hideConfirmationModal);

        confirmationModal?.querySelector('.modal-backdrop')?.addEventListener('click', hideConfirmationModal);

        confirmationModal?.querySelector('[data-confirm-accept]')?.addEventListener('click', event => {
            if (!pendingConfirmForm) return;

            const confirmBtn = event.currentTarget;
            confirmBtn.disabled = true;

            // Native submit() bypasses the submit event, so the data-confirm
            // listener above will not re-trigger this modal.
            pendingConfirmForm.submit();
        });

        document.addEventListener('keydown', event => {
            if (event.key !== 'Escape') return;

            if (confirmationModal?.classList.contains('modal-open')) {
                hideConfirmationModal();
                return;
            }

            if (!isDesktop) hideSidebar();
        });
    });

    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const icons = {
            success: 'fa-circle-check text-success',
            error: 'fa-circle-xmark text-error',
            info: 'fa-circle-info text-info',
        };
        const alerts = {
            success: 'alert-success',
            error: 'alert-error',
            info: 'alert-info',
        };

        const toast = document.createElement('div');
        toast.className = `admin-animate-toast alert ${alerts[type] ?? alerts.info} shadow-lg text-sm`;
        toast.innerHTML = `
            <i class="fas ${icons[type] ?? icons.info}"></i>
            <span class="flex-1">${message}</span>
            <button type="button" aria-label="Tutup notifikasi" class="btn btn-ghost btn-xs btn-circle">
                <i class="fas fa-xmark"></i>
            </button>
        `;

        const dismiss = () => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        };

        toast.querySelector('button')?.addEventListener('click', dismiss);
        container.appendChild(toast);
        setTimeout(dismiss, 4000);
    }
</script>
