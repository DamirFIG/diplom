@extends('layouts.admin')

@section('content')
<div class="admin-page">
    <div class="page-header">
        <h1>Редактировать карточку</h1>
        <a href="{{ route('admin.items') }}" class="btn-secondary">← Назад</a>
    </div>

    <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="admin-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="transport">

        <div class="form-group">
            <label for="title">Название *</label>
            <input type="text" name="title" id="title" value="{{ old('title', $item->title) }}" required placeholder="Например: Гидроцикл Yamaha VX">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="activity_type">Тип активности *</label>
                <select name="activity_type" id="activity_type" required>
                    <option value="">Выберите тип</option>
                    <option value="гидроцикл" {{ old('activity_type', $item->activity_type) === 'гидроцикл' ? 'selected' : '' }}>Гидроцикл</option>
                    <option value="банан" {{ old('activity_type', $item->activity_type) === 'банан' ? 'selected' : '' }}>Банан</option>
                    <option value="флайборд" {{ old('activity_type', $item->activity_type) === 'флайборд' ? 'selected' : '' }}>Флайборд</option>
                    <option value="сапборд" {{ old('activity_type', $item->activity_type) === 'сапборд' ? 'selected' : '' }}>Сапборд</option>
                    <option value="катамаран" {{ old('activity_type', $item->activity_type) === 'катамаран' ? 'selected' : '' }}>Катамаран</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Цена (₽) *</label>
                <input type="number" name="price" id="price" value="{{ old('price', $item->price) }}" required min="0" placeholder="5000">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="max_people">Макс. человек</label>
                <input type="number" name="max_people" id="max_people" value="{{ old('max_people', $item->max_people) }}" min="1" placeholder="2">
            </div>

            <div class="form-group">
                <label for="duration_minutes">Длительность (мин)</label>
                <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', $item->duration_minutes) }}" min="1" placeholder="60">
            </div>

            <div class="form-group">
                <label for="min_age">Мин. возраст</label>
                <input type="number" name="min_age" id="min_age" value="{{ old('min_age', $item->min_age) }}" min="1" placeholder="16">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea name="description" id="description" rows="4" placeholder="Описание карточки...">{{ old('description', $item->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Текущие фотографии</label>
            <div class="current-images">
                @if($item->gallery && count($item->gallery) > 0)
                    @foreach($item->gallery as $image)
                        <div class="image-item">
                            <img loading="lazy" decoding="async" src="{{ asset('storage/' . $image) }}" alt="Фото">
                            <label class="delete-checkbox">
                                <input type="checkbox" name="delete_images[]" value="{{ $image }}">
                                <span>Удалить</span>
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="gallery">Добавить фотографии</label>
            <input type="file" name="gallery[]" id="gallery" multiple accept="image/*">
            <div class="form-hint">Можно выбрать несколько файлов. Форматы: jpg, jpeg, png, webp. Макс. размер: 5MB</div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Сохранить изменения</button>
            <a href="{{ route('admin.items') }}" class="btn-cancel">Отмена</a>
        </div>
    </form>
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

.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-secondary:hover {
    background: #5a6268;
}

.admin-form {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    max-width: 800px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3498db;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.form-hint {
    font-size: 13px;
    color: #7f8c8d;
    margin-top: 8px;
}

.current-images {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.image-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #eee;
}

.image-item img {
    width: 100%;
    height: 100px;
    object-fit: cover;
}

.delete-checkbox {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 8px;
    background: #fff;
    font-size: 13px;
    cursor: pointer;
}

.delete-checkbox input {
    width: auto;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn-submit {
    background: #3498db;
    color: white;
    padding: 14px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-submit:hover {
    background: #2980b9;
}

.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 14px 30px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    align-self: center;
    transition: background 0.3s;
}

.btn-cancel:hover {
    background: #5a6268;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .admin-form {
        padding: 20px;
    }
}
</style>

@endsection
