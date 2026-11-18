<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isSeller()) {
                return redirect()->intended(route('seller.dashboard'))
                    ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            }

            return redirect()->intended(route('home'))
                ->with('success', 'Berhasil masuk ke AY SHOP!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:30'],
            'account_type' => ['required', 'in:customer,seller'],
            'store_name' => ['required_if:account_type,seller', 'nullable', 'string', 'max:120'],
            'city' => ['required_if:account_type,seller', 'nullable', 'string', 'max:100'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['account_type'],
                'phone' => $validated['phone'] ?? null,
            ]);

            if ($validated['account_type'] === 'seller') {
                $baseSlug = Str::slug($validated['store_name']);
                $slug = $baseSlug;
                $counter = 1;
                while (Seller::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }

                Seller::create([
                    'user_id' => $user->id,
                    'store_name' => $validated['store_name'],
                    'slug' => $slug,
                    'city' => $validated['city'] ?? 'Jakarta',
                    'phone' => $validated['phone'] ?? null,
                    'description' => 'Toko pakaian resmi di AY SHOP.',
                    'is_verified' => false,
                    'is_active' => true,
                ]);
            }

            return $user;
        });

        Auth::login($user);

        if ($user->isSeller()) {
            return redirect()->route('seller.dashboard')->with('success', 'Akun & Toko Anda berhasil dibuat!');
        }

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil! Selamat berbelanja di AY SHOP.');
    }

    public function logout(Request $request)
    {
        // The embedded chat's token ends with the session.
        $request->user()?->tokens()->where('name', 'mcp-hub-chat')->delete();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah keluar dari akun.');
    }
}

