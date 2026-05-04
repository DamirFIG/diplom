@extends('layouts.app')

@section('content')

<style>
.trip-page {
    display: flex;
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

.trip-left {
    flex: 1.4;
    max-width: 650px;
}

.main-image {
    margin-bottom: 20px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.main-image img {
    width: 100%;
    height: 550px;
    object-fit: cover;
    display: block;
}

.thumbs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.thumb:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Адаптив для главного фото */
@media (max-width: 768px) {
    .main-image img {
        height: 300px;
    }
}

.trip-right {
    flex: 1;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    height: fit-content;
}

.trip-right h4 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #333;
    font-family: 'Montserrat', sans-serif;
}

.trip-right p {
    font-size: 16px;
    color: #555;
    margin-bottom: 15px;
    line-height: 1.6;
}

.trip-right .price {
    font-size: 24px;
    font-weight: 700;
    color: #4A90D9;
    margin: 20px 0;
}

.btn-book {
    background: #4A90D9;
    color: white;
    padding: 15px 40px;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
    width: 100%;
    margin-top: 20px;
}

.btn-book:hover {
    background: #357ABD;
}

/* Стили для блока с маршрутом */
.route-section {
    max-width: 1200px;
    margin: 40px auto;
    padding: 30px 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.route-section h3 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
    font-family: 'Montserrat', sans-serif;
}

#map {
    height: 500px;
    width: 100%;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Кастомные стили для поп-апов Leaflet */
.leaflet-popup-content-wrapper {
    border-radius: 10px;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 3px 14px rgba(0,0,0,0.2);
}

.leaflet-popup-content {
    margin: 0;
    width: 300px !important;
    max-width: 300px;
}

.leaflet-popup-tip {
    box-shadow: 0 3px 14px rgba(0,0,0,0.2);
}

.popup-content {
    display: flex;
    flex-direction: column;
}

.popup-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    display: block;
}

.popup-info {
    padding: 15px;
    background: #fff;
}

.popup-info h4 {
    margin: 0 0 8px 0;
    font-size: 17px;
    color: #333;
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
}

.popup-info p {
    margin: 0;
    font-size: 14px;
    color: #666;
    line-height: 1.5;
}

.popup-type-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 10px;
    letter-spacing: 0.5px;
}

.badge-start {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
}

.badge-end {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.badge-stop {
    background: linear-gradient(135deg, #28a745, #218838);
    color: white;
}

/* Кастомные маркеры */
.custom-marker {
    transition: transform 0.2s;
}

.custom-marker:hover {
    transform: scale(1.2);
}

.route-name{
    text-align: center;
}

/* Адаптив */
@media (max-width: 768px) {
    .trip-page {
        flex-direction: column;
    }

    .trip-left {
        max-width: 100%;
    }
}

/* Модальное окно бронирования */
.booking-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease;
}

.booking-modal-content {
    background: #fff;
    margin: 5% auto;
    border-radius: 16px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.booking-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #e0e0e0;
}

.booking-modal-header h3 {
    margin: 0;
    font-size: 22px;
    color: #2c3e50;
    font-weight: 600;
}

.booking-modal-close {
    font-size: 32px;
    color: #999;
    cursor: pointer;
    transition: color 0.2s;
    line-height: 1;
}

.booking-modal-close:hover {
    color: #333;
}

.booking-modal-body {
    padding: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: 500;
    font-size: 14px;
}

.people-counter {
    display: flex;
    align-items: center;
    gap: 15px;
}

.counter-btn {
    width: 40px;
    height: 40px;
    border: 2px solid #e0e0e0;
    background: #f8f9fa;
    border-radius: 8px;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.counter-btn:hover {
    background: #4A90D9;
    border-color: #4A90D9;
    color: white;
}

.counter-btn:active {
    transform: scale(0.95);
}

#booking-people {
    width: 60px;
    text-align: center;
    font-size: 18px;
    font-weight: 600;
    border: none;
    background: transparent;
}

.form-hint {
    display: block;
    margin-top: 5px;
    color: #999;
    font-size: 12px;
}

.static-value {
    font-size: 16px;
    color: #333;
    font-weight: 500;
    margin: 0;
}

#total-price {
    font-size: 24px;
    font-weight: 700;
    color: #4A90D9;
}

