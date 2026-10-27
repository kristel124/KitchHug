{{-- Follow Modal --}}
<div id="follow-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div id="follow-modal-backdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="relative flex w-full max-w-md flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
         style="max-height: 80vh;"
         role="dialog" aria-modal="true" aria-labelledby="follow-modal-title">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 id="follow-modal-title" class="text-base font-bold text-slate-900"></h2>
            <button id="follow-modal-close" type="button"
                    class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                <svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true">
                    <path d="M6 18 18 6M6 6l12 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="flex border-b border-slate-100 px-4 pt-2">
            <button id="follow-modal-tab-followers" type="button"
                    class="follow-modal-tab mr-1 rounded-t-lg px-4 py-2 text-sm font-bold transition"
                    data-tab="followers">
                Followers
            </button>
            <button id="follow-modal-tab-following" type="button"
                    class="follow-modal-tab rounded-t-lg px-4 py-2 text-sm font-bold transition"
                    data-tab="following">
                Following
            </button>
        </div>

        {{-- Search --}}
        <div class="border-b border-slate-100 px-4 py-3">
            <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                <svg viewBox="0 0 24 24" fill="none" class="size-4 shrink-0 text-slate-400" aria-hidden="true">
                    <path d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <input id="follow-modal-search" type="search" placeholder="Search..."
                       class="min-w-0 flex-1 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400">
            </div>
        </div>

        {{-- List --}}
        <div id="follow-modal-body" class="overflow-y-auto">
            <div id="follow-modal-loading" class="flex items-center justify-center py-12">
                <svg class="size-6 animate-spin text-orange-400" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const modal       = document.getElementById('follow-modal');
    const backdrop    = document.getElementById('follow-modal-backdrop');
    const closeBtn    = document.getElementById('follow-modal-close');
    const title       = document.getElementById('follow-modal-title');
    const body        = document.getElementById('follow-modal-body');
    const loading     = document.getElementById('follow-modal-loading');
    const tabs        = document.querySelectorAll('.follow-modal-tab');

    let currentUserId   = null;
    let currentTab      = 'followers';

    const searchInput = document.getElementById('follow-modal-search');

    function setActiveTab(tab) {
        currentTab = tab;
        tabs.forEach(function (t) {
            const isActive = t.dataset.tab === tab;
            t.classList.toggle('border-b-2', isActive);
            t.classList.toggle('border-orange-500', isActive);
            t.classList.toggle('text-orange-600', isActive);
            t.classList.toggle('text-slate-500', !isActive);
        });
        title.textContent = tab === 'followers' ? 'Followers' : 'Following';
    }

    function loadTab(userId, tab) {
        setActiveTab(tab);
        searchInput.value = '';
        body.innerHTML = '';
        body.appendChild(loading.cloneNode(true));
        const url = '/' + (tab === 'followers' ? 'users/' + userId + '/followers' : 'users/' + userId + '/following');
        fetch(url, { headers: { 'X-Requested-With': 'fetch' } })
            .then(function (r) { return r.text(); })
            .then(function (html) { body.innerHTML = html; })
            .catch(function () { body.innerHTML = '<p class="px-5 py-8 text-center text-sm text-slate-400">Failed to load.</p>'; });
    }

    function openModal(userId, tab) {
        currentUserId = userId;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        searchInput.value = '';
        searchInput.focus();
        loadTab(userId, tab);
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        searchInput.value = '';
    }

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase().trim();
        body.querySelectorAll('[data-person-row]').forEach(function (row) {
            const name = (row.dataset.personName || '').toLowerCase();
            row.style.display = name.includes(query) ? '' : 'none';
        });
    });

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () { loadTab(currentUserId, tab.dataset.tab); });
    });

    closeBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

    // Expose globally so profile pages can call it
    window.openFollowModal = openModal;
})();
</script>
