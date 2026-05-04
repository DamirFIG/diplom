@extends('layouts.app2')
@section('content')

<div class="banned-page">
    <div class="banned-message">
        @if(isset($is_temporary) && $is_temporary)
            <h1>СЛИШКОМ МНОГО ПОПЫТОК</h1>
            <p>Ваш аккаунт был временно заблокирован из-за слишком большого количества неудачных попыток входа</p>
            <p class="timer-text">Подождите <span id="countdown">{{ $remaining_seconds ?? 30 }}</span> сек.</p>
            <a href="#" class="back-login disabled">Вернуться на страницу входа</a>
        @else
            <h1>ВЫ ЗАБАНЕНЫ</h1>
            <p>Ваш аккаунт был заблокирован администрацией</p>
            <a href="{{ route('auth.login') }}" class="back-login">Вернуться на страницу входа</a>
        @endif
    </div>
</div>

<style>
.banned-page {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #f8f9fa;
}

.banned-message {
    text-align: center;
    padding: 60px 40px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    max-width: 500px;
}

.banned-message h1 {
    color: #dc3545;
    font-size: 48px;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.banned-message p {
    color: #666;
    font-size: 20px;
    margin-bottom: 30px;
}

.timer-text {
    font-size: 24px;
    font-weight: bold;
    color: #dc3545;
}

#countdown {
    font-size: 32px;
    color: #dc3545;
}

.back-login {
    display: inline-block;
    color: #4A90D9;
    text-decoration: none;
    font-size: 18px;
    padding: 12px 24px;
    border: 2px solid #4A90D9;
    border-radius: 5px;
    transition: all 0.3s;
}

.back-login:hover {
    background: #4A90D9;
    color: #fff;
}

.back-login.disabled {
    pointer-events: none;
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

@if(isset($is_temporary) && $is_temporary)
<script>
document.addEventListener('DOMContentLoaded', function() {
    let seconds = {{ $remaining_seconds ?? 30 }};
    const countdownElement = document.getElementById('countdown');
    const backLink = document.querySelector('.back-login');
    
    const interval = setInterval(function() {
        seconds--;
        if (countdownElement) {
            countdownElement.textContent = seconds;
        }
        
        if (seconds <= 0) {
            clearInterval(interval);
            if (backLink) {
                backLink.classList.remove('disabled');
                backLink.href = "{{ route('auth.login') }}";
                backLink.textContent = 'Вернуться на страницу входа';
            }
        }
    }, 1000);
});
</script>
@endif

@endsection
