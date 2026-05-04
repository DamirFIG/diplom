@extends('layouts.app2')
@section('content')

<div class="auth">
    <div class="auth-left">
        <img loading="lazy" decoding="async" src="/img/register.png" alt="">
    </div>
    <div class="auth-right">
    <form action="{{ route('user.register') }}" method="post">
    @csrf
    <h2>Регистрация</h2>
    <div class="auth-form">
        <label for="login">Логин</label>
        <input type="text" name="login" id="" value="{{ old('login') }}" class="auth-input">
    </div>
    <div class="auth-form">
        <label for="email">Почта</label>
        <input type="email" name="email" id="" value="{{ old('email') }}" class="auth-input">
    </div>
    <div class="auth-form">
        <label for="phone">Номер телефона</label>
        <input type="tel" name="phone" id="" value="{{ old('phone') }}" class="auth-input">
    </div>
    <div class="auth-form">
        <label for="password">Пароль</label>
        <input type="password" name="password" id="" class="auth-input">
    </div>
    <div class="auth-form">
        <label for="password_confirmation">Подтвержение пароля</label>
        <input type="password" name="password_confirmation" id="" class="auth-input">
    </div>

    <button type="submit">Зарегистрироваться</button>
    </form>

    <p class="auth-link-text">Уже есть аккаунт? <a href="{{ route('auth.login') }}">Войти</a></p>
    </div>

</div>

@endsection