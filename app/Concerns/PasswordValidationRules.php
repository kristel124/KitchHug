<?php

namespace App\Concerns;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, Closure|Password|ValidationRule|array<mixed>|string>
     */
    protected function passwordRules(?string $email = null): array
    {
        $rules = ['required', 'string', Password::default(), 'confirmed'];

        if ($email !== null) {
            $rules[] = function (string $attribute, mixed $value, Closure $fail) use ($email): void {
                if (is_string($value) && strcasecmp($value, $email) === 0) {
                    $fail('The password must be different from your email address.');
                }
            };
        }

        return $rules;
    }

    /**
     * Get the validation rules used to validate the current password.
     *
     * @return array<int, Password|ValidationRule|array<mixed>|string>
     */
    protected function currentPasswordRules(): array
    {
        return ['required', 'string', 'current_password'];
    }
}
