@extends('layouts.admin')

@section('content')
<div class="admin-page">
    <h1>Создать гида</h1>

    <form action="{{ route('admin.guides.store') }}" method="POST" enctype="multipart/form-data" class="form">
        @csrf

        <div class="form-group">
            <label for="name">Имя *</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                   placeholder="Иван Иванов">
        </div>

        <div class="form-group">
            <label for="bio">Биография</label>
            <textarea name="bio" id="bio" rows="5" placeholder="Расскажите о гиде...">{{ old('bio') }}</textarea>
        </div>

        <div class="form-group">
            <label for="photo">Фото</label>
            <input type="file" name="photo" id="photo" accept="image/*">
            <small>Форматы: jpg, jpeg, png, webp. Макс. размер: 5MB</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Создать гида</button>
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

@endsection
