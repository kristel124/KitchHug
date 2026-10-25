<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Hug | Your Digital Recipe Box</title>
    
    <!-- Tailwind CSS Script -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="min-h-full bg-orange-50/30 text-slate-800 flex flex-col selection:bg-orange-500 selection:text-white antialiased font-sans">

    <!-- Accessibility Skip Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:p-4 focus:bg-orange-500 focus:text-white">
        Skip to main content
    </a>

    <!-- ================= NAVIGATION ================= -->
    <header class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-md border-b border-orange-100/60 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-20 flex items-center justify-between gap-4">

                <!-- Logo -->
                <a href="#" class="flex items-center gap-3 group focus:outline-none focus:ring-2 focus:ring-orange-500 rounded-2xl p-1 shrink-0">
                    <div class="w-11 h-11 rounded-2xl bg-orange-500 flex items-center justify-center text-white text-xl shadow-md shadow-orange-500/20 group-hover:scale-105 group-hover:bg-orange-600 transition-all duration-300">
                        <span role="img" aria-label="Cooking pot">🍳</span>
                    </div>

                    <div class="leading-none">
                        <span class="text-2xl font-black tracking-tight text-slate-900">
                            Kitch<span class="text-orange-500">Hug</span>
                        </span>
                        <span class="block text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 mt-1">
                            Made with love
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
                    <a href="#explore" class="text-orange-500 hover:text-orange-600 transition-colors">
                        Explore Recipes
                    </a>
                    <a href="#categories" class="text-slate-600 hover:text-orange-500 transition-colors">
                        Categories
                    </a>
                    <a href="#about" class="text-slate-600 hover:text-orange-500 transition-colors">
                        About Us
                    </a>
                </nav>

                <!-- Authentication CTA -->
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <a href="{{ route('login') }}"
                       class="px-3 sm:px-5 py-2.5 text-xs sm:text-sm font-bold text-slate-700 rounded-xl hover:bg-orange-100/50 hover:text-orange-600 transition-all focus:outline-none focus:ring-2 focus:ring-orange-500">
                        Log In
                    </a>

                    <a href="{{ route('register') }}"
                       class="px-3 sm:px-5 py-2.5 text-xs sm:text-sm font-bold text-white bg-orange-500 rounded-xl shadow-sm shadow-orange-500/30 hover:bg-orange-600 hover:shadow-md hover:shadow-orange-500/40 transition-all focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                        Get Started
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- ================= HERO SECTION ================= -->
    <main id="main-content" class="grow">

        <section class="relative overflow-hidden bg-gradient-to-b from-orange-100/70 via-orange-50/50 to-transparent py-12 lg:py-20">

            <!-- Decorative Ambient Glows -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-orange-300/30 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 -left-24 w-80 h-80 bg-amber-200/40 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                    <!-- Hero Content -->
                    <div class="lg:col-span-7 max-w-2xl">

                        <div class="inline-flex items-center gap-2 px-4 py-2 mb-6 bg-white/80 border border-orange-200/60 rounded-full text-xs sm:text-sm font-bold text-orange-700 shadow-sm backdrop-blur-sm">
                            <span role="img" aria-label="Fork and knife">🍴</span>
                            <span>Your recipes, all in one place</span>
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] text-slate-900">
                            A little
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-500">hug</span>
                            for every recipe.
                        </h1>

                        <p class="mt-6 text-lg lg:text-xl text-slate-600 leading-relaxed max-w-xl">
                            Welcome to <strong>Kitchen Hug</strong> — your personal digital recipe box
                            for saving, organizing, and discovering delicious homemade meals.
                        </p>

                        <p class="mt-3 text-base text-slate-500 leading-relaxed max-w-xl">
                            Keep your favorite family recipes safe, discover new dishes,
                            and turn your kitchen ideas into beautiful, easy-to-follow cards.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

                            <a href="#explore"
                               class="inline-flex items-center justify-center gap-2 px-7 py-4 bg-orange-500 text-white font-bold text-base rounded-xl shadow-lg shadow-orange-500/25 hover:bg-orange-600 hover:shadow-orange-500/35 transition-all focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                Explore Recipes
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>

                            <a href="/register"
                               class="inline-flex items-center justify-center px-7 py-4 bg-white text-slate-800 font-bold text-base rounded-xl border border-orange-200/80 shadow-sm hover:bg-orange-50/50 hover:border-orange-300 transition-all focus:outline-none focus:ring-2 focus:ring-orange-500">
                                Start Cooking
                            </a>

                        </div>

                    </div>

                    <!-- Hero Illustration -->
                    <div class="lg:col-span-5 relative flex justify-center lg:justify-end">

                        <div class="relative w-full max-w-md">

                            <!-- Decorative Layer Cards -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-orange-400 to-amber-300 rounded-[2.5rem] rotate-3 opacity-20 transform scale-95"></div>

                            <div class="relative bg-white/80 rounded-[2.5rem] p-6 sm:p-8 shadow-2xl shadow-orange-900/10 border border-white/80 backdrop-blur-md">

                                <img
                                    src="https://cdni.iconscout.com/illustration/premium/thumb/female-chef-cooking-illustration-download-in-svg-png-gif-file-formats--woman-cook-kitchen-flat-illustrations-pack-people-3802381.png"
                                    alt="Illustration of a cheerful female chef preparing meals in a modern kitchen"
                                    class="w-full h-72 sm:h-80 object-contain drop-shadow-md"
                                    loading="eager"
                                    width="400"
                                    height="320"
                                >

                            </div>

                            <!-- Floating Recipe Card Badge -->
                            <div class="absolute -bottom-6 -left-4 sm:-left-6 bg-white/95 rounded-2xl shadow-xl shadow-slate-900/10 p-4 border border-orange-100 flex items-center gap-3.5 backdrop-blur-sm">

                                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl shadow-inner shrink-0">
                                    ❤️
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Today's Featured
                                    </p>

                                    <p class="font-extrabold text-slate-900 text-sm sm:text-base">
                                        Made with Love
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </section>

        <!-- ================= FEATURES ================= -->
        <section id="explore" class="bg-white py-20 lg:py-28 border-y border-orange-100/50">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Section Heading -->
                <div class="text-center max-w-2xl mx-auto">

                    <span class="text-xs font-extrabold uppercase tracking-widest text-orange-500 bg-orange-50 px-3 py-1 rounded-full border border-orange-100">
                        Why Kitchen Hug?
                    </span>

                    <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Everything your recipes need
                    </h2>

                    <p class="mt-3 text-base sm:text-lg text-slate-500 leading-relaxed">
                        Keep your everyday cooking simple, beautifully organized, and enjoyable.
                    </p>

                </div>

                <!-- Feature Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">

                    <!-- Card 1 -->
                    <div class="group p-8 bg-orange-50/30 border border-orange-100 rounded-3xl hover:bg-white hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/5 hover:-translate-y-1 transition-all duration-300">

                        <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                            📖
                        </div>

                        <h3 class="mt-6 text-xl font-bold text-slate-900">
                            Save Your Recipes
                        </h3>

                        <p class="mt-3 text-slate-600 leading-relaxed text-sm">
                            Store your favorite meals digitally so your family traditions
                            are always organized and accessible anywhere.
                        </p>

                    </div>

                    <!-- Card 2 -->
                    <div class="group p-8 bg-orange-50/30 border border-orange-100 rounded-3xl hover:bg-white hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/5 hover:-translate-y-1 transition-all duration-300">

                        <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                            🔎
                        </div>

                        <h3 class="mt-6 text-xl font-bold text-slate-900">
                            Discover New Meals
                        </h3>

                        <p class="mt-3 text-slate-600 leading-relaxed text-sm">
                            Browse curated recipes and discover new ideas for breakfast,
                            dinner, desserts, and quick weeknight meals.
                        </p>

                    </div>

                    <!-- Card 3 -->
                    <div class="group p-8 bg-orange-50/30 border border-orange-100 rounded-3xl hover:bg-white hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/5 hover:-translate-y-1 transition-all duration-300">

                        <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                            ❤️
                        </div>

                        <h3 class="mt-6 text-xl font-bold text-slate-900">
                            Cook with Love
                        </h3>

                        <p class="mt-3 text-slate-600 leading-relaxed text-sm">
                            Keep meaningful family recipes close at hand and create customizable 
                            step-by-step guides for cooking success.
                        </p>

                    </div>

                </div>

            </div>
        </section>

        <!-- ================= CTA ================= -->
        <section class="relative bg-orange-100/60 py-20 overflow-hidden">

            <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10">

                <div class="text-5xl mb-6 inline-block transform hover:scale-110 transition-transform">
                    🥘
                </div>

                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Ready to make something delicious?
                </h2>

                <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed max-w-xl mx-auto">
                    Start building your personal recipe box today and keep every favorite meal organized.
                </p>

                <a href="/register"
                   class="inline-flex items-center gap-2 mt-8 px-8 py-4 bg-orange-500 text-white font-bold text-base rounded-xl shadow-lg shadow-orange-500/25 hover:bg-orange-600 hover:shadow-orange-500/35 transition-all focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                    Create Your Recipe Box
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

            </div>

        </section>

    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-slate-900 text-slate-300 mt-auto">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <div class="flex flex-col md:flex-row items-center justify-between gap-6 border-b border-slate-800 pb-8">

                <div class="text-center md:text-left">
                    <a href="#" class="text-2xl font-black tracking-tight text-white inline-block">
                        Kitch<span class="text-orange-500">Hug</span>
                    </a>
                    <p class="mt-1 text-xs text-slate-400">
                        Digital recipe box — made with love.
                    </p>
                </div>

                <nav class="flex items-center gap-6 text-sm font-medium text-slate-400">
                    <a href="#" class="hover:text-orange-400 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-orange-400 transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-orange-400 transition-colors">Contact</a>
                </nav>

            </div>

            <div class="mt-8 text-center md:text-left text-xs text-slate-500">
                &copy; 2026 Kitchen Hug. All rights reserved.
            </div>

        </div>

    </footer>

</body>
</html>