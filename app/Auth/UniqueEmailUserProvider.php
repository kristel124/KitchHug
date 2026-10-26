<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Str;

class UniqueEmailUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials only when the email is unique.
     *
     * @param  array<string, mixed>  $credentials
     */
    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials)
    {
        $email = $credentials['email'] ?? null;

        if (is_string($email) && $this->emailHasDuplicates($email)) {
            return null;
        }

        return parent::retrieveByCredentials($credentials);
    }

    private function emailHasDuplicates(string $email): bool
    {
        return $this->createModel()->newQuery()
            ->whereRaw('LOWER(email) = ?', [Str::lower($email)])
            ->limit(2)
            ->count() > 1;
    }
}
