<?php

use Livewire\Component;

new class extends Component {}; ?>

<section class="space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <flux:heading>{{ __('Delete account') }}</flux:heading>
            <flux:subheading>{{ __('Delete your account and all of its resources') }}</flux:subheading>
        </div>


    </div>

    <livewire:pages::settings.delete-user-modal />
</section>
