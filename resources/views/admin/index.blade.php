@extends('layouts.admin')

@section('content')
<div class="admin-page">
    <h1>Панель управления</h1>

    <div class="stats-grid">
        <a href="{{ route('admin.items') }}" class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-info">
                <h3>{{ $items->count() }}</h3>
                <p>Карточек аренды</p>
            </div>
        </a>

        <a href="{{ route('admin.trips') }}" class="stat-card">
            <div class="stat-icon">🚗</div>
            <div class="stat-info">
                <h3>{{ $trips->count() }}</h3>
                <p>Поездок</p>
            </div>
        </a>

        <a href="{{ route('admin.guides') }}" class="stat-card">
            <div class="stat-icon">👤</div>
            <div class="stat-info">
                <h3>{{ $guides->count() }}</h3>
                <p>Гидов</p>
            </div>
        </a>

        <a href="{{ route('admin.users') }}" class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3>{{ $users->count() }}</h3>
                <p>Пользователей</p>
            </div>
        </a>
    </div>

    <div class="quick-actions">
        <h2>Быстрые действия</h2>
        <div class="actions-grid">
            <a href="{{ route('admin.items.create') }}" class="action-card">
                <span class="action-icon">➕</span>
                <span>Создать карточку</span>
            </a>
            <a href="{{ route('admin.trips.create') }}" class="action-card">
                <span class="action-icon">🚗</span>
                <span>Создать поездку</span>
            </a>
            <a href="{{ route('admin.guides.create') }}" class="action-card">
                <span class="action-icon">👤</span>
                <span>Добавить гида</span>
            </a>
        </div>
    </div>
</div>

<style>
.admin-page h1 {
    margin-bottom: 30px;
    color: #2c3e50;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 15px;
    text-decoration: none;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.stat-icon {
    font-size: 40px;
}

.stat-info h3 {
    font-size: 28px;
    color: #2c3e50;
    margin-bottom: 4px;
}

.stat-info p {
    color: #7f8c8d;
    font-size: 14px;
}

.quick-actions h2 {
    margin: 30px 0 20px;
    color: #2c3e50;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.action-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 15px;
    text-decoration: none;
    color: #2c3e50;
    font-weight: 600;
    transition: transform 0.3s, box-shadow 0.3s;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.action-icon {
    font-size: 32px;
}

@media (max-width: 768px) {
    .stats-grid,
    .actions-grid {
        grid-template-columns: 1fr;
    }
}
</style>

@endsection