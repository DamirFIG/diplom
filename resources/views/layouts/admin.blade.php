<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lobster&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poiret+One&display=swap&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <title>Админ-панель</title>
    <style>
        .notifications {
            position: fixed;
            bottom: 20px;
            left: 300px;
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

        @media (max-width: 768px) {
            .notifications {
                left: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Мобильная шапка -->
        <header class="admin-mobile-header">
            <button class="mobile-menu-btn" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <h2>Админ-панель</h2>
        </header>

        <!-- Левое меню -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>Админ-панель</h2>
                <button class="mobile-close-btn" onclick="toggleMenu()">×</button>
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('admin.index') }}" class="sidebar-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                    Главная
                </a>
                
                <a href="{{ route('admin.items') }}" class="sidebar-link {{ request()->routeIs('admin.items*') ? 'active' : '' }}">
                    Карточки
                </a>
                
                <a href="{{ route('admin.trips') }}" class="sidebar-link {{ request()->routeIs('admin.trips*') ? 'active' : '' }}">
                    Поездки
                </a>
                
                <a href="{{ route('admin.bookings') }}" class="sidebar-link {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                    Заказы
                </a>

                <a href="{{ route('admin.guides') }}" class="sidebar-link {{ request()->routeIs('admin.guides*') ? 'active' : '' }}">
                    Гиды
                </a>
                
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    Пользователи
                </a>
                
                <hr class="sidebar-divider">
                
                <a href="{{ route('home') }}" class="sidebar-link">
                    На сайт
                </a>
                
                <a href="{{ route('profile.index') }}" class="sidebar-link">
                    Профиль
                </a>
                
                <a href="{{ route('logout') }}" class="sidebar-link logout">
                    Выйти
                </a>
            </nav>
        </aside>

        <!-- Затемнение фона -->
        <div class="sidebar-overlay" id="overlay" onclick="toggleMenu()"></div>

        <!-- Основной контент -->
        <main class="admin-content">
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
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #f5f7fa;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Мобильная шапка */
        .admin-mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: #fff;
            align-items: center;
            padding: 0 15px;
            z-index: 100;
            justify-content: space-between;
        }

        .admin-mobile-header h2 {
            font-size: 18px;
            font-weight: 600;
        }

        .mobile-menu-btn {
            display: flex;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        .mobile-menu-btn span {
            width: 25px;
            height: 3px;
            background: #fff;
            border-radius: 2px;
            transition: 0.3s;
        }

        /* Сайдбар */
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 101;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-header h2 {
            font-size: 20px;
            font-weight: 600;
        }

        .mobile-close-btn {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 30px;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        .sidebar-link.active {
            background: #3498db;
            color: #fff;
        }

        .sidebar-link.logout {
            color: #e74c3c;
        }

        .sidebar-link.logout:hover {
            background: rgba(231, 76, 60, 0.1);
        }

        .sidebar-link span {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .sidebar-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 15px 0;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 13px;
        }

        .sidebar-footer p {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .sidebar-footer small {
            color: #7f8c8d;
        }

        /* Затемнение */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 100;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Контент */
        .admin-content {
            flex: 1;
            margin-left: 280px;
            padding: 40px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .admin-mobile-header {
                display: flex;
            }

            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
                padding: 80px 15px 20px;
            }

            .mobile-close-btn {
                display: block;
            }
        }

        /* Компактный и спокойный вид админ-панели */
        html {
            view-transition-name: none;
        }

        ::view-transition-old(root),
        ::view-transition-new(root) {
            animation: none !important;
        }

        .admin-layout,
        .admin-layout * {
            font-size: 14px;
        }

        .admin-content {
            padding: 28px !important;
            background: #f6f8fb;
        }

        .admin-page h1 {
            margin-bottom: 18px !important;
            color: #263445 !important;
            font-size: 24px !important;
            line-height: 1.25 !important;
            font-weight: 700 !important;
            letter-spacing: -0.02em;
        }

        .quick-actions h2,
        .admin-mobile-header h2,
        .sidebar-header h2 {
            font-size: 18px !important;
            line-height: 1.25 !important;
        }

        .page-header {
            margin-bottom: 16px !important;
            gap: 12px !important;
        }

        .sidebar-link {
            gap: 0 !important;
            padding: 14px 24px !important;
            font-size: 15px !important;
            font-weight: 500 !important;
            line-height: 1.35 !important;
            letter-spacing: 0.01em;
        }

        .sidebar-link span {
            display: none !important;
        }

        .filters-section {
            padding: 16px !important;
            margin-bottom: 16px !important;
            border: 1px solid #e6edf5 !important;
            border-radius: 14px !important;
            background: #fff !important;
            box-shadow: 0 2px 10px rgba(16, 24, 40, 0.05) !important;
        }

        .filters-section:hover {
            box-shadow: 0 2px 10px rgba(16, 24, 40, 0.05) !important;
        }

        .filters-row {
            gap: 12px !important;
            align-items: flex-end !important;
        }

        .filter-group {
            gap: 5px !important;
            min-width: 145px !important;
        }

        .filter-group label {
            font-size: 12px !important;
            letter-spacing: 0.02em !important;
            text-transform: none !important;
        }

        .filter-group input,
        .filter-group select {
            min-height: 40px !important;
            padding: 9px 12px !important;
            border: 1px solid #d9e2ec !important;
            border-radius: 9px !important;
            font-size: 13px !important;
        }

        .filter-buttons {
            flex: 0 0 auto !important;
            flex-direction: row !important;
            align-items: flex-end !important;
            justify-content: flex-start !important;
            gap: 8px !important;
            min-width: auto !important;
        }

        .btn-primary,
        .btn-secondary,
        .btn-submit,
        .btn-cancel,
        .btn-filter,
        .btn-reset {
            min-height: 40px !important;
            padding: 9px 16px !important;
            border-radius: 9px !important;
            font-size: 13px !important;
            line-height: 1.35 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            box-shadow: none !important;
            transform: none !important;
        }

        .btn-filter {
            background: #3498db !important;
            color: #fff !important;
        }

        .btn-reset {
            background: #eef3f8 !important;
            color: #536273 !important;
            border: 1px solid #d9e2ec !important;
        }

        .table-wrapper,
        .bookings-table-wrap,
        .order-card,
        .filters-section,
        .admin-form,
        .form {
            border-radius: 14px !important;
            box-shadow: 0 2px 10px rgba(16, 24, 40, 0.05) !important;
        }

        .admin-table,
        .bookings-table {
            font-size: 13px !important;
        }

        .admin-table th,
        .admin-table td,
        .bookings-table th,
        .bookings-table td {
            padding: 11px 12px !important;
        }

        .stat-card,
        .action-card {
            padding: 18px !important;
            transform: none !important;
            box-shadow: 0 2px 10px rgba(16, 24, 40, 0.05) !important;
        }

        .stat-icon {
            font-size: 13px !important;
        }

        .action-icon {
            font-size: 30px !important;
        }

        .stat-info h3 {
            font-size: 22px !important;
        }

        .stat-info p,
        .action-card span {
            font-size: 13px !important;
        }

        .admin-sidebar,
        .admin-content,
        .sidebar-link,
        .mobile-menu-btn span,
        .filters-section,
        .btn-primary,
        .btn-secondary,
        .btn-submit,
        .btn-cancel,
        .btn-filter,
        .btn-reset,
        .btn-edit,
        .btn-delete,
        .stat-card,
        .action-card,
        .filter-group input,
        .filter-group select {
            transition: none !important;
            animation: none !important;
        }

        .sidebar-link:hover,
        .stat-card:hover,
        .action-card:hover,
        .btn-filter:hover,
        .btn-reset:hover,
        .btn-primary:hover,
        .btn-secondary:hover,
        .btn-submit:hover,
        .btn-cancel:hover {
            transform: none !important;
        }


        @media (max-width: 768px) {
            .admin-content {
                padding: 76px 14px 20px !important;
            }

            .filters-row,
            .filter-buttons {
                width: 100% !important;
            }

            .filter-buttons {
                flex-direction: row !important;
            }

            .btn-filter,
            .btn-reset {
                flex: 1 !important;
            }
        }
    </style>

    <script>
        function showNotification(message, type, duration = 4000) {
            const container = document.getElementById('notifications');
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = message + '<button class="notification-close" onclick="closeNotification(this)">×</button>';

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
    </script>
    @stack('scripts')

</body>
</html>
