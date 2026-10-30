<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div x-data="{
                val: '',
                strength: 0,
                label: '',
                color: '',
                check(v) {
                    this.val = v;
                    let s = 0;
                    if (v.length >= 8) s++;
                    if (/[A-Z]/.test(v)) s++;
                    if (/[0-9]/.test(v)) s++;
                    if (/[^A-Za-z0-9]/.test(v)) s++;
                    this.strength = s;
                    this.label = ['', 'Weak', 'Fair', 'Good', 'Strong'][s];
                    this.color = ['', 'bg-red-400', 'bg-amber-400', 'bg-yellow-400', 'bg-green-500'][s];
                },
                get hasLength() { return this.val.length >= 8; },
                get hasUpper()  { return /[A-Z]/.test(this.val); },
                get hasNumber() { return /[0-9]/.test(this.val); },
                get hasSymbol() { return /[^A-Za-z0-9]/.test(this.val); }
            }">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Password')"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable
                    x-on:input="check($event.target.value)"
                />

                {{-- Strength bar --}}
                <div x-show="strength > 0" x-cloak class="mt-2">
                    <div class="flex gap-1">
                        <template x-for="i in 4">
                            <div class="h-1.5 flex-1 rounded-full transition-all duration-300"
                                 :class="i <= strength ? color : 'bg-slate-200'"></div>
                        </template>
                    </div>
                    <p class="mt-1 text-xs font-semibold"
                       :class="{
                           'text-red-500': strength === 1,
                           'text-amber-500': strength === 2,
                           'text-yellow-600': strength === 3,
                           'text-green-600': strength === 4
                       }"
                       x-text="label"></p>
                </div>

                {{-- Password requirements --}}
                <ul class="mt-2 space-y-1">
                    <template x-for="req in [
                        { met: hasLength, text: 'At least 8 characters' },
                        { met: hasUpper,  text: 'At least one uppercase letter (A–Z)' },
                        { met: hasNumber, text: 'At least one number (0–9)' },
                        { met: hasSymbol, text: 'At least one special character (!@#$…)' }
                    ]">
                        <li class="flex items-center gap-1.5 text-xs transition-colors duration-200"
                            :class="req.met ? 'text-green-600' : 'text-slate-400'">
                            <svg class="size-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <template x-if="req.met">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/>
                                </template>
                                <template x-if="!req.met">
                                    <circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5" fill="none" opacity="0.4"/>
                                </template>
                            </svg>
                            <span x-text="req.text"></span>
                        </li>
                    </template>
                </ul>
            </div>
            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
