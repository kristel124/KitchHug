<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KitchHug</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f2eadf] font-sans text-slate-800 antialiased">
    @hasSection('content')
        @yield('content')
    @else
        {{ $slot }}
    @endif

    {{-- Reactions modal --}}
    <div id="reactions-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div id="reactions-modal-backdrop" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
        <div class="relative flex w-full max-w-sm flex-col overflow-hidden rounded-2xl bg-white shadow-2xl" style="max-height:70vh" role="dialog" aria-modal="true" aria-labelledby="reactions-modal-title">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 id="reactions-modal-title" class="flex items-center gap-2 text-base font-bold text-slate-900">
                    <span class="text-rose-500">♥</span> Hearts
                </h2>
                <button id="reactions-modal-close" type="button" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100">
                    <svg viewBox="0 0 24 24" fill="none" class="size-5"><path d="M6 18 18 6M6 6l12 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </div>
            <div id="reactions-modal-body" class="overflow-y-auto">
                <div id="reactions-modal-loading" class="flex items-center justify-center py-12">
                    <svg class="size-6 animate-spin text-rose-400" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var modal    = document.getElementById('reactions-modal');
        var backdrop = document.getElementById('reactions-modal-backdrop');
        var closeBtn = document.getElementById('reactions-modal-close');
        var body     = document.getElementById('reactions-modal-body');
        var loading  = document.getElementById('reactions-modal-loading');

        window.openReactionsModal = function (recipeId) {
            body.innerHTML = '';
            body.appendChild(loading.cloneNode(true));
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            fetch('/recipes/' + recipeId + '/hearts', { headers: { 'X-Requested-With': 'fetch' } })
                .then(function (r) { return r.text(); })
                .then(function (html) { body.innerHTML = html; })
                .catch(function () { body.innerHTML = '<p class="px-5 py-8 text-center text-sm text-slate-400">Failed to load.</p>'; });
        };

        function close() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        closeBtn.addEventListener('click', close);
        backdrop.addEventListener('click', close);
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
    })();
    </script>
</body>
</html>
