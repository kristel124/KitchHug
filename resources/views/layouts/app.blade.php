<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KitchHug</title>
    {{-- Apply saved appearance before paint to avoid flash --}}
    <script>
        (function () {
            var appearance = localStorage.getItem('appearance') || 'light';
            if (appearance === 'dark' || (appearance === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f2eadf] font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100">
    {{-- Flash toast notification --}}
    @if (session('status'))
        <div
            id="flash-toast"
            role="alert"
            aria-live="polite"
            class="fixed top-5 left-1/2 z-[999] -translate-x-1/2 flex items-center gap-3 rounded-2xl bg-white border border-green-200 px-5 py-3.5 shadow-lg shadow-slate-900/10 transition-all duration-500"
        >
            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true">
                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <p class="text-sm font-semibold text-slate-800">{{ session('status') }}</p>
        </div>
        <script>
            (function () {
                var toast = document.getElementById('flash-toast');
                if (!toast) return;
                setTimeout(function () {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(-50%) translateY(-12px)';
                    setTimeout(function () { toast.remove(); }, 500);
                }, 3000);
            })();
        </script>
    @endif

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
    {{-- Hugsy AI Chat Widget (authenticated users only) --}}
    @auth
    <div id="ai-chat-widget">
        {{-- Floating trigger button --}}
        <button
            id="ai-chat-toggle"
            type="button"
            aria-label="Chat with Hugsy"
            aria-expanded="false"
            aria-controls="ai-chat-panel"
            class="fixed bottom-6 right-6 z-[60] flex size-14 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 transition hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2"
        >
            {{-- Messenger icon (shown when closed) --}}
            <span id="ai-chat-icon-open" aria-hidden="true" class="flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 0 0-1.032-.211 50.89 50.89 0 0 0-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 0 0 2.433 3.984L7.28 21.53A.75.75 0 0 1 6 21v-4.03a48.527 48.527 0 0 1-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979Z" />
                    <path d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 0 0 1.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0 0 15.75 7.5Z" />
                </svg>
            </span>
            {{-- Close icon (shown when open) --}}
            <svg id="ai-chat-icon-close" viewBox="0 0 24 24" fill="none" class="hidden size-5" aria-hidden="true">
                <path d="M6 18 18 6M6 6l12 12" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            </svg>
        </button>

        {{-- Chat panel --}}
        <div
            id="ai-chat-panel"
            role="dialog"
            aria-modal="true"
            aria-labelledby="ai-chat-title"
            style="display:none; flex-direction:column; max-height:min(70dvh,560px);"
            class="fixed bottom-24 right-6 z-[60] w-[22rem] overflow-hidden rounded-3xl border border-orange-100 bg-white shadow-2xl shadow-orange-900/10"
        >
            {{-- Header --}}
            <div class="flex items-center gap-3 bg-gradient-to-r from-orange-500 to-orange-400 px-5 py-4">
                <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-white">
                        <path d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 0 0-1.032-.211 50.89 50.89 0 0 0-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 0 0 2.433 3.984L7.28 21.53A.75.75 0 0 1 6 21v-4.03a48.527 48.527 0 0 1-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979Z" />
                        <path d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 0 0 1.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0 0 15.75 7.5Z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 id="ai-chat-title" class="text-sm font-black text-white tracking-tight">Hugsy</h2>
                    <p class="text-[11px] text-orange-100 font-medium">Your KitchHug cooking assistant</p>
                </div>
                <button id="ai-chat-close-btn" type="button" aria-label="Close Hugsy" class="flex size-8 shrink-0 items-center justify-center rounded-full bg-white/20 text-white transition hover:bg-white/30">
                    <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true">
                        <path d="M6 18 18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            {{-- Quick suggestion chips --}}
            <div id="ai-chat-suggestions" class="flex gap-2 overflow-x-auto px-4 py-3 bg-orange-50/60 border-b border-orange-100 [&::-webkit-scrollbar]:hidden" style="scrollbar-width:none;">
                <button type="button" data-suggestion="What can I cook with chicken and garlic?" class="ai-suggestion-chip shrink-0 rounded-full border border-orange-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-orange-700 transition hover:bg-orange-50 hover:border-orange-300">🍗 Chicken ideas</button>
                <button type="button" data-suggestion="Give me a quick 15-minute dinner recipe." class="ai-suggestion-chip shrink-0 rounded-full border border-orange-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-orange-700 transition hover:bg-orange-50 hover:border-orange-300">⚡ Quick dinner</button>
                <button type="button" data-suggestion="What's a good substitute for butter in baking?" class="ai-suggestion-chip shrink-0 rounded-full border border-orange-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-orange-700 transition hover:bg-orange-50 hover:border-orange-300">🔄 Substitutes</button>
                <button type="button" data-suggestion="How do I fix an over-salted dish?" class="ai-suggestion-chip shrink-0 rounded-full border border-orange-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-orange-700 transition hover:bg-orange-50 hover:border-orange-300">🧂 Fix my dish</button>
                <button type="button" data-suggestion="Give me a simple Filipino dessert recipe." class="ai-suggestion-chip shrink-0 rounded-full border border-orange-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-orange-700 transition hover:bg-orange-50 hover:border-orange-300">🍮 Filipino dessert</button>
            </div>

            {{-- Messages area --}}
            <div id="ai-chat-messages" class="flex-1 overflow-y-auto bg-[#FAF6F0] p-4 space-y-3 [&::-webkit-scrollbar]:hidden" style="scrollbar-width:none; min-height:220px; max-height:320px;">
                {{-- Greeting bubble --}}
                <div class="flex items-end gap-2 justify-start">
                    <div class="flex size-7 shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm">🍳</div>
                    <div class="max-w-[80%] rounded-2xl rounded-bl-sm bg-white px-4 py-3 text-sm leading-6 text-slate-700 shadow-sm border border-orange-50">
                        Hi{{ auth()->user() ? ', ' . auth()->user()->name : '' }}! 👋 I'm <strong>Hugsy</strong>, your KitchHug cooking assistant. Ask me anything about recipes, ingredients, or cooking tips!
                    </div>
                </div>
            </div>

            {{-- Input area --}}
            <div class="border-t border-orange-100 bg-white p-3">
                <form id="ai-chat-form" class="flex gap-2 items-end">
                    @csrf
                    <textarea
                        id="ai-chat-input"
                        name="message"
                        rows="1"
                        maxlength="1000"
                        placeholder="Ask Hugsy anything..."
                        class="flex-1 resize-none rounded-2xl border border-orange-200 bg-orange-50/50 px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:bg-white"
                        style="min-height:40px; max-height:120px; overflow-y:auto;"
                    ></textarea>
                    <button
                        id="ai-chat-send"
                        type="submit"
                        aria-label="Send message"
                        class="flex size-10 shrink-0 items-center justify-center rounded-2xl bg-orange-500 text-white transition hover:bg-orange-600 disabled:opacity-40"
                    >
                        <svg viewBox="0 0 24 24" fill="currentColor" class="size-4" aria-hidden="true"><path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z"/></svg>
                    </button>
                </form>
                <p class="mt-1.5 text-center text-[10px] text-slate-400">Enter to send · Shift+Enter for new line</p>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var toggle      = document.getElementById('ai-chat-toggle');
        var panel       = document.getElementById('ai-chat-panel');
        var closeBtn    = document.getElementById('ai-chat-close-btn');
        var iconOpen    = document.getElementById('ai-chat-icon-open');
        var iconClose   = document.getElementById('ai-chat-icon-close');
        var messages    = document.getElementById('ai-chat-messages');
        var form        = document.getElementById('ai-chat-form');
        var input       = document.getElementById('ai-chat-input');
        var sendBtn     = document.getElementById('ai-chat-send');
        var suggestions = document.getElementById('ai-chat-suggestions');
        var history     = [];

        var tokenInput = form.querySelector('input[name="_token"]');
        var csrfToken  = tokenInput ? tokenInput.value : '';

        function openPanel() {
            panel.style.display = 'flex';
            toggle.setAttribute('aria-expanded', 'true');
            iconOpen.classList.add('hidden');
            iconClose.classList.remove('hidden');
            input.focus();
            scrollToBottom();
        }

        function closePanel() {
            panel.style.display = 'none';
            toggle.setAttribute('aria-expanded', 'false');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
        }

        function isOpen() { return panel.style.display === 'flex'; }

        toggle.addEventListener('click', function () { isOpen() ? closePanel() : openPanel(); });
        closeBtn.addEventListener('click', closePanel);
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && isOpen()) closePanel(); });

        function scrollToBottom() {
            messages.scrollTop = messages.scrollHeight;
        }

        function parseMarkdown(text) {
            return text
                // Bold **text** or __text__
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/__(.+?)__/g, '<strong>$1</strong>')
                // Italic *text* or _text_ (single, not double)
                .replace(/(?<!\*)\*(?!\*)(.+?)(?<!\*)\*(?!\*)/g, '<em>$1</em>')
                // Bullet list lines starting with * or -
                .replace(/^[\*\-] (.+)$/gm, '<li>$1</li>')
                // Numbered list lines
                .replace(/^\d+\. (.+)$/gm, '<li>$1</li>')
                // Wrap consecutive <li> in <ul>
                .replace(/(<li>[\s\S]*?<\/li>)/g, '<ul class="list-disc pl-4 space-y-1 my-1">$1</ul>')
                // Line breaks
                .replace(/\n/g, '<br>');
        }

        function appendBubble(text, isUser) {
            // Hide suggestion chips after first user message
            if (isUser && suggestions) {
                suggestions.style.display = 'none';
            }

            var wrap = document.createElement('div');
            wrap.className = 'flex items-end gap-2 ' + (isUser ? 'justify-end' : 'justify-start');

            if (!isUser) {
                var avatar = document.createElement('div');
                avatar.className = 'flex size-7 shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm';
                avatar.textContent = '🍳';
                wrap.appendChild(avatar);
            }

            var bubble = document.createElement('div');
            bubble.className = 'max-w-[80%] rounded-2xl px-4 py-3 text-sm leading-6 shadow-sm whitespace-pre-wrap break-words border '
                + (isUser
                    ? 'rounded-br-sm bg-orange-500 text-white border-orange-400'
                    : 'rounded-bl-sm bg-white text-slate-700 border-orange-50');
            if (isUser) {
                bubble.textContent = text;
            } else {
                bubble.innerHTML = parseMarkdown(text);
            }

            wrap.appendChild(bubble);
            messages.appendChild(wrap);
            scrollToBottom();
            return bubble;
        }

        function appendTypingIndicator() {
            var wrap = document.createElement('div');
            wrap.id = 'ai-typing';
            wrap.className = 'flex items-end gap-2 justify-start';

            var avatar = document.createElement('div');
            avatar.className = 'flex size-7 shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm';
            avatar.textContent = '🍳';
            wrap.appendChild(avatar);

            var bubble = document.createElement('div');
            bubble.className = 'rounded-2xl rounded-bl-sm bg-white px-4 py-3 text-sm text-slate-400 shadow-sm border border-orange-50 flex items-center gap-1';
            bubble.innerHTML = '<span class="inline-block size-1.5 rounded-full bg-orange-400 animate-bounce" style="animation-delay:0ms"></span>'
                             + '<span class="inline-block size-1.5 rounded-full bg-orange-400 animate-bounce" style="animation-delay:150ms"></span>'
                             + '<span class="inline-block size-1.5 rounded-full bg-orange-400 animate-bounce" style="animation-delay:300ms"></span>';

            wrap.appendChild(bubble);
            messages.appendChild(wrap);
            scrollToBottom();
        }

        function removeTypingIndicator() {
            var el = document.getElementById('ai-typing');
            if (el) el.remove();
        }

        // Suggestion chips
        document.querySelectorAll('.ai-suggestion-chip').forEach(function (chip) {
            chip.addEventListener('click', function () {
                input.value = chip.dataset.suggestion;
                input.dispatchEvent(new Event('input'));
                form.dispatchEvent(new Event('submit', { cancelable: true }));
            });
        });

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

        function sendMessage(message) {
            if (!message) return;

            history.push({ role: 'user', text: message });
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
                body: JSON.stringify({ message: message, history: history.slice(-10) }),
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                removeTypingIndicator();
                var reply = data.reply || 'Sorry, I could not get a response.';
                history.push({ role: 'model', text: reply });
                appendBubble(reply, false);
            })
            .catch(function () {
                removeTypingIndicator();
                appendBubble('Something went wrong. Please try again.', false);
            })
            .finally(function () {
                sendBtn.disabled = false;
                input.focus();
            });
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            sendMessage(input.value.trim());
        });
    })();
    </script>
    @endauth

</body>
</html>