#booking-comment {
    width: 100%;
    min-height: 80px;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    resize: vertical;
    font-family: inherit;
    font-size: 14px;
    transition: border-color 0.2s;
}

#booking-comment:focus {
    outline: none;
    border-color: #4A90D9;
}

.btn-submit-booking {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #4A90D9 0%, #357ABD 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(74, 144, 217, 0.3);
}

.btn-submit-booking:hover {
    background: linear-gradient(135deg, #357ABD 0%, #2c6aa3 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(74, 144, 217, 0.4);
}

.btn-submit-booking:active {
    transform: translateY(0);
}
</style>

<div class="trip-page">
    <div class="trip-left">
        {{-- Большое фото --}}
        <div class="main-image">
            <img loading="lazy" decoding="async" id="activeImage" src="{{ asset('storage/' . $trip->main_image) }}" alt="{{ $trip->title }}">
        </div>

        {{-- Мини-фото --}}
        <div class="thumbs">
            {{-- Главное фото как первая миниатюра --}}
            <img loading="lazy" decoding="async" src="{{ asset('storage/' . $trip->main_image) }}"
                 class="thumb"
                 alt="Main"
                 onclick="changeImage(this.src)">

            {{-- Галерея --}}
            @php
                $gallery = is_array($trip->gallery) ? $trip->gallery : [];
            @endphp

            @if(count($gallery) > 1)
                @foreach(array_slice($gallery, 1) as $img)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/'.$img) }}" class="thumb" alt="Gallery" onclick="changeImage(this.src)">
                @endforeach
            @endif
        </div>
    </div>

    <div class="trip-right">
        <h4>{{ $trip->title }}</h4>
        <p><strong>Тип активности:</strong> {{ $trip->activity_type }}</p>
        <p style="word-wrap: break-word; overflow-wrap: break-word;">{{ $trip->description ?? '' }}</p>
        <p><strong>Макс. количество людей:</strong> {{ $trip->max_people ?? 'не указано' }}</p>
        <p><strong>Длительность:</strong> {{ $trip->duration_minutes ?? 'не указано' }} минут</p>
        <p><strong>Минимальный возраст:</strong> {{ $trip->min_age ?? '0' }}</p>
        @if($trip->event_date)
            <p><strong>Дата проведения:</strong> {{ \Carbon\Carbon::parse($trip->event_date)->format('d.m.Y') }}</p>
        @endif
        @if($trip->guide)
            <p><strong>Гид:</strong> {{ $trip->guide->name }}</p>
        @endif

        <p class="price">{{ $trip->price }} ₽</p>

        <button class="btn-book" onclick="openBookingModal()">Забронировать</button>
    </div>
</div>

<!-- Модальное окно бронирования -->
<div id="bookingModal" class="booking-modal">
    <div class="booking-modal-content">
        <div class="booking-modal-header">
            <h3>Бронирование поездки</h3>
            <span class="booking-modal-close" onclick="closeBookingModal()">&times;</span>
        </div>
        <div class="booking-modal-body">
            <form action="{{ route('trips.book') }}" method="POST">
                @csrf
                <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                
                <div class="form-group">
                    <label for="booking-people">Количество участников:</label>
                    <div class="people-counter">
                        <button type="button" class="counter-btn" onclick="decrementPeople()">−</button>
                        <input type="number" id="booking-people" name="people" value="1" min="1" max="{{ $trip->max_people ?? 10 }}" readonly>
                        <button type="button" class="counter-btn" onclick="incrementPeople()">+</button>
                    </div>
                    <small class="form-hint">Максимум: {{ $trip->max_people ?? 10 }} чел.</small>
                </div>

                <div class="form-group">
                    <label for="booking-date">Дата проведения:</label>
                    <p class="static-value">{{ $trip->event_date ? \Carbon\Carbon::parse($trip->event_date)->format('d.m.Y') : 'Уточняется' }}</p>
                </div>

                <div class="form-group">
                    <label>Стоимость:</label>
                    <p class="static-value" id="total-price">{{ $trip->price }} ₽</p>
                    <small class="form-hint">Цена за 1 человека</small>
                </div>

                <div class="form-group">
                    <label for="booking-comment">Комментарий (необязательно):</label>
                    <textarea id="booking-comment" name="comment" placeholder="Ваши пожелания..."></textarea>
                </div>

                <button type="submit" class="btn-submit-booking">Подтвердить бронирование</button>
            </form>
        </div>
    </div>
</div>

{{-- Блок с маршрутом на карте --}}
<h2 class="route-name">Маршрут</h2>
@if($trip->route && $trip->route->points->count())
    <div class="route-section">
        <div id="map"
             data-visible-points='@json($trip->route->allPoints)'
             data-line-points='@json($trip->route->allLinePoints)'
             data-center='@json(["lat" => $trip->route->start_lat ?? 59.9343, "lng" => $trip->route->start_lng ?? 30.3351])'>
        </div>
    </div>
@endif

<!-- Отзывы -->
<section class="reviews-section container">
    <h3 class="reviews-title">Отзывы <span class="reviews-count">({{ $reviews->total() }})</span></h3>

    <!-- Форма добавления отзыва -->
    @auth
        <div class="add-review-form">
            <h4>Оставить отзыв</h4>
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                <input type="hidden" name="rating" id="trip-rating-input" value="0">

                <div class="rating-stars" id="trip-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star" data-value="{{ $i }}">★</span>
                    @endfor
                </div>

                <textarea name="text" maxlength="256" placeholder="Напишите ваш отзыв..." required></textarea>
                <button type="submit">Отправить отзыв</button>
            </form>
        </div>
    @else
        <p class="login-message"><a href="{{ route('auth.login') }}">Войдите</a>, чтобы оставить отзыв</p>
    @endauth

    @if($reviews->count() > 0)
        <div class="reviews-list">
            @foreach($reviews as $review)
                <div class="review-card" data-review-id="{{ $review->id }}">
                    <div class="review-card-header">
                        <div class="review-author-wrapper">
                            <img loading="lazy" decoding="async" src="{{ $review->user->avatar ? asset('storage/' . $review->user->avatar) : asset('img/empty.png') }}" alt="avatar" class="review-avatar">
                            <div class="review-author-info">
                                <span class="author-name">{{ $review->user->login ?? 'Гость' }}</span>
                                <span class="review-date">{{ $review->created_at->format('d.m.Y') }}</span>
                            </div>
                        </div>
                        <div class="review-rating-badge">
                            @for($i = 0; $i < 5; $i++)
                                <span class="star {{ $i < $review->rating ? 'active' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <p class="review-text">{{ $review->text }}</p>
                    <div class="review-reactions">
                        <span class="reaction-item likes">
                            <span class="reaction-icon">👍</span>
                            <span class="reaction-count">{{ $review->likes }}</span>
                        </span>
                        <span class="reaction-item dislikes">
                            <span class="reaction-icon">👎</span>
                            <span class="reaction-count">{{ $review->dislikes }}</span>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Пагинация для отзывов -->
        <div class="pagination-container">
            {{ $reviews->links('vendor.pagination.bootstrap-5') }}
        </div>
    @else
        <div class="no-reviews">
            <div class="no-reviews-icon">💬</div>
            <p>Отзывов пока нет</p>
            <span>Будьте первым, кто оставит отзыв!</span>
        </div>
    @endif
</section>

<style>
.reviews-section {
    max-width: 1200px;
    margin: 40px auto;
    padding: 30px 20px;
}

.reviews-title {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.reviews-count {
    color: #4A90D9;
    font-weight: 600;
}

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

.review-card {
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #f0f0f0;
}

.review-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.review-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 18px;
}

.review-author-wrapper {
    display: flex;
    align-items: center;
    gap: 14px;
}

.review-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e8f0fe;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.review-author-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.author-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 16px;
}

