@extends('layouts.app')

@section('content')
    @php($isEditing = $recipe->exists)

    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <section class="border-b border-orange-100 bg-gradient-to-b from-orange-100/80 to-[#FAF6F0]">
                <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                    <a href="{{ route('profile.show') }}" class="text-sm font-bold text-orange-600 transition hover:text-orange-700">← Back to profile</a>
                    <p class="mt-6 text-xs font-extrabold uppercase tracking-[0.2em] text-orange-500">Share your kitchen</p>
                    <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900">{{ $isEditing ? 'Edit your recipe' : 'Post a new recipe' }}</h1>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Add the details home cooks need to make your dish.</p>
                </div>
            </section>

            <section class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <form method="POST" enctype="multipart/form-data" action="{{ $isEditing ? route('recipes.update', $recipe) : route('recipes.store') }}" class="space-y-6 rounded-3xl border border-orange-100 bg-white p-6 shadow-sm sm:p-8">
                    @csrf
                    @if ($isEditing)
                        @method('PUT')
                    @endif

                    <div>
                        <label for="title" class="block text-sm font-bold text-slate-700">Recipe name</label>
                        <input id="title" name="title" value="{{ old('title', $recipe->title) }}" required maxlength="120" class="mt-2 w-full rounded-xl border border-orange-100 px-4 py-3 text-sm outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        @error('title') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="category" class="block text-sm font-bold text-slate-700">Category</label>
                            <select id="category" name="category" required class="mt-2 w-full rounded-xl border border-orange-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                <option value="">Choose a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" @selected(old('category', $recipe->category) === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="prep_time_minutes" class="block text-sm font-bold text-slate-700">Prep and cook time (minutes)</label>
                            <input id="prep_time_minutes" name="prep_time_minutes" type="number" min="1" max="1440" value="{{ old('prep_time_minutes', $recipe->prep_time_minutes) }}" required class="mt-2 w-full rounded-xl border border-orange-100 px-4 py-3 text-sm outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                            @error('prep_time_minutes') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-bold text-slate-700">Short description</label>
                        <textarea id="description" name="description" rows="3" maxlength="2000" required class="mt-2 w-full rounded-xl border border-orange-100 px-4 py-3 text-sm outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">{{ old('description', $recipe->description) }}</textarea>
                        @error('description') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="photo" class="block text-sm font-bold text-slate-700">Recipe photo @unless ($isEditing)<span class="text-rose-600">(required)</span>@endunless</label>
                        @if ($isEditing)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->image_path) }}" alt="Current photo for {{ $recipe->title }}" class="mt-3 h-48 w-full rounded-2xl object-cover sm:w-72">
                            <p class="mt-2 text-xs text-slate-500">Choose a new image only if you want to replace the current photo.</p>
                        @else
                            <p class="mt-1 text-xs text-slate-500">Add a clear photo of your finished dish. JPG, PNG, or WebP up to 5 MB.</p>
                        @endif
                        <input id="photo" name="photo" type="file" accept="image/*" @required(!$isEditing) class="mt-2 block w-full rounded-xl border border-orange-100 bg-white px-4 py-3 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-orange-50 file:px-3 file:py-2 file:font-bold file:text-orange-600">
                        @error('photo') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="video" class="block text-sm font-bold text-slate-700">Recipe video <span class="font-normal text-slate-400">(optional)</span></label>
                        @if ($isEditing && $recipe->video_path)
                            <video controls preload="metadata" class="mt-3 max-h-72 w-full rounded-2xl bg-slate-900" aria-label="Current video for {{ $recipe->title }}">
                                <source src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->video_path) }}">
                                Your browser does not support video playback.
                            </video>
                            <p class="mt-2 text-xs text-slate-500">Choose another video to replace this one.</p>
                        @endif
                        <p class="mt-1 text-xs text-slate-500">Upload a video of your recipe if you have one. MP4, MOV, or WebM up to 50 MB.</p>
                        <input id="video" name="video" type="file" accept="video/mp4,video/quicktime,video/webm" class="mt-2 block w-full rounded-xl border border-orange-100 bg-white px-4 py-3 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-orange-50 file:px-3 file:py-2 file:font-bold file:text-orange-600">
                        @error('video') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="ingredients" class="block text-sm font-bold text-slate-700">Ingredients</label>
                        <textarea id="ingredients" name="ingredients" rows="5" required placeholder="Add one ingredient per line" class="mt-2 w-full rounded-xl border border-orange-100 px-4 py-3 text-sm outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">{{ old('ingredients', $recipe->ingredients) }}</textarea>
                        @error('ingredients') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="instructions" class="block text-sm font-bold text-slate-700">Instructions</label>
                        <textarea id="instructions" name="instructions" rows="7" required placeholder="Write the steps in order" class="mt-2 w-full rounded-xl border border-orange-100 px-4 py-3 text-sm outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">{{ old('instructions', $recipe->instructions) }}</textarea>
                        @error('instructions') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                        <a href="{{ route('profile.show') }}" class="inline-flex items-center justify-center rounded-xl border border-orange-100 px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-orange-50">Cancel</a>
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-600">{{ $isEditing ? 'Save changes' : 'Publish recipe' }}</button>
                    </div>
                </form>
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection
