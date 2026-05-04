@extends('layouts.admin')

@section('content')
<div class="admin-page">
    <div class="page-header">
        <h1>Управление пользователями</h1>
    </div>

    <!-- Фильтры и поиск -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.users') }}" class="filters-form">
            <div class="filters-row">
                <div class="filter-group">
                    <label>🔍 Поиск</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Логин, имя, email...">
                </div>
                
                <div class="filter-group">
                    <label>👥 Роль</label>
                    <select name="role">
                        <option value="">Все роли</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Пользователь</option>
                        <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>Модератор</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>📊 Сортировка</label>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>По ID</option>
                        <option value="login" {{ request('sort') == 'login' ? 'selected' : '' }}>По логину</option>
                        <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>По email</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>🔼 Порядок</label>
                    <select name="order" onchange="this.form.submit()">
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>По убыванию</option>
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>По возрастанию</option>
                    </select>
                </div>
                
                <div class="filter-group filter-buttons">
                    <button type="submit" class="btn-filter">Применить</button>
                    <a href="{{ route('admin.users') }}" class="btn-reset">Сброс</a>
                </div>
            </div>
        </form>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Логин</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Роль</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->login }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    @if($user->is_banned)
                        <span class="status-banned">Забанен</span>
                    @else
                        <span class="status-active">Активен</span>
                    @endif
                </td>
                <td>
                    @if($user->is_banned)
                        <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-unban">Разбанить</button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-ban" onclick="return confirm('Вы уверены, что хотите забанить пользователя {{ $user->login }}?')">Забанить</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
.admin-page h1 {
    margin-bottom: 30px;
    color: #2c3e50;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

/* Фильтры */
.filters-section {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

.filters-form {
    width: 100%;
}

.filters-row {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 150px;
}

.filter-group label {
    font-size: 13px;
    font-weight: 600;
    color: #555;
}

.filter-group input,
.filter-group select {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    background: #fff;
    transition: border-color 0.3s;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: #3498db;
}

.filter-buttons {
    display: flex;
    gap: 10px;
}

.btn-filter,
.btn-reset {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    white-space: nowrap;
}

.btn-filter {
    background: #3498db;
    color: white;
}

.btn-filter:hover {
    background: #2980b9;
}

.btn-reset {
    background: #95a5a6;
    color: white;
}

.btn-reset:hover {
    background: #7f8c8d;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.admin-table th,
.admin-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.admin-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
}

.admin-table tr:hover {
    background: #f8f9fa;
}

.status-active {
    color: #28a745;
    font-weight: 600;
}

.status-banned {
    color: #dc3545;
    font-weight: 600;
}

.btn-ban,
.btn-unban {
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    color: white;
}

.btn-ban {
    background: #dc3545;
}

.btn-ban:hover {
    background: #c82333;
}

.btn-unban {
    background: #28a745;
}

.btn-unban:hover {
    background: #218838;
}
</style>

@endsection
