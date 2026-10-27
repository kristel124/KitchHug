<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Update at most once per minute to avoid a DB write on every request
            if (is_null($user->last_seen_at) || $user->last_seen_at->diffInMinutes(now()) >= 1) {
                $user->timestamps = false;
                $user->update(['last_seen_at' => now()]);
                $user->timestamps = true;
            }
        }

        return $next($request);
    }
}
