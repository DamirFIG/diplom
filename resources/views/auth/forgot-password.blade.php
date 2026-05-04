@extends('layouts.app2')
@section('content')

<div class="auth">
    <div class="auth-left">
        <img loading="lazy" decoding="async" src="/img/register.png" alt="">
    </div>
    <div class="auth-right">
    <form action="{{ route('auth.forgot-password.send') }}" method="post">
    @csrf
    <h2>Восстановление пароля</h2>
    <p style="font-size: 16px; color: #666; margin-bottom: 20px;">
        Введите ваш email, и мы отправим ссылку для сброса пароля
    </p>
    <div class="auth-form">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" class="auth-input" required>
    </div>
    <button type="submit">Отправить ссылку</button>
    </form>

    <a href="{{ route('auth.login') }}">← Назад ко входу</a>
    </div>

</div>

@endsection
