<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|alpha_num|unique:users|min:6',
            'phone' => 'required|regex:/^\+7\d{10}$/',
            'password' => 'required|confirmed|min:8',
            'email' => 'required|email|unique:users',
        ]);

        User::create($validated);

        return redirect()->route('auth.login')->with('success', 'Вы успешно создали аккаунт');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('login', $credentials['login'])->first();

        if ($user && $user->isLocked()) {
            return $this->responseBlocked($user);
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {
            if ($user->is_banned) {
                return response()->view('auth.banned', ['is_temporary' => false], 403);
            }

            $user->resetFailedLoginAttempts();

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended(
                $user->role === 'admin' ? route('admin.index') : '/'
            )->with('success', 'Вы успешно вошли в аккаунт');
        }

        if ($user) {
            $user->incrementFailedLoginAttempts(30, 3);

            if ($user->isLocked()) {
                return $this->responseBlocked($user);
            }
        }

        return back()->with('error', 'Неверный логин или пароль');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('auth.login')->with('success', 'Вы успешно вышли из аккаунта');
    }

    private function responseBlocked(User $user)
    {
        return response()->view('auth.banned', [
            'is_temporary' => true,
            'remaining_seconds' => $user->getLockRemainingSeconds(),
        ], 403);
    }
}
