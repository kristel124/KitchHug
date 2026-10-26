<?php

use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    use PasswordValidationRules;

    public string $password = '';
    public bool $confirm_deletion = false;
    public bool $showConfirmation = false;

    public function openConfirmation(): void
    {
        $this->resetValidation();
        $this->reset('password', 'confirm_deletion');
        $this->showConfirmation = true;
    }

    public function cancelDeletion(): void
    {
        $this->showConfirmation = false;
        $this->reset('password', 'confirm_deletion');
        $this->resetValidation();
    }

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
            'confirm_deletion' => ['accepted'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <button type="button" wire:click="openConfirmation" data-test="delete-user-button" class="inline-flex w-full items-center justify-center rounded-xl bg-rose-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-200 sm:w-auto">
        {{ __('Delete account') }}
    </button>

    @if ($showConfirmation)
        <div class="fixed inset-0 z-[100] overflow-y-auto bg-slate-950/60 p-3 sm:flex sm:items-center sm:justify-center sm:p-6" wire:keydown.escape="cancelDeletion">
            <section role="alertdialog" aria-modal="true" aria-labelledby="delete-account-heading" aria-describedby="delete-account-description" class="mx-auto my-4 w-full max-w-lg rounded-2xl bg-white p-5 shadow-2xl sm:my-0 sm:rounded-3xl sm:p-8">
                <form method="POST" wire:submit="deleteUser" class="space-y-5 sm:space-y-6">
                    <div class="min-w-0">
                        <h2 id="delete-account-heading" class="text-xl font-black leading-tight text-slate-900 sm:text-2xl">{{ __('Are you sure you want to delete your account?') }}</h2>
                        <p id="delete-account-description" class="mt-3 break-words text-sm leading-6 text-slate-600">
                            {{ __('This action is permanent and all of your resources and data will be deleted. Enter your current password to continue.') }}
                        </p>
                    </div>

                    <div>
                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-rose-100 bg-rose-50/60 p-3 text-sm leading-5 text-slate-700">
                            <input type="checkbox" wire:model="confirm_deletion" class="mt-0.5 h-4 w-4 shrink-0 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            <span>{{ __('I understand that deleting my account is permanent.') }}</span>
                        </label>
                        @error('confirm_deletion')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="delete_account_password" class="block text-sm font-bold text-slate-700">{{ __('Current password') }}</label>
                        <input id="delete_account_password" name="password" wire:model="password" type="password" required autocomplete="current-password" class="mt-2 block w-full min-w-0 rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-800 outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('password')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:justify-end">
                        <button type="button" wire:click="cancelDeletion" class="inline-flex min-h-11 w-full items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50 sm:w-auto">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" wire:loading.attr="disabled" data-test="confirm-delete-user-button" class="inline-flex min-h-11 w-full items-center justify-center rounded-xl bg-rose-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-200 disabled:cursor-wait disabled:opacity-60 sm:w-auto">
                            <span wire:loading.remove wire:target="deleteUser">{{ __('Delete account') }}</span>
                            <span wire:loading wire:target="deleteUser">{{ __('Deleting...') }}</span>
                        </button>
                    </div>
                </form>
            </section>
        </div>
    @endif
</div>
