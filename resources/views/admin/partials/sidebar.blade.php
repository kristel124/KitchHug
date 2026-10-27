<aside class="flex w-full shrink-0 flex-col border-b border-orange-100 bg-white md:sticky md:top-0 md:h-screen md:w-64 md:border-b-0 md:border-r md:overflow-hidden">
    <div class="flex items-center justify-between px-6 py-5">
        <a href="{{ route('admin.dashboard') }}" class="text-xl font-black tracking-tight text-slate-900">
            Kitch<span class="text-orange-500">Hug</span>
            <span class="ml-1 text-xs font-bold uppercase tracking-wider text-slate-400">Admin</span>
        </a>
    </div>

    <nav aria-label="Admin navigation" class="grid grid-cols-2 gap-2 px-4 pb-4 md:flex md:flex-1 md:flex-col md:gap-1">
        <p class="hidden px-3 pb-2 pt-5 text-xs font-bold uppercase tracking-widest text-slate-400 md:block">Workspace</p>
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                  {{ request()->routeIs('admin.dashboard') ? 'bg-orange-50 font-bold text-orange-700' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-700' }}">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5">
                <path d="M3.75 10.5 12 3.75l8.25 6.75v9a.75.75 0 0 1-.75.75h-5.25v-6h-4.5v6H4.5a.75.75 0 0 1-.75-.75v-9Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                  {{ request()->routeIs('admin.users.*') ? 'bg-orange-50 font-bold text-orange-700' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-700' }}">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5">
                <path d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Users
        </a>
        <a href="{{ route('admin.recipes.index') }}"
           class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                  {{ request()->routeIs('admin.recipes.*') ? 'bg-orange-50 font-bold text-orange-700' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-700' }}">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5">
                <path d="M7.75 3.75h8.5v4.5a4.25 4.25 0 0 1-8.5 0v-4.5Zm0 2H4.5v1.5a4.25 4.25 0 0 0 4.25 4.25m7.5-5.75h3.25v1.5a4.25 4.25 0 0 1-4.25 4.25m-3.25 1v3.75m-3.5 4h7m-3.5-4a3.5 3.5 0 0 0-3.5 3.5v.5h7v-.5a3.5 3.5 0 0 0-3.5-3.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Recipes
        </a>
    </nav>
    <div class="hidden border-t border-slate-100 p-4 md:block">
        <div class="flex items-center gap-3 rounded-xl bg-[#FAF6F0] p-3">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm font-black text-orange-700">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                <p class="text-xs font-medium text-slate-500">Administrator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-slate-600 transition hover:bg-orange-50 hover:text-orange-700">
                Log out
            </button>
        </form>
    </div>
</aside>
