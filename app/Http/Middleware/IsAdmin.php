<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->user()?->is_admin) {
            return redirect()->route('account.dashboard')
                ->with('error', 'You do not have access to the admin panel.');
        }

        return $next($request);
    }
}
