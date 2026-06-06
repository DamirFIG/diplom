@extends('layouts.app2')
@section('content')

<div class="auth">
    <div class="auth-left">
        <img loading="lazy" decoding="async" src="/img/register.png" alt="">
    </div>
    <div class="auth-right">
    <form action="{{ route('user.login') }}" method="post">
    @csrf
    <h2>Авторизация</h2>
    <div class="auth-form">
        <label for="login">Логин</label>
        <input type="text" name="login" id="login" value="{{ old('login') }}" class="auth-input">
    </div>
    <div class="auth-form">
        <label for="password">Пароль</label>
        <input type="password" name="password" id="password" class="auth-input">
    </div>
    <button type="submit">Войти</button>
    </form>

    <a href="{{ route('auth.forgot-password') }}">Забыли пароль?</a>
    <p class="auth-link-text">Еще нет аккаунта? <a href="{{ route('auth.register') }}">Зарегистрироваться</a></p>
    </div>

</div>

@endsection