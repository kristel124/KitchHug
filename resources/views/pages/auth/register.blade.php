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
                strength: 0,
                label: '',
                color: '',
                check(val) {
                    let s = 0;
                    if (val.length >= 8) s++;
                    if (/[A-Z]/.test(val)) s++;
                    if (/[0-9]/.test(val)) s++;
                    if (/[^A-Za-z0-9]/.test(val)) s++;
                    this.strength = s;
                    this.label = ['', 'Weak', 'Fair', 'Good', 'Strong'][s];
                    this.color = ['', 'bg-red-400', 'bg-amber-400', 'bg-yellow-400', 'bg-green-500'][s];
                }
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
