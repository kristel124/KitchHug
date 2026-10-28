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
    {{-- AI Chat Widget (authenticated users only) --}}
    @auth
    <div id="ai-chat-widget">
        {{-- Floating trigger button --}}
        <button
            id="ai-chat-toggle"
            type="button"
            aria-label="Ask AI"
            aria-expanded="false"
            aria-controls="ai-chat-panel"
            class="fixed bottom-6 right-6 z-[60] flex size-14 items-center justify-center rounded-full bg-teal-700 text-white shadow-lg transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
        >
            {{-- AI icon (shown when closed) --}}
            <span id="ai-chat-icon-open" aria-hidden="true" class="text-xs font-black tracking-tight">AI</span>
            {{-- Close icon (shown when open) --}}
            <svg id="ai-chat-icon-close" viewBox="0 0 24 24" fill="none" class="hidden size-5" aria-hidden="true">
                <path d="M6 18 18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>

        {{-- Chat panel --}}
        <div
            id="ai-chat-panel"
            role="dialog"
            aria-modal="true"
            aria-labelledby="ai-chat-title"
            class="fixed bottom-24 right-6 z-[60] hidden w-[22rem] overflow-hidden rounded-2xl shadow-2xl"
            style="max-height: 70dvh; display: flex; flex-direction: column; display: none;"
        >
            {{-- Header --}}
            <div class="flex items-center justify-between bg-teal-700 px-5 py-4">
                <div>
                    <h2 id="ai-chat-title" class="text-base font-bold text-white">Ask AI</h2>
                    <p class="text-xs text-teal-200">Recipes, cooking tips &amp; more</p>
                </div>
                <button id="ai-chat-close-btn" type="button" aria-label="Close AI chat" class="flex size-8 items-center justify-center rounded-full bg-teal-600 text-white transition hover:bg-teal-500">
                    <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true">
                        <path d="M6 18 18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            {{-- Messages area --}}
            <div id="ai-chat-messages" class="flex-1 overflow-y-auto bg-slate-50 p-4 space-y-3" style="min-height: 280px; max-height: 360px;">
                {{-- Greeting bubble --}}
                <div class="flex justify-start">
                    <div class="max-w-[85%] rounded-2xl rounded-tl-sm bg-white px-4 py-3 text-sm leading-6 text-slate-700 shadow-sm">
                        Hi! Ask me anything about recipes, cooking tips, or ingredients. 🍳
                    </div>
                </div>
            </div>

            {{-- Input area --}}
            <div class="border-t border-slate-200 bg-white p-3">
                <form id="ai-chat-form" class="flex gap-2">
                    @csrf
                    <textarea
                        id="ai-chat-input"
                        name="message"
                        rows="1"
                        maxlength="1000"
                        placeholder="Ask about a recipe..."
                        class="flex-1 resize-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100"
                        style="min-height:40px; max-height:120px; overflow-y:auto;"
                    ></textarea>
                    <button
                        id="ai-chat-send"
                        type="submit"
                        class="flex items-center gap-1 rounded-xl bg-teal-700 px-4 py-2 text-sm font-bold text-white transition hover:bg-teal-800 disabled:opacity-50"
                    >Ask</button>
                </form>
                <p class="mt-1.5 text-center text-[10px] text-slate-400">Enter to send · Shift+Enter for a new line</p>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var toggle   = document.getElementById('ai-chat-toggle');
        var panel    = document.getElementById('ai-chat-panel');
        var closeBtn = document.getElementById('ai-chat-close-btn');
        var iconOpen = document.getElementById('ai-chat-icon-open');
        var iconClose= document.getElementById('ai-chat-icon-close');
        var messages = document.getElementById('ai-chat-messages');
        var form     = document.getElementById('ai-chat-form');
        var input    = document.getElementById('ai-chat-input');
        var sendBtn  = document.getElementById('ai-chat-send');
        var csrfToken = document.querySelector('input[name="_token"]') ? document.querySelector('input[name="_token"]').value : '';

        // Grab CSRF from the AI form's hidden input
        var tokenInput = form.querySelector('input[name="_token"]');
        if (tokenInput) csrfToken = tokenInput.value;

        function openPanel() {
            panel.style.display = 'flex';
            toggle.setAttribute('aria-expanded', 'true');
            iconOpen.classList.add('hidden');
            iconClose.classList.remove('hidden');
            input.focus();
        }

        function closePanel() {
            panel.style.display = 'none';
            toggle.setAttribute('aria-expanded', 'false');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
        }

        function isOpen() {
            return panel.style.display === 'flex';
        }

        toggle.addEventListener('click', function () {
            isOpen() ? closePanel() : openPanel();
        });

        closeBtn.addEventListener('click', closePanel);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && isOpen()) closePanel();
        });

        function scrollToBottom() {
            messages.scrollTop = messages.scrollHeight;
        }

        function appendBubble(text, isUser) {
            var wrap = document.createElement('div');
            wrap.className = 'flex ' + (isUser ? 'justify-end' : 'justify-start');

            var bubble = document.createElement('div');
            bubble.className = 'max-w-[85%] rounded-2xl px-4 py-3 text-sm leading-6 shadow-sm whitespace-pre-wrap break-words '
                + (isUser
                    ? 'rounded-tr-sm bg-teal-700 text-white'
                    : 'rounded-tl-sm bg-white text-slate-700');
            bubble.textContent = text;

            wrap.appendChild(bubble);
            messages.appendChild(wrap);
            scrollToBottom();
            return bubble;
        }

        function appendTypingIndicator() {
            var wrap = document.createElement('div');
            wrap.id = 'ai-typing';
            wrap.className = 'flex justify-start';

            var bubble = document.createElement('div');
            bubble.className = 'rounded-2xl rounded-tl-sm bg-white px-4 py-3 text-sm text-slate-400 shadow-sm';
            bubble.textContent = 'Thinking…';

            wrap.appendChild(bubble);
            messages.appendChild(wrap);
            scrollToBottom();
        }

        function removeTypingIndicator() {
            var el = document.getElementById('ai-typing');
            if (el) el.remove();
        }

        // Auto-grow textarea
        input.addEventListener('input', function () {
            input.style.height = 'auto';
            input.style.height = Math.min(input.scrollHeight, 120) + 'px';
        });

        // Enter to send, Shift+Enter for newline
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var message = input.value.trim();
            if (!message) return;

            appendBubble(message, true);
            input.value = '';
            input.style.height = 'auto';
            sendBtn.disabled = true;
            appendTypingIndicator();

            fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: message }),
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                removeTypingIndicator();
                appendBubble(data.reply || 'Sorry, I could not get a response.', false);
            })
            .catch(function () {
                removeTypingIndicator();
                appendBubble('Something went wrong. Please try again.', false);
            })
            .finally(function () {
                sendBtn.disabled = false;
                input.focus();
            });
        });
    })();
    </script>
    @endauth

</body>
</html>
