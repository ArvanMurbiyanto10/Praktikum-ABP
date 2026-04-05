<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        return back()->with('error', 'Kredensial tidak valid. Coba lagi.');
    }

    public function registration()
    {
        return view('registration');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        try {
            User::create([
                'name'     => strip_tags($request->name),
                'email'    => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return redirect('/registration')->with('success', 'Registrasi berhasil! Silakan login.');
                
        } catch (\Exception $e) {
            Log::error('Insiden Registrasi: ' . $e->getMessage());
            return back()->with('error', 'Gagal mendaftar. Terjadi gangguan server.');
        }
    }

    public function home()
    {
        $user = Auth::user();
        return view('home', compact('user'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
