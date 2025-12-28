<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function showLoginForm()
    {
        return view('auth.login');
    }

  
    public function login(Request $request)
    {
        
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'moderator') {
                return redirect()->route('moderator.dashboard');
            } else {
                return redirect()->route('client.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Nieprawidłowy e-mail lub hasło.',
        ])->onlyInput('email');
    }

    
    public function showRegisterForm()
    {
        return view('auth.register');
    }

   
    public function register(Request $request)
    {
       
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6|confirmed',
           
        ]);

        
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'client',
        ]);

        
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('client.dashboard');
    }

    
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
