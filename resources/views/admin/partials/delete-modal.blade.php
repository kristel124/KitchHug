<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div id="delete-modal-backdrop" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl shadow-slate-900/20"
         role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div class="flex items-start gap-4">
            <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true">
                    <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <div>
                <h3 id="delete-modal-title" class="text-base font-bold text-slate-900"></h3>
                <p class="mt-1 text-sm text-slate-500" id="delete-modal-message"></p>
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button id="delete-modal-cancel" type="button"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                Cancel
            </button>
            <form id="delete-modal-form" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-600">
                    Yes, delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('delete-modal');
        const modalTitle = document.getElementById('delete-modal-title');
        const modalMessage = document.getElementById('delete-modal-message');
        const modalForm = document.getElementById('delete-modal-form');
        const modalBackdrop = document.getElementById('delete-modal-backdrop');
        const modalCancel = document.getElementById('delete-modal-cancel');

        function openDeleteModal(title, message, action) {
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            modalForm.action = action;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.querySelectorAll('[data-delete-trigger]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openDeleteModal(
                    btn.dataset.deleteTitle,
                    btn.dataset.deleteMessage,
                    btn.dataset.deleteAction
                );
            });
        });

        modalCancel.addEventListener('click', closeDeleteModal);
        modalBackdrop.addEventListener('click', closeDeleteModal);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { closeDeleteModal(); }
        });
    })();
</script>
