<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Appearance settings')] class extends Component {
    //
}; ?>

<section class="flex min-h-screen w-full flex-col bg-[#FAF6F0] dark:bg-slate-900">
    @include('partials.kitchhug_header')

    @include('partials.settings-heading')

    <flux:heading level="2" class="sr-only">{{ __('Appearance settings') }}</flux:heading>

    <main class="flex-1">
        <x-pages::settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
            <div
                x-data="{
                    appearance: localStorage.getItem('appearance') ?? 'light',
                    apply(value) {
                        this.appearance = value;
                        localStorage.setItem('appearance', value);
                        if (value === 'dark' || (value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                        $flux.appearance = value;
                    }
                }"
                x-init="apply(appearance)"
            >
                <flux:radio.group variant="segmented" x-model="appearance" @change="apply(appearance)">
                    <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                    <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                    <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
                </flux:radio.group>
                <p class="mt-3 text-xs text-slate-400">Your preference is saved automatically.</p>
            </div>
        </x-pages::settings.layout>
    </main>

    @include('partials.kitchhug_footer')
</section>