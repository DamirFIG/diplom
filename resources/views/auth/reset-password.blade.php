@extends('layouts.app2')
@section('content')

<div class="auth">
    <div class="auth-left">
        <img loading="lazy" decoding="async" src="/img/register.png" alt="">
    </div>
    <div class="auth-right">
    <form action="{{ route('auth.reset-password.submit') }}" method="post">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <h2>Сброс пароля</h2>
    
    <div class="auth-form">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" class="auth-input" required>
    </div>
    
    <div class="auth-form">
        <label for="password">Новый пароль</label>
        <input type="password" name="password" id="password" class="auth-input" required>
    </div>
    
    <div class="auth-form">
        <label for="password_confirmation">Подтверждение пароля</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="auth-input" required>
    </div>
    
    <button type="submit">Изменить пароль</button>
    </form>

    <a href="{{ route('auth.login') }}">← Назад ко входу</a>
    </div>

</div>

@endsection
