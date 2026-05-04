@extends('layouts.admin')

@section('content')
<div class="admin-page">
    <div class="page-header">
        <h1>Управление гидами</h1>
        <a href="{{ route('admin.guides.create') }}" class="btn-primary">➕ Добавить гида</a>
    </div>

    <!-- Фильтры и поиск -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.guides') }}" class="filters-form">
            <div class="filters-row">
                <div class="filter-group">
                    <label>🔍 Поиск</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Имя гида...">
                </div>
                
                <div class="filter-group">
                    <label>📊 Сортировка</label>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>По ID</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>По имени</option>
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
                    <a href="{{ route('admin.guides') }}" class="btn-reset">Сброс</a>
                </div>
            </div>
        </form>
    </div>

    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Фото</th>
            <th>Имя</th>
            <th>Биография</th>
            <th>Поездок</th>
            <th>Действия</th>
        </tr>

        @forelse($guides as $guide)
        <tr>
            <td>{{ $guide->id }}</td>
            <td>
                @if($guide->photo)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $guide->photo) }}" width="80" style="object-fit: cover; border-radius: 5px;">
                @else
                    <span style="color: #999;">Нет фото</span>
                @endif
            </td>
            <td>{{ $guide->name }}</td>
            <td style="max-width: 300px;">
                {{ Str::limit($guide->bio, 100) ?? '—' }}
            </td>
            <td>{{ $guide->trips->count() }}</td>
            <td>
                <a href="{{ route('admin.guides.edit', $guide->id) }}" class="btn-edit">✏️ Редактировать</a>

                <form action="{{ route('admin.guides.destroy', $guide->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Удалить гида?')" class="btn-delete">🗑 Удалить</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">Нет гидов</td>
        </tr>
        @endforelse
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

.btn-edit,
.btn-delete {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin-right: 5px;
    color: white;
}

.btn-edit {
    background: #3498db;
}

.btn-edit:hover {
    background: #2980b9;
}

.btn-delete {
    background: #e74c3c;
}

.btn-delete:hover {
    background: #c0392b;
}
</style>

@endsection
