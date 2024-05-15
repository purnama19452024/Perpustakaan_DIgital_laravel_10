<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$level): Response
    {
        // Jika pengguna belum terotentikasi, arahkan ke halaman login
        if (Auth::check() && in_array(Auth::user()->level, $level)) {
            return $next($request);
        }

        // return redirect()->route('error');
        // Jika pengguna memiliki peran lainnya, arahkan ke halaman 'home' dengan pesan kesalahan
        return redirect('home')->with('error', "You don't have admin access.");
    }
}
