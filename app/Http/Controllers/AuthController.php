<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    // Inject AuthService melalui constructor
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Menampilkan halaman form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Menerima submit form login
    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Lempar logika ke AuthService
        if ($this->authService->login($data)) {
            // Jika sukses, arahkan ke dashboard masing-masing role
            if (auth()->user()->role === 'Admin') {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/kepsek/dashboard');
            }
        }

        return back()->withErrors([
            'login' => 'Username atau password salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        $this->authService->logout();
        return redirect('/login');
    }
}
