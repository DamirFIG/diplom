@extends('layouts.admin')

@section('content')
<div class="admin-page">
    <h1>Редактировать гида</h1>

    <form action="{{ route('admin.guides.update', $guide->id) }}" method="POST" enctype="multipart/form-data" class="form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Имя *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $guide->name) }}" required 
                   placeholder="Иван Иванов">
        </div>

        <div class="form-group">
            <label for="bio">Биография</label>
            <textarea name="bio" id="bio" rows="5" placeholder="Расскажите о гиде...">{{ old('bio', $guide->bio) }}</textarea>
        </div>

        <div class="form-group">
            <label for="photo">Фото</label>
            @if($guide->photo)
                <div class="current-photo">
                    <img loading="lazy" decoding="async" src="{{ $guide->photo_url }}" alt="Текущее фото">
                    <button type="button" class="btn-remove-photo" aria-label="Убрать фото" title="Убрать фото">×</button>
                </div>
            @endif
            <input type="file" name="photo" id="photo" accept="image/*">
            <small>Форматы: jpg, jpeg, png, webp. Макс. размер: 5MB. Оставьте пустым, чтобы не менять фото.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Сохранить изменения</button>
            <a href="{{ route('admin.guides') }}" class="btn-cancel">Отмена</a>
        </div>
    </form>
</div>

<style>
.admin-page h1 {
    margin-bottom: 30px;
    color: #2c3e50;
}

.form {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    max-width: 600px;
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

.form-group input[type="text"],
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    font-family: inherit;
}

.form-group input[type="text"]:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3498db;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #7f8c8d;
    font-size: 13px;
}

.current-photo {
    position: relative;
    display: inline-block;
    margin-bottom: 10px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #eee;
    transition: opacity 0.2s, transform 0.2s;
}

.current-photo img {
    display: block;
    width: 200px;
    max-width: 100%;
    height: 140px;
    object-fit: cover;
}

.current-photo.is-removing {
    opacity: 0;
    transform: scale(0.92);
}

.btn-remove-photo {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 50%;
    background: rgba(220, 53, 69, 0.95);
    color: #fff;
    font-size: 22px;
    line-height: 1;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    transition: background 0.2s, transform 0.2s;
}

.btn-remove-photo:hover {
    background: #c82333;
    transform: scale(1.08);
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn-submit {
    background: #3498db;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.btn-submit:hover {
    background: #2980b9;
}

.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 12px 24px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 16px;
    align-self: center;
}

.btn-cancel:hover {
    background: #5a6268;
}
</style>

<script>
document.addEventListener('click', function (event) {
    const removeButton = event.target.closest('.btn-remove-photo');

    if (!removeButton) {
        return;
    }

    const currentPhoto = removeButton.closest('.current-photo');
    const form = removeButton.closest('form');

    if (!currentPhoto || !form) {
        return;
    }

    let deleteInput = form.querySelector('input[name="delete_photo"]');

    if (!deleteInput) {
        deleteInput = document.createElement('input');
        deleteInput.type = 'hidden';
        deleteInput.name = 'delete_photo';
        form.appendChild(deleteInput);
    }

    deleteInput.value = '1';

    currentPhoto.classList.add('is-removing');
    window.setTimeout(() => currentPhoto.remove(), 200);
});
</script>

@endsection
