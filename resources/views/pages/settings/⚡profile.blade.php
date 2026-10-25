<?php

use App\Concerns\ProfileValidationRules;
use App\Concerns\PasswordValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.app')] #[Title('Profile settings')] class extends Component {
    use ProfileValidationRules;
    use PasswordValidationRules;

    public string $name = '';
    public string $email = '';
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(variant: 'success', text: __('Profile updated.'));
    }

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $exception) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $exception;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        Flux::toast(variant: 'success', text: __('Password updated.'));
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
    @include('partials.kitchhug_header')

    <main>
        <section class="relative isolate overflow-hidden border-b border-orange-100 bg-gradient-to-r from-[#faf7f2] via-[#fffaf0] to-[#fff0dc]">
            <div class="pointer-events-none absolute -right-16 -top-20 -z-10 h-72 w-72 rounded-full bg-orange-200/40 blur-3xl"></div>
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 rounded-full border border-orange-200/80 bg-white/70 px-4 py-2 text-sm font-bold text-orange-700 shadow-sm transition hover:bg-white">
                    <span aria-hidden="true">←</span> Back to profile
                </a>
                <p class="mt-5 text-xs font-extrabold uppercase tracking-[0.2em] text-orange-600">Your account</p>
                <h1 class="mt-1 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Edit your profile</h1>
                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-600 sm:text-base">Keep your details up to date and make your KitchHug account feel like yours.</p>
            </div>
        </section>

        <section class="mx-auto grid max-w-7xl items-start gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[16rem_minmax(0,1fr)] lg:px-8 lg:py-10">
            <aside class="space-y-5 lg:sticky lg:top-28">
                <div class="rounded-3xl border border-orange-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-orange-100 bg-slate-900 text-sm font-black text-white">
                            @if (Auth::user()->profile_photo_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(Auth::user()->profile_photo_path) }}" alt="" class="h-full w-full object-cover">
                            @else
                                {{ Auth::user()->initials() }}
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-black text-slate-900">{{ Auth::user()->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <nav aria-label="Account settings" class="mt-5 space-y-1 border-t border-orange-100 pt-4">
                        <a href="{{ route('profile.edit') }}" aria-current="page" class="flex items-center gap-3 rounded-xl bg-orange-50 px-3 py-2.5 text-sm font-bold text-orange-700">
                            <span aria-hidden="true">♙</span> Profile settings
                        </a>
                        <a href="{{ route('security.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-orange-50 hover:text-orange-700">
                            <span aria-hidden="true">⌑</span> Security
                        </a>
                        <a href="{{ route('appearance.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-orange-50 hover:text-orange-700">
                            <span aria-hidden="true">☼</span> Appearance
                        </a>
                    </nav>
                </div>

                <div class="rounded-3xl bg-slate-900 p-5 text-white shadow-lg shadow-slate-900/10">
                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-orange-300">A little reminder</p>
                    <p class="mt-3 text-sm leading-6 text-slate-300">A friendly photo and up-to-date details help your cooking community recognize you.</p>
                </div>
            </aside>

            <div class="min-w-0 space-y-6">
                @if (session('status'))
                    <div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
                @endif

                <section class="overflow-hidden rounded-3xl border border-orange-100 bg-white shadow-sm">
                    <div class="border-b border-orange-100 bg-gradient-to-r from-orange-50/80 to-amber-50/50 px-5 py-5 sm:px-7">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-orange-600">Make it yours</p>
                        <h2 class="mt-1 text-xl font-black text-slate-900">Profile photo</h2>
                        <p class="mt-1 text-sm text-slate-500">Click your photo to choose a new one. It will upload automatically.</p>
                    </div>
                    <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:gap-6 sm:p-7">
                        @csrf
                        <label for="profile_photo" class="group relative flex h-24 w-24 shrink-0 cursor-pointer items-center justify-center overflow-hidden rounded-full border-4 border-white bg-slate-900 text-2xl font-black text-white shadow-lg ring-2 ring-orange-100 sm:h-28 sm:w-28" aria-label="Choose a new profile photo">
                            @if (Auth::user()->profile_photo_path)
                                <img data-profile-photo-preview src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }} profile photo" class="h-full w-full object-cover">
                            @else
                                <span data-profile-photo-initials>{{ Auth::user()->initials() }}</span>
                                <img data-profile-photo-preview alt="{{ Auth::user()->name }} profile photo preview" class="hidden h-full w-full object-cover">
                            @endif
                            <span class="absolute inset-0 flex flex-col items-center justify-center gap-1 rounded-full bg-slate-950/30 text-white transition group-hover:bg-slate-950/60">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-7 w-7" aria-hidden="true"><path d="M14 4h-4L8 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-4l-2-3Z"></path><circle cx="12" cy="13" r="3"></circle></svg>
                                <span class="text-[9px] font-bold leading-none">Change</span>
                            </span>
                        </label>
                        <input id="profile_photo" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" required class="sr-only">
                        <div class="sm:max-w-sm">
                            <p class="text-sm font-bold text-slate-800">Show the community who’s cooking</p>
                            <p class="mt-1 text-sm leading-6 text-slate-500">Choose a JPG, PNG, or WebP image up to 5 MB. Your new photo appears here as soon as you select it.</p>
                            @error('profile_photo')
                                <p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </form>
                </section>

                <section class="rounded-3xl border border-orange-100 bg-white p-5 shadow-sm sm:p-7">
                    <div class="mb-6 flex items-start gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-orange-100 text-lg font-black text-orange-700" aria-hidden="true">✎</span>
                        <div>
                            <h2 class="text-xl font-black text-slate-900">Personal information</h2>
                            <p class="mt-1 text-sm text-slate-500">Update the name and email connected to your account.</p>
                        </div>
                    </div>
                    <form wire:submit="updateProfileInformation" class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-bold text-slate-700">Name</label>
                            <input id="name" name="name" wire:model="name" type="text" required autofocus autocomplete="name" class="mt-2 w-full rounded-2xl border border-orange-100 bg-[#FFFCF8] px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 hover:border-orange-200 focus:border-orange-300 focus:bg-white focus:ring-4 focus:ring-orange-100/70">
                            @error('name') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-bold text-slate-700">Email address</label>
                            <input id="email" name="email" wire:model="email" type="email" required autocomplete="email" class="mt-2 w-full rounded-2xl border border-orange-100 bg-[#FFFCF8] px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 hover:border-orange-200 focus:border-orange-300 focus:bg-white focus:ring-4 focus:ring-orange-100/70">
                            @error('email') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror

                            @if ($this->hasUnverifiedEmail)
                                <p class="mt-3 text-sm text-amber-700">
                                    Your email address is unverified.
                                    <button type="button" wire:click.prevent="resendVerificationNotification" class="font-bold underline underline-offset-2">Resend verification email</button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p role="status" class="mt-2 text-sm font-semibold text-emerald-700">A new verification link has been sent to your email address.</p>
                                @endif
                            @endif
                        </div>

                        <div class="flex justify-end border-t border-orange-100 pt-5">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-3 text-sm font-extrabold text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-200" data-test="update-profile-button">
                                Save changes <span aria-hidden="true">→</span>
                            </button>
                        </div>
                    </form>
                </section>

                <section class="rounded-3xl border border-orange-100 bg-white p-5 shadow-sm sm:p-7">
                    <div class="mb-6 flex items-start gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-lg font-black text-amber-700" aria-hidden="true">⌑</span>
                        <div>
                            <h2 class="text-xl font-black text-slate-900">Change password</h2>
                            <p class="mt-1 text-sm text-slate-500">Use your current password to set a new one.</p>
                        </div>
                    </div>
                    <form wire:submit="updatePassword" class="space-y-5">
                        <div>
                            <label for="current_password" class="block text-sm font-bold text-slate-700">Current password</label>
                            <input id="current_password" name="current_password" wire:model="current_password" type="password" required autocomplete="current-password" class="mt-2 w-full rounded-2xl border border-orange-100 bg-[#FFFCF8] px-4 py-3 text-sm text-slate-800 outline-none transition hover:border-orange-200 focus:border-orange-300 focus:bg-white focus:ring-4 focus:ring-orange-100/70">
                            @error('current_password') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-bold text-slate-700">New password</label>
                            <input id="password" name="password" wire:model="password" type="password" required autocomplete="new-password" passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" class="mt-2 w-full rounded-2xl border border-orange-100 bg-[#FFFCF8] px-4 py-3 text-sm text-slate-800 outline-none transition hover:border-orange-200 focus:border-orange-300 focus:bg-white focus:ring-4 focus:ring-orange-100/70">
                            @error('password') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-slate-700">Confirm new password</label>
                            <input id="password_confirmation" name="password_confirmation" wire:model="password_confirmation" type="password" required autocomplete="new-password" passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" class="mt-2 w-full rounded-2xl border border-orange-100 bg-[#FFFCF8] px-4 py-3 text-sm text-slate-800 outline-none transition hover:border-orange-200 focus:border-orange-300 focus:bg-white focus:ring-4 focus:ring-orange-100/70">
                            @error('password_confirmation') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex justify-end border-t border-orange-100 pt-5">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-sm font-extrabold text-white shadow-lg shadow-slate-900/10 transition hover:-translate-y-0.5 hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200" data-test="update-password-button">
                                Update password <span aria-hidden="true">→</span>
                            </button>
                        </div>
                    </form>
                </section>

                @if ($this->showDeleteUser)
                    <div class="rounded-3xl border border-rose-100 bg-white p-5 shadow-sm sm:p-7">
                        <livewire:pages::settings.delete-user-form />
                    </div>
                @endif
            </div>
        </section>
    </main>

    @include('partials.kitchhug_footer')
</div>
