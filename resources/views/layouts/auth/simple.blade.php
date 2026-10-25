<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light" data-flux-appearance="light">
    <head>
        @include('partials.head')
        <style>
            /* Direct override para sa body background */
            body {
                background: linear-gradient(to bottom, #FFF7ED, #FFFBEB, #FEF3C7) !important;
            }
            /* Direct override para sa inputs at button */
            [data-flux-control], input {
                background-color: #ffffff !important;
                color: #1e293b !important;
                border-color: #fde68a !important;
            }
            /* Direct override para sa login button */
            [data-test="login-button"], button[type="submit"] {
                background-color: #f97316 !important;
                color: #ffffff !important;
            }
            [data-test="login-button"]:hover, button[type="submit"]:hover {
                background-color: #ea580c !important;
            }
        </style>
        <script>
            localStorage.setItem('flux.appearance', 'light');
            document.documentElement.classList.remove('dark');
        </script>
    </head>
    <body class="min-h-screen antialiased font-sans text-slate-800" style="background: linear-gradient(to bottom, #FFF7ED, #FFFBEB, #FEF3C7) !important;">

        <div class="relative flex min-h-svh flex-col items-center justify-center p-6 md:p-10" style="background: transparent !important;">
            <div class="flex w-full max-w-md flex-col gap-6">
                @if (request()->routeIs('login', 'register'))
                    <a href="{{ route('home') }}" class="inline-flex w-fit items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-white/70 hover:text-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                        <span aria-hidden="true">←</span>
                        {{ __('Back to home') }}
                    </a>
                @endif

                <!-- Kitchen Hug Logo -->
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 group focus:outline-none" wire:navigate>
                    <div class="w-12 h-12 rounded-2xl bg-orange-500 flex items-center justify-center text-white text-2xl shadow-lg shadow-orange-500/20 group-hover:scale-105 transition-all">
                        🍳
                    </div>
                    <span class="text-2xl font-black tracking-tight text-slate-900">
                        Kitch<span class="text-orange-500">Hug</span>
                    </span>
                </a>

                <!-- Slot Content -->
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>

            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>