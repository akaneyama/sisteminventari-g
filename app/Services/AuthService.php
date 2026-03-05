<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Logika untuk memproses login user
     */
    public function login(array $data): bool
    {
        $loginField = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Siapkan array credentials untuk Auth Laravel
        $credentials = [
            $loginField => $data['login'],
            'password'  => $data['password'],
        ];
        if (Auth::attempt($credentials)) {
            session()->regenerate();
            return true;
        }

        return false;
    }

    /**
     * Logika untuk proses logout
     */
    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}