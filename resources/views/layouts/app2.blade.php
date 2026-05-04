<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lobster&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poiret+One&display=swap&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Аренда гидроциклов в Санкт-Петербурге</title>
    <meta name="description" content="Аренда гидроциклов и водных маршрутов в Санкт-Петербурге. Активный отдых на воде.">
    <meta name="keywords" content="аренда гидроцикла СПб, водные прогулки, отдых на воде">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .notifications {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .notification {
            padding: 16px 40px 16px 24px;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            min-width: 280px;
            max-width: 400px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            position: relative;
            word-wrap: break-word;
        }

        .notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        .notification.hide {
            opacity: 0;
            transform: translateY(20px);
        }

        .notification-success {
            background: #28a745;
        }

        .notification-error {
            background: #dc3545;
        }

        .notification-close {
            position: absolute;
            top: 8px;
            right: 12px;
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .notification-close:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navigation container">
            <a href="/"><img class="logo" src="/img/logo.svg" alt="logo"></a>
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-menu">
                <li class="nav-item"><a href="/about">О нас</a></li>
                <li class="nav-item"><a href="/catalog">Каталог</a></li>
                <li class="nav-item"><a href="/re">Отзывы</a></li>
            </ul>
        </nav>
    </header>

    <div class="page-container">
    <main>
        @yield('content')
    </main>
    </div>

<!-- Уведомления -->
<div id="notifications" class="notifications"></div>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showNotification('{{ session('success') }}', 'success', 4000);
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showNotification('{{ session('error') }}', 'error', 10000);
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach($errors->all() as $error)
                showNotification('{{ $error }}', 'error', 10000);
            @endforeach
        });
    </script>
@endif

<script>
function showNotification(message, type, duration = 4000) {
    const container = document.getElementById('notifications');
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = message + '<button class="notification-close" onclick="closeNotification(this)">&times;</button>';

    container.appendChild(notification);

    // Анимация появления
    setTimeout(() => notification.classList.add('show'), 10);

    // Удаление через указанное время
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            notification.classList.add('hide');
            setTimeout(() => notification.remove(), 300);
        }
    }, duration);
}

function closeNotification(btn) {
    const notification = btn.parentElement;
    notification.classList.remove('show');
    notification.classList.add('hide');
    setTimeout(() => notification.remove(), 300);
}

window.isAuth = @json(auth()->check());

function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    const toggle = document.querySelector('.mobile-menu-toggle');
    if (navMenu && toggle) {
        navMenu.classList.toggle('active');
        toggle.classList.toggle('active');
    }
}
</script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>