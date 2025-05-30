<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyElectionStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_if(Auth::guest() && !$request->election->is_open , 403, 'وارد شوید');
        abort_unless($request->election->is_public, 403, 'نظرسنجی عمومی نیست');
        abort_unless($request->election->is_open || $request->election->user_id == $request->user()->id, 403, 'نظرسنجی بسته شده است');

        return $next($request);
    }
}