.review-date {
    color: #95a5a6;
    font-size: 13px;
}

.review-rating-badge {
    background: linear-gradient(135deg, #fff9e6 0%, #fff3cc 100%);
    padding: 8px 14px;
    border-radius: 20px;
    display: flex;
    gap: 3px;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.2);
}

.review-rating-badge .star {
    color: #ffc107;
    font-size: 16px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.review-rating-badge .star:not(.active) {
    color: #d0d0d0;
    text-shadow: none;
}

.review-text {
    margin-left: 0;
    color: #555;
    line-height: 1.7;
    margin-bottom: 20px;
    font-size: 15px;
    padding-left: 15px;
    border-left: 3px solid #4A90D9;
}

.review-reactions {
    display: flex;
    gap: 12px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.reaction-item {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    background: #f8f9fa;
    transition: background 0.2s ease;
    cursor: pointer;
}

.reaction-item:hover {
    background: #e8f0fe;
}

.reaction-item.likes:hover {
    background: #d4edda;
}

.reaction-item.dislikes:hover {
    background: #f8d7da;
}

.reaction-icon {
    font-size: 16px;
}

.reaction-count {
    font-size: 14px;
    font-weight: 600;
    color: #555;
}

.no-reviews {
    text-align: center;
    padding: 60px 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e8f0fe 100%);
    border-radius: 16px;
    border: 2px dashed #4A90D9;
}

.no-reviews-icon {
    font-size: 64px;
    margin-bottom: 15px;
}

.no-reviews p {
    font-size: 18px;
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 8px;
}

.no-reviews span {
    color: #7f8c8d;
    font-size: 14px;
}

.add-review-form {
    background: linear-gradient(135deg, #f8f9fa 0%, #e8f0fe 100%);
    padding: 30px;
    border-radius: 16px;
    border: 1px solid #e0e0e0;
}

.add-review-form h4 {
    margin-bottom: 15px;
    color: #2c3e50;
    font-size: 18px;
    font-weight: 600;
}

.add-review-form .rating-stars {
    margin-bottom: 15px;
}

.add-review-form .star {
    color: #ddd;
    font-size: 32px;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-right: 5px;
}

.add-review-form .star:hover,
.add-review-form .star.active {
    color: #ffc107;
    transform: scale(1.1);
}

.add-review-form textarea {
    width: 100%;
    min-height: 120px;
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    resize: vertical;
    font-family: inherit;
    margin-bottom: 15px;
    font-size: 15px;
    transition: border-color 0.2s ease;
}

.add-review-form textarea:focus {
    outline: none;
    border-color: #4A90D9;
}

.add-review-form button {
    background: linear-gradient(135deg, #4A90D9 0%, #357ABD 100%);
    color: white;
    padding: 14px 35px;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(74, 144, 217, 0.3);
}

.add-review-form button:hover {
    background: linear-gradient(135deg, #357ABD 0%, #2c6aa3 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(74, 144, 217, 0.4);
}

.login-message {
    text-align: center;
    color: #666;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 12px;
}

.login-message a {
    color: #4A90D9;
    font-weight: 600;
    text-decoration: none;
}

.login-message a:hover {
    text-decoration: underline;
}

/* Пагинация отзывов */
.pagination-container {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
// Рейтинг для формы отзыва
document.addEventListener('DOMContentLoaded', function() {
    const ratingContainer = document.getElementById('trip-rating');
    const ratingInput = document.getElementById('trip-rating-input');
    
    if (ratingContainer && ratingInput) {
        const stars = ratingContainer.querySelectorAll('.star');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = parseInt(this.dataset.value);
                ratingInput.value = value;
                
                stars.forEach((s, index) => {
                    if (index < value) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
            
            star.addEventListener('mouseover', function() {
                const value = parseInt(this.dataset.value);
                stars.forEach((s, index) => {
                    if (index < value) {
                        s.style.color = '#ffc107';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
        });
        
        ratingContainer.addEventListener('mouseleave', function() {
            const value = parseInt(ratingInput.value);
            stars.forEach((s, index) => {
                if (index < value) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    }
});
</script>

<script>
// Обработка лайков/дизлайков для отзывов
document.addEventListener('DOMContentLoaded', function() {
    console.log('Инициализация реакций на отзывы...');
    
    const reviewLikes = document.querySelectorAll('.review-reactions .likes');
    const reviewDislikes = document.querySelectorAll('.review-reactions .dislikes');
    
    console.log('Найдено лайков:', reviewLikes.length);
    console.log('Найдено дизлайков:', reviewDislikes.length);

    function handleReaction(reviewId, type, element) {
        console.log('Реакция:', reviewId, type);
        
        fetch(`/reviews/${reviewId}/react-simple`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ type })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Ответ сервера:', data);
            if (data.success) {
                const likesEl = element.closest('.review-reactions').querySelector('.likes .reaction-count');
                const dislikesEl = element.closest('.review-reactions').querySelector('.dislikes .reaction-count');
                
                likesEl.textContent = data.likes;
                dislikesEl.textContent = data.dislikes;
            }
        })
        .catch(error => console.error('Ошибка реакции:', error));
    }

    reviewLikes.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const reviewCard = this.closest('.review-card');
            const reviewId = reviewCard ? reviewCard.dataset.reviewId : null;
            console.log('Клик по лайку, reviewId:', reviewId);
            if (reviewId) {
                handleReaction(reviewId, 'like', this);
            }
        });
    });

    reviewDislikes.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const reviewCard = this.closest('.review-card');
            const reviewId = reviewCard ? reviewCard.dataset.reviewId : null;
            console.log('Клик по дизлайку, reviewId:', reviewId);
            if (reviewId) {
                handleReaction(reviewId, 'dislike', this);
            }
        });
    });
});
</script>

<script>
function changeImage(src) {
    document.getElementById('activeImage').src = src;
}

// Инициализация карты с маршрутом
document.addEventListener('DOMContentLoaded', function() {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    const visiblePointsData = JSON.parse(mapElement.dataset.visiblePoints);
    const linePointsData = JSON.parse(mapElement.dataset.linePoints);
    const centerCoords = JSON.parse(mapElement.dataset.center);

    // Инициализация карты БЕЗ attribution (флага Украины)
    const map = L.map('map', {
        zoomControl: true,
        attributionControl: false
    }).setView([centerCoords.lat, centerCoords.lng], 12);

    // Добавляем слой OpenStreetMap БЕЗ attribution
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '',
        noWrap: true
    }).addTo(map);

    // Функция для создания кастомной иконки
    function createCustomIcon(type) {
        const config = {
            'start': { color: '#17a2b8', icon: '🚩' },
            'stop': { color: '#28a745', icon: '📍' },
            'turn': { color: '#6c757d', icon: '🔄' },
            'end': { color: '#dc3545', icon: '🏁' }
        };

        const cfg = config[type] || config['stop'];

        return L.divIcon({
            className: 'custom-marker',
            html: `
                <div style="
                    background: ${cfg.color};
                    color: white;
                    width: 35px;
                    height: 35px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                    border: 3px solid white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                ">${cfg.icon}</div>
            `,
            iconSize: [35, 35],
            iconAnchor: [17, 17],
            popupAnchor: [0, -18]
        });
    }

    // Функция для создания контента поп-апа С ФОТО
    function createPopupContent(point) {
        const badgeClass = `badge-${point.type}`;
        const typeLabels = {
            'start': 'Старт',
            'stop': 'Остановка',
            'turn': 'Точка поворота',
            'end': 'Финиш'
        };
        const typeLabel = typeLabels[point.type] || 'Остановка';

        const imageUrl = point.image ? `/storage/${point.image}` : null;

        return `
            <div class="popup-content">
                ${imageUrl ? `<img loading="lazy" decoding="async" src="${imageUrl}" alt="${point.title}" class="popup-image">` : ''}
                <div class="popup-info">
                    <span class="popup-type-badge ${badgeClass}">${typeLabel}</span>
                    <h4>${point.title}</h4>
                    ${point.description ? `<p>${point.description}</p>` : ''}
                </div>
            </div>
        `;
    }

    // Рисуем линию маршрута по ВСЕМ точкам (включая невидимые)
    const latlngs = linePointsData.map(p => [p.lat, p.lng]);
    
    if (latlngs.length > 1) {
        L.polyline(latlngs, {
            color: '#4A90D9',
            weight: 5,
            opacity: 0.7,
            dashArray: '10, 10',
            lineCap: 'round'
        }).addTo(map);
    }

    // Добавляем маркеры только для ВИДИМЫХ точек
    let popupTimeout = null;
    let currentPopup = null;

    visiblePointsData.forEach(point => {
        const marker = L.marker([point.lat, point.lng], {
            icon: createCustomIcon(point.type)
        }).addTo(map);

        // Создаем поп-ап
        const popup = L.popup({
            closeButton: false,
            autoClose: false,
            closeOnClick: false,
            maxWidth: 300,
            minWidth: 280
        }).setContent(createPopupContent(point));

        // Открытие поп-апа при наведении
        marker.on('mouseover', function(e) {
            clearTimeout(popupTimeout);
            
            // Закрываем предыдущий поп-ап
            if (currentPopup) {
                map.closePopup(currentPopup);
            }
            
            popup.setLatLng(e.latlng);
            popup.openOn(map);
            currentPopup = popup;
        });

        // Закрытие поп-апа при уходе мыши
        marker.on('mouseout', function() {
            popupTimeout = setTimeout(() => {
                if (currentPopup === popup) {
                    map.closePopup(currentPopup);
                    currentPopup = null;
                }
            }, 200);
        });

        // Клик по маркеру тоже открывает поп-ап
        marker.on('click', function(e) {
            clearTimeout(popupTimeout);
            
            if (currentPopup && currentPopup !== popup) {
                map.closePopup(currentPopup);
            }
            
            popup.setLatLng(e.latlng);
            popup.openOn(map);
            currentPopup = popup;
        });
    });

    // Подгоняем зум чтобы показать весь маршрут
    if (latlngs.length > 1) {
        map.fitBounds(L.polyline(latlngs).getBounds(), { padding: [50, 50] });
    }
});

// Функции для модального окна бронирования
const tripPrice = {{ $trip->price }};
const maxPeople = {{ $trip->max_people ?? 10 }};

function openBookingModal() {
    document.getElementById('bookingModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeBookingModal() {
    document.getElementById('bookingModal').style.display = 'none';
    document.body.style.overflow = '';
}

function incrementPeople() {
    const input = document.getElementById('booking-people');
    const currentValue = parseInt(input.value);
    if (currentValue < maxPeople) {
        input.value = currentValue + 1;
        updateTotalPrice(input.value);
    }
}

function decrementPeople() {
    const input = document.getElementById('booking-people');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
        updateTotalPrice(input.value);
    }
}

function updateTotalPrice(people) {
    const total = tripPrice * people;
    document.getElementById('total-price').textContent = total.toLocaleString('ru-RU') + ' ₽';
}

// Закрытие модального окна при клике вне его
window.onclick = function(event) {
    const modal = document.getElementById('bookingModal');
    if (event.target === modal) {
        closeBookingModal();
    }
}

// Закрытие по Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBookingModal();
    }
});
</script>

@endsection
