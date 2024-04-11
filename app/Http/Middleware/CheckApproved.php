<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApproved
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->approved_at) {
            return redirect()->route('approval');
        }
        if (!auth()->user()->approved_at) {
            return redirect()->route('deny');
        }


        return $next($request);
    }
}
