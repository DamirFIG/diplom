@extends('layouts.app2')
@section('content')

<div class="auth">
    <div class="auth-left">
        <img loading="lazy" decoding="async" src="/img/register.png" alt="">
    </div>
    <div class="auth-right">
        <form action="{{ route('auth.two-factor.verify') }}" method="post">
            @csrf
            <h2>Подтверждение входа</h2>
            <p style="font-size: 16px; color: #666; margin-bottom: 20px;">
                Мы отправили 6-значный код на вашу почту. Введите его ниже, чтобы завершить вход.
            </p>

            <div class="auth-form">
                <label for="code">Код из письма</label>
                <input
                    type="text"
                    name="code"
                    id="code"
                    value="{{ old('code') }}"
                    class="auth-input"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    autocomplete="one-time-code"
                    required
                >
            </div>

            <button type="submit">Подтвердить</button>
        </form>

        <form action="{{ route('auth.two-factor.resend') }}" method="post" style="margin-top: 12px;">
            @csrf
            <button type="submit">Отправить код ещё раз</button>
        </form>

        <a href="{{ route('auth.login') }}">← Вернуться ко входу</a>
    </div>
</div>

@endsection
