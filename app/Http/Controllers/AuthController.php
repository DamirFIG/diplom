<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

        if ($user && Hash::check($credentials['password'], $user->password)) {
            if ($user->is_banned) {
                return response()->view('auth.banned', ['is_temporary' => false], 403);
            }

            $user->resetFailedLoginAttempts();

            if ($user->two_factor_enabled) {
                if (! $this->sendTwoFactorCode($user)) {
                    return back()->with('error', 'Не удалось отправить код подтверждения на email. Проверьте настройки почты и попробуйте ещё раз.');
                }

                $request->session()->put('two_factor_user_id', $user->id);

                return redirect()->route('auth.two-factor.show')
                    ->with('success', 'Код подтверждения отправлен на вашу почту');
            }

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

    public function showTwoFactor(Request $request)
    {
        if (! $request->session()->has('two_factor_user_id')) {
            return redirect()->route('auth.login');
        }

        return view('auth.two-factor');
    }

    public function verifyTwoFactor(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|digits:6',
        ]);

        $userId = $request->session()->get('two_factor_user_id');
        $user = $userId ? User::find($userId) : null;

        if (! $user) {
            $request->session()->forget('two_factor_user_id');

            return redirect()->route('auth.login')->with('error', 'Сессия подтверждения истекла. Войдите снова.');
        }

        if ($user->isLocked()) {
            $request->session()->forget('two_factor_user_id');

            return $this->responseBlocked($user);
        }

        if ($user->is_banned) {
            $request->session()->forget('two_factor_user_id');

            return response()->view('auth.banned', ['is_temporary' => false], 403);
        }

        if (! $user->two_factor_code || ! $user->two_factor_expires_at || $user->two_factor_expires_at->isPast()) {
            return back()->with('error', 'Код подтверждения истёк. Запросите новый код.');
        }

        if (! Hash::check($data['code'], $user->two_factor_code)) {
            return back()->with('error', 'Неверный код подтверждения');
        }

        $this->clearTwoFactorCode($user);
        Auth::login($user);
        $request->session()->forget('two_factor_user_id');
        $request->session()->regenerate();

        return redirect()->intended(
            $user->role === 'admin' ? route('admin.index') : '/'
        )->with('success', 'Вы успешно вошли в аккаунт');
    }

    public function resendTwoFactor(Request $request)
    {
        $userId = $request->session()->get('two_factor_user_id');
        $user = $userId ? User::find($userId) : null;

        if (! $user) {
            $request->session()->forget('two_factor_user_id');

            return redirect()->route('auth.login')->with('error', 'Сессия подтверждения истекла. Войдите снова.');
        }

        if (! $this->sendTwoFactorCode($user)) {
            return back()->with('error', 'Не удалось отправить новый код на email. Попробуйте позже.');
        }

        return back()->with('success', 'Новый код подтверждения отправлен на вашу почту');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->forget('two_factor_user_id');

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

        $user = User::where('email', $request->email)->firstOrFail();

        try {
            Mail::send('emails.reset-password', [
                'user' => $user,
                'token' => $token,
            ], function ($message) use ($user) {
                $message->to($user->email)->subject('Сброс пароля');
            });
        } catch (\Throwable $exception) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withInput()->with('error', 'Не удалось отправить письмо для сброса пароля. Проверьте настройки почты и попробуйте ещё раз.');
        }

        return back()->with('success', 'Ссылка для сброса пароля отправлена на email');
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
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

        if (! $resetToken || hash('sha256', $request->token) !== $resetToken->token) {
            return back()->with('error', 'Неверный токен сброса');
        }

        if (now()->diffInMinutes($resetToken->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->with('error', 'Срок действия токена истек');
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('auth.login')->with('success', 'Пароль успешно изменён');
    }

    private function sendTwoFactorCode(User $user): bool
    {
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'two_factor_code' => Hash::make($code),
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        try {
            Mail::send('emails.two-factor-code', [
                'user' => $user,
                'code' => $code,
            ], function ($message) use ($user) {
                $message->to($user->email)->subject('Код подтверждения входа');
            });
        } catch (\Throwable $exception) {
            $this->clearTwoFactorCode($user);

            return false;
        }

        return true;
    }

    private function clearTwoFactorCode(User $user): void
    {
        $user->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();
    }

    private function responseBlocked(User $user)
    {
        return response()->view('auth.banned', [
            'is_temporary' => true,
            'remaining_seconds' => $user->getLockRemainingSeconds(),
        ], 403);
    }
}
