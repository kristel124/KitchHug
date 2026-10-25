@extends('layouts.app')

@section('content')
    @php($isEditing = $recipe->exists)

    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <section class="relative isolate overflow-hidden border-b border-orange-100 bg-gradient-to-r from-[#faf7f2] via-[#fffaf0] to-[#fff0dc]">
                <div class="pointer-events-none absolute -right-16 -top-20 -z-10 h-72 w-72 rounded-full bg-orange-200/40 blur-3xl"></div>
                <div class="mx-auto flex max-w-7xl items-center justify-between gap-8 px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                    <div>
                        <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 rounded-full border border-orange-200/80 bg-white/70 px-4 py-2 text-sm font-bold text-orange-700 shadow-sm transition hover:bg-white">
                            <span aria-hidden="true">←</span> Back to profile
                        </a>
                        <p class="mt-6 text-xs font-extrabold uppercase tracking-[0.2em] text-orange-600">A little taste of home</p>
                        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">{{ $isEditing ? 'Edit your recipe' : 'Post a new recipe' }}</h1>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-slate-600 sm:text-base">Every great dish has a story. Add yours and help someone find their new favorite.</p>
                    </div>
                    <div class="hidden shrink-0 rotate-3 items-center gap-4 rounded-3xl border border-white/80 bg-white/75 p-4 shadow-lg shadow-orange-900/5 sm:flex">
                        <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-100 to-amber-100 text-orange-600" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-9 w-9">
                                <path d="M4 11h16l-1.4 8H5.4L4 11Z"></path><path d="M3 11h18M7 7c0-1 1-1.5 1-2.5M12 7c0-1 1-1.5 1-2.5M17 7c0-1 1-1.5 1-2.5M8 19v2m8-2v2"></path>
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-black text-slate-900">Made with love</p>
                            <p class="mt-1 text-xs text-slate-500">Shared with the community</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
                <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_18rem]">
                    <form method="POST" enctype="multipart/form-data" action="{{ $isEditing ? route('recipes.update', $recipe) : route('recipes.store') }}" class="space-y-8 rounded-[2rem] border border-orange-100 bg-white p-5 shadow-xl shadow-orange-900/5 sm:p-8 lg:p-10">
                    @csrf
                    @if ($isEditing)
                        @method('PUT')
                    @endif

                    <div class="flex items-start gap-4 border-b border-orange-100 pb-6">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-orange-100 text-lg font-black text-orange-700">1</span>
                        <div class="w-full">
                            <h2 class="mt-1 text-xl font-black text-slate-900">What are we cooking?</h2>
                            <div class="mt-5">
                                <label for="title" class="block text-sm font-bold text-slate-700">Recipe name</label>
                                <input id="title" name="title" value="{{ old('title', $recipe->title) }}" required maxlength="120" placeholder="e.g. Grandma's Sunday tomato pasta" class="mt-2 w-full rounded-2xl border border-orange-100 bg-[#FFFCF8] px-4 py-3.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-orange-200 focus:border-orange-300 focus:bg-white focus:ring-4 focus:ring-orange-100/70">
                                @error('title') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 border-b border-orange-100 pb-8">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-lg font-black text-amber-700">2</span>
                        <div class="w-full">
                            <h2 class="mt-1 text-xl font-black text-slate-900">Help cooks find your dish</h2>
                            <p class="mt-1 text-sm text-slate-500">Choose a course, cooking method, and recipe collection.</p>
                            <div class="mt-5 grid gap-4 rounded-3xl border border-orange-100 bg-gradient-to-br from-orange-50/80 via-white to-amber-50/60 p-4 sm:grid-cols-2 sm:p-5 lg:grid-cols-3">
                                <div>
                                    <label for="category" class="flex items-center gap-2 text-sm font-bold text-slate-700"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-orange-100 text-orange-700" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><circle cx="12" cy="12" r="8"></circle><circle cx="12" cy="12" r="5"></circle><path d="M3 5v5m2-5v5m-2-2h2m14-3v5m2-5v5m-2-2h2"></path></svg></span>Course</label>
                                    <div class="relative mt-2">
                                        <select id="category" name="category" required class="w-full appearance-none rounded-2xl border border-orange-200/80 bg-white px-4 py-3.5 pr-11 text-sm font-semibold text-slate-700 shadow-sm outline-none transition hover:border-orange-300 focus:border-orange-400 focus:ring-4 focus:ring-orange-100/70">
                                            <option value="">Choose a course</option>
                                            @foreach ($courseCategories as $course)
                                                <option value="{{ $course }}" @selected(old('category', $recipe->category) === $course)>{{ $course }}</option>
                                            @endforeach
                                        </select>
                                        <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-orange-500" aria-hidden="true">⌄</span>
                                    </div>
                                    @error('category') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="method" class="flex items-center gap-2 text-sm font-bold text-slate-700"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 text-amber-700" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M12 22c4.4 0 7-3 7-7 0-3-1.8-5.2-4-7-.2 2-1.2 3.2-2.4 3.8C12.8 8.2 10 4.7 7 3c.4 4.5-2 6.6-2 11 0 4.8 2.8 8 7 8Z"></path><path d="M12 22c-2 0-3-1.5-3-3.2 0-1.4.8-2.6 2.2-3.8.2 1.4.8 2.1 1.7 2.6.7-1.2.9-2.1.8-3.1 1.5 1.3 2.3 2.6 2.3 4.1 0 2-1.4 3.4-4 3.4Z"></path></svg></span>Methods</label>
                                    <div class="relative mt-2">
                                        <select id="method" name="method" required class="w-full appearance-none rounded-2xl border border-orange-200/80 bg-white px-4 py-3.5 pr-11 text-sm font-semibold text-slate-700 shadow-sm outline-none transition hover:border-orange-300 focus:border-orange-400 focus:ring-4 focus:ring-orange-100/70">
                                            <option value="">Choose a method</option>
                                            @foreach ($methodCategories as $method)
                                                <option value="{{ $method }}" @selected(old('method', $recipe->method) === $method)>{{ $method }}</option>
                                            @endforeach
                                        </select>
                                        <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-orange-500" aria-hidden="true">⌄</span>
                                    </div>
                                    @error('method') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="sm:col-span-2 lg:col-span-1">
                                    <label for="recipe_type" class="flex items-center gap-2 text-sm font-bold text-slate-700"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-rose-100 text-rose-700" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M11 20A8 8 0 0 1 3 12c0-4.4 3.6-8 8-8h8a2 2 0 0 1 2 2v8a8 8 0 0 1-8 8h-2Z"></path><path d="M7 13c2 0 3-1 4-3m1 6c2 0 3-1 4-3"></path></svg></span>Recipes</label>
                                    <div class="relative mt-2">
                                        <select id="recipe_type" name="recipe_type" required class="w-full appearance-none rounded-2xl border border-orange-200/80 bg-white px-4 py-3.5 pr-11 text-sm font-semibold text-slate-700 shadow-sm outline-none transition hover:border-orange-300 focus:border-orange-400 focus:ring-4 focus:ring-orange-100/70">
                                            <option value="">Choose a recipe type</option>
                                            @foreach ($recipeCategories as $recipeCategory)
                                                <option value="{{ $recipeCategory }}" @selected(old('recipe_type', $recipe->recipe_type) === $recipeCategory)>{{ $recipeCategory }}</option>
                                            @endforeach
                                        </select>
                                        <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-orange-500" aria-hidden="true">⌄</span>
                                    </div>
                                    @error('recipe_type') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="mt-5 max-w-xs">
                                <label for="prep_time_minutes" class="block text-sm font-bold text-slate-700">Prep and cook time <span class="font-normal text-slate-400">(minutes)</span></label>
                                <input id="prep_time_minutes" name="prep_time_minutes" type="number" min="1" max="1440" value="{{ old('prep_time_minutes', $recipe->prep_time_minutes) }}" required placeholder="e.g. 30" class="mt-2 w-full rounded-2xl border border-orange-100 bg-[#FFFCF8] px-4 py-3.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-orange-200 focus:border-orange-300 focus:bg-white focus:ring-4 focus:ring-orange-100/70">
                                @error('prep_time_minutes') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4 flex items-start gap-4">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-lg font-black text-rose-700">3</span>
                            <div>
                                <h2 class="mt-1 text-xl font-black text-slate-900">Tell us what makes it special</h2>
                                <p class="mt-1 text-sm text-slate-500">A little description helps bring your recipe to life.</p>
                            </div>
                        </div>
                        <label for="description" class="block text-sm font-bold text-slate-700">Short description</label>
                        <textarea id="description" name="description" rows="3" maxlength="2000" required placeholder="What makes this dish worth sharing?" class="mt-2 w-full rounded-2xl border border-orange-100 bg-[#FFFCF8] px-4 py-3.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-orange-200 focus:border-orange-300 focus:bg-white focus:ring-4 focus:ring-orange-100/70">{{ old('description', $recipe->description) }}</textarea>
                        @error('description') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="rounded-3xl border border-dashed border-orange-200 bg-orange-50/40 p-5 sm:p-6">
                        <label for="photo" class="flex items-center gap-2 text-sm font-bold text-slate-700"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-orange-600" aria-hidden="true"><path d="M14 4h-4L8 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-4l-2-3Z"></path><circle cx="12" cy="13" r="3"></circle></svg>Recipe photo @unless ($isEditing)<span class="text-rose-600">(required)</span>@endunless</label>
                        @if ($isEditing)
                            <p class="mt-1 text-xs text-slate-500">Choose a new image to replace the current photo. Your new photo will preview here.</p>
                        @else
                            <p class="mt-1 text-xs text-slate-500">Add a clear photo of your finished dish. JPG, PNG, or WebP up to 5 MB.</p>
                        @endif
                        <input id="photo" name="photo" type="file" accept="image/*" @required(!$isEditing) class="mt-3 block w-full cursor-pointer rounded-2xl border border-orange-100 bg-white px-4 py-3 text-sm text-slate-600 file:mr-4 file:cursor-pointer file:rounded-xl file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:font-bold file:text-orange-700 hover:file:bg-orange-200">
                        <div class="mt-4 flex flex-wrap items-center gap-4">
                            <img data-photo-preview data-original-src="{{ $isEditing ? \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->image_path) : '' }}" @if ($isEditing) src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->image_path) }}" @endif alt="{{ $isEditing ? 'Current photo for '.$recipe->title : 'Selected recipe photo preview' }}" class="{{ $isEditing ? '' : 'hidden' }} h-28 w-36 rounded-2xl border border-orange-100 object-cover shadow-sm sm:h-32 sm:w-44">
                            <p data-photo-preview-status role="status" class="text-xs font-semibold leading-5 text-slate-500">{{ $isEditing ? 'Current recipe photo' : 'Your selected photo will appear here.' }}</p>
                        </div>
                        @error('photo') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50/70 p-5 sm:p-6">
                        <label for="video" class="flex items-center gap-2 text-sm font-bold text-slate-700"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-slate-600" aria-hidden="true"><rect x="3" y="5" width="13" height="14" rx="2"></rect><path d="m16 10 5-3v10l-5-3z"></path></svg>Recipe video <span class="font-normal text-slate-400">(optional)</span></label>
                        @if ($isEditing && $recipe->video_path)
                            <video controls preload="metadata" class="mt-3 max-h-72 w-full rounded-2xl bg-slate-900" aria-label="Current video for {{ $recipe->title }}">
                                <source src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->video_path) }}">
                                Your browser does not support video playback.
                            </video>
                            <p class="mt-2 text-xs text-slate-500">Choose another video to replace this one.</p>
                        @endif
                        <p class="mt-1 text-xs text-slate-500">Upload a video of your recipe if you have one. MP4, MOV, or WebM up to 200 MB.</p>
                        <input id="video" name="video" type="file" accept="video/mp4,video/quicktime,video/webm" class="mt-3 block w-full cursor-pointer rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 file:mr-4 file:cursor-pointer file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:font-bold file:text-slate-700 hover:file:bg-slate-200">
                        @error('video') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-start gap-4 border-b border-orange-100 pb-8">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-lg font-black text-emerald-700">4</span>
                        <div class="w-full space-y-5">
                            <div>
                                <h2 class="mt-1 text-xl font-black text-slate-900">Ingredients & instructions</h2>
                                <p class="mt-1 text-sm text-slate-500">Clear steps make it easy for anyone to cook along.</p>
                            </div>
                            <div>
                                <label for="ingredients" class="flex items-center gap-2 text-sm font-bold text-slate-700"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-emerald-700" aria-hidden="true"><path d="M12 22c4-4 7-8 7-12a7 7 0 0 0-14 0c0 4 3 8 7 12Z"></path><path d="M12 22V10m0 4c-2-2-4-2-5-2m5 0c2-2 4-2 5-2"></path></svg>Ingredients</label>
                                <textarea id="ingredients" name="ingredients" rows="5" required placeholder="Add one ingredient per line" class="mt-2 w-full rounded-2xl border border-orange-100 bg-[#FFFCF8] px-4 py-3.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-orange-200 focus:border-orange-300 focus:bg-white focus:ring-4 focus:ring-orange-100/70">{{ old('ingredients', $recipe->ingredients) }}</textarea>
                                @error('ingredients') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="instructions" class="flex items-center gap-2 text-sm font-bold text-slate-700"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-violet-700" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6M8 13h8m-8 4h8"></path></svg>Instructions</label>
                                <textarea id="instructions" name="instructions" rows="7" required placeholder="Write the steps in order" class="mt-2 w-full rounded-2xl border border-orange-100 bg-[#FFFCF8] px-4 py-3.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-orange-200 focus:border-orange-300 focus:bg-white focus:ring-4 focus:ring-orange-100/70">{{ old('instructions', $recipe->instructions) }}</textarea>
                                @error('instructions') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-orange-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs text-slate-400"><span class="text-rose-500">*</span> Required fields</p>
                        <div class="flex flex-col-reverse gap-3 sm:flex-row">
                            <a href="{{ route('profile.show') }}" class="inline-flex items-center justify-center rounded-xl border border-orange-100 px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-orange-50">Cancel</a>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-3 text-sm font-extrabold text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-200">{{ $isEditing ? 'Save changes' : 'Publish recipe' }} <span aria-hidden="true">→</span></button>
                        </div>
                    </div>
                    </form>

                    <aside class="space-y-5 lg:sticky lg:top-28">
                        <div class="relative isolate overflow-hidden rounded-[2rem] bg-slate-900 p-6 text-white shadow-xl shadow-slate-900/10">
                            <div class="pointer-events-none absolute -right-10 -top-10 -z-10 h-36 w-36 rounded-full bg-orange-500/30 blur-2xl"></div>
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-orange-300">From your kitchen</p>
                            <div class="mt-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-3xl" aria-hidden="true">🍲</div>
                            <h2 class="mt-5 text-xl font-black leading-tight">Good food brings people together.</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-300">Share the little details that make this dish yours, and give another cook the confidence to try it.</p>
                            <div class="mt-6 flex items-center gap-3 border-t border-white/10 pt-5">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-400/15 text-orange-300" aria-hidden="true">♡</span>
                                <p class="text-xs font-semibold leading-5 text-slate-300">Made with love, shared with the community.</p>
                            </div>
                        </div>

                        <div class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-sm">
                            <h2 class="text-sm font-black uppercase tracking-wide text-slate-800">A few helpful tips</h2>
                            <ul class="mt-5 space-y-4">
                                <li class="flex gap-3">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-black text-orange-700">1</span>
                                    <p class="pt-1 text-xs leading-5 text-slate-600">Give your recipe a name cooks will remember.</p>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-amber-100 text-xs font-black text-amber-700">2</span>
                                    <p class="pt-1 text-xs leading-5 text-slate-600">List ingredients one per line so they’re easy to follow.</p>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-black text-emerald-700">3</span>
                                    <p class="pt-1 text-xs leading-5 text-slate-600">Write instructions in the order you’d cook them.</p>
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection
