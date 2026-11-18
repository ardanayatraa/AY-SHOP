<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSeller
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu untuk mengakses Menu Seller.');
        }

        if (! $user->seller) {
            return redirect()->route('seller.register')->with('info', 'Anda belum memiliki toko. Silakan buka toko Anda terlebih dahulu!');
        }

        return $next($request);
    }
}

