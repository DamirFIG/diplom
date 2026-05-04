<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if ($user->is_banned) {
                Auth::logout();
                return response()->view('auth.banned', ['is_temporary' => false], 403);
            }

            $user->resetFailedLoginAttempts();

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

    public function logout()
    {
        auth()->logout();
        return redirect()->route('auth.login')->with('success', 'Вы успешно вышли из аккаунта');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => hash('sha256', $token),
                'created_at' => now(),
            ]
        );

        $user = User::where('email', $request->email)->first();

        \Illuminate\Support\Facades\Mail::send('emails.reset-password', [
            'user' => $user,
            'token' => $token,
        ], function ($message) use ($user) {
            $message->to($user->email)->subject('Сброс пароля');
        });

        return back()->with('success', 'Ссылка для сброса пароля отправлена на email');
    }

    public function showResetPassword($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetToken || hash('sha256', $request->token) !== $resetToken->token) {
            return back()->with('error', 'Неверный токен сброса');
        }

        if (now()->diffInMinutes($resetToken->created_at) > 60) {
            return back()->with('error', 'Срок действия токена истек');
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('auth.login')->with('success', 'Пароль успешно изменён');
    }

    private function responseBlocked(User $user)
    {
        return response()->view('auth.banned', [
            'is_temporary' => true,
            'remaining_seconds' => $user->getLockRemainingSeconds(),
        ], 403);
    }
}
