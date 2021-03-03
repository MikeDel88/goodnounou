<?php

namespace App\Http\Middleware;

use Closure;

class Parents
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if ($user && get_class($user->categorie) === 'App\Models\Parents') {
            return $next($request);
        }
        return redirect()->route('home')->with('error403', 'Désolé cette page est interdite');
    }
}
