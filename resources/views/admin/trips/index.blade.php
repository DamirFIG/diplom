@extends('layouts.admin')

@section('content')
<div class="admin-page">
    <div class="page-header">
        <h1>Поездки</h1>
        <a href="{{ route('admin.trips.create') }}" class="btn-primary">➕ Создать поездку</a>
    </div>

    <!-- Фильтры и поиск -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.trips') }}" class="filters-form">
            <div class="filters-row">
                <div class="filter-group">
                    <label>🔍 Поиск</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Название...">
                </div>
                
                <div class="filter-group">
                    <label>🏷️ Тип активности</label>
                    <select name="activity_type">
                        <option value="">Все типы</option>
                        @foreach($activityTypes as $type)
                            <option value="{{ $type }}" {{ request('activity_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>📊 Сортировка</label>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>По ID</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>По названию</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>По цене</option>
                        <option value="event_date" {{ request('sort') == 'event_date' ? 'selected' : '' }}>По дате</option>
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
                    <a href="{{ route('admin.trips') }}" class="btn-reset">Сброс</a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Фото</th>
                    <th>Название</th>
                    <th>Тип</th>
                    <th>Дата</th>
                    <th>Цена</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trips as $trip)
                <tr>
                    <td>{{ $trip->id }}</td>
                    <td>
                        @if($trip->main_image && $trip->main_image !== 'img/empty.png')
                            <img loading="lazy" decoding="async" src="{{ asset('storage/' . $trip->main_image) }}" width="80" style="object-fit: cover; border-radius: 5px;">
                        @else
                            <span style="color: #999;">Нет фото</span>
                        @endif
                    </td>
                    <td>{{ $trip->title }}</td>
                    <td>
                        <span class="badge badge-{{ $trip->activity_type }}">
                            {{ $trip->activity_type }}
                        </span>
                    </td>
                    <td>{{ $trip->event_date?->format('d.m.Y') ?? '—' }}</td>
                    <td>{{ $trip->price }} ₽</td>
                    <td>
                        <a href="{{ route('admin.trips.edit', $trip->id) }}" class="btn-edit">✏️</a>
                        <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Удалить?')" class="btn-delete">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">Нет поездок</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
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

.btn-primary {
    background: #3498db;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-primary:hover {
    background: #2980b9;
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

.table-wrapper {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
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

.admin-table tr:last-child td {
    border-bottom: none;
}

.btn-edit,
.btn-delete {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
    padding: 8px 12px;
    border-radius: 5px;
    transition: background 0.3s;
}

.btn-edit:hover {
    background: #e3f2fd;
}

.btn-delete:hover {
    background: #ffebee;
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.badge-гидроцикл { background: #e3f2fd; color: #1976d2; }
.badge-банан { background: #fff3e0; color: #f57c00; }
.badge-флайборд { background: #fce4ec; color: #c2185b; }
.badge-сапборд { background: #e8f5e9; color: #388e3c; }
.badge-катамаран { background: #e0f7fa; color: #00838f; }

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .admin-table {
        font-size: 14px;
    }
    
    .admin-table th,
    .admin-table td {
        padding: 10px 8px;
    }
}
</style>

@endsection
