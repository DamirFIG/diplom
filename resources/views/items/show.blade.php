@extends('layouts.app')

@section('content')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
.item-page {
    display: flex;
    gap: 30px;
    max-width: 1200px;
    margin: 80px auto 0;
    padding: 30px 20px;
}

.item-left {
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

    .item-page {
        flex-direction: column;
        gap: 18px;
        margin: 20px auto 0;
        padding: 16px 12px;
    }

    .item-left {
        max-width: 100%;
    }
}

.item-right {
    flex: 1;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    height: fit-content;
}

.item-right h4 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #333;
    font-family: 'Montserrat', sans-serif;
}

.item-right p {
    color: #2b2b2b;
    margin-bottom: 15px;
    line-height: 1.6;
}

.item-right .price {
    font-size: 24px;
    font-weight: 700;
    color: #4A90D9;
    margin: 20px 0;
}

.btn-book {
    background: #4A90D9;
    color: white;
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

.booking-modal,
.booking-modal * {
    box-sizing: border-box;
}

.item-booking-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.item-booking-form .booking-row {
    margin: 0;
}

.item-booking-form label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #546170;
    margin-bottom: 7px;
}

.item-booking-form input,
.item-booking-form textarea {
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #d8e0ea;
    border-radius: 12px;
    background: #fff;
    color: #1f2933;
    font-family: inherit;
    font-size: 16px;
    line-height: 1.3;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.item-booking-form input:focus,
.item-booking-form textarea:focus {
    outline: none;
    border-color: #4A90D9;
    box-shadow: 0 0 0 4px rgba(74, 144, 217, 0.16);
    background: #fbfdff;
}

.item-booking-form textarea {
    min-height: 76px;
    resize: vertical;
}

.booking-time-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
}

.booking-time-row > div {
    min-width: 0;
}

.booking-modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background: rgba(15, 23, 42, 0.58);
    backdrop-filter: blur(4px);
    z-index: 1000;
}

.booking-modal-content {
    width: min(100%, 500px);
    max-height: calc(100vh - 48px);
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 24px 70px rgba(15, 23, 42, 0.32);
    animation: bookingModalIn 0.22s ease-out;
}

.booking-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    padding: 24px 26px 18px;
    border-bottom: 1px solid #edf1f5;
    background: linear-gradient(135deg, #f8fbff 0%, #ffffff 68%);
}

.booking-modal-header h3 {
    margin: 0;
    max-width: 390px;
    color: #1f2933;
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(24px, 3vw, 32px);
    font-weight: 500;
    line-height: 1.18;
}

.booking-modal-body {
    padding: 20px 26px 26px;
    overflow-y: auto;
    max-height: calc(100vh - 190px);
}

.booking-modal-close {
    flex: 0 0 auto;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 50%;
    background: #eef4fb;
    color: #2c3e50;
    font-size: 24px;
    line-height: 1;
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
}

.booking-modal-close:hover {
    background: #4A90D9;
    color: #fff;
    transform: rotate(90deg);
}

.booking-total-preview {
    margin: 2px 0 0;
    padding: 12px 14px;
    border-radius: 16px;
    background: linear-gradient(135deg, #eef6ff 0%, #f7fbff 100%);
    color: #24547d;
    font-size: 18px;
    font-weight: 700;
}

.item-booking-form .btn-book {
    margin-top: 4px;
    padding: 13px 18px;
    border-radius: 12px;
    background: linear-gradient(135deg, #4A90D9 0%, #357ABD 100%);
    box-shadow: 0 10px 24px rgba(74, 144, 217, 0.34);
}

.item-booking-form .btn-book:hover {
    background: linear-gradient(135deg, #357ABD 0%, #2c6aa3 100%);
    transform: translateY(-1px);
}

@keyframes bookingModalIn {
    from {
        opacity: 0;
        transform: translateY(18px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@media (max-width: 576px) {
    .booking-modal {
        align-items: flex-end;
        padding: 8px;
    }

    .booking-modal-content {
        border-radius: 18px 18px 12px 12px;
        max-height: calc(100vh - 16px);
    }

    .booking-modal-header {
        padding: 16px 16px 12px;
        gap: 12px;
    }

    .booking-modal-header h3 {
        font-size: 22px;
    }

    .booking-modal-close {
        width: 32px;
        height: 32px;
        font-size: 22px;
    }

    .booking-modal-body {
        padding: 14px 16px 16px;
        max-height: calc(100vh - 112px);
    }

    .item-booking-form {
        gap: 10px;
    }

    .item-booking-form label {
        margin-bottom: 5px;
        font-size: 12px;
    }

    .item-booking-form input,
    .item-booking-form textarea {
        padding: 10px 11px;
        border-radius: 10px;
        font-size: 14px;
    }

    .item-booking-form textarea {
        min-height: 64px;
    }

    .booking-time-row {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
    }

    .booking-total-preview {
        padding: 10px 12px;
        border-radius: 12px;
        font-size: 16px;
    }
}

/* Стили для блока с маршрутом */
.route-section {
    max-width: 1200px;
    margin: 30px auto;
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

/* Адаптив карточки и связанных блоков */
@media (max-width: 768px) {
    .item-right {
        padding: 16px;
        border-radius: 14px;
    }

    .item-right h4 {
        font-size: 22px;
        margin: 0 0 14px;
    }

    .item-right p {
        font-size: 15px;
        line-height: 1.5;
        margin-bottom: 10px;
    }

    .item-right .price {
        font-size: 21px;
        margin: 16px 0 10px;
    }

    .btn-book {
        min-height: 46px;
        margin-top: 14px;
        padding: 12px 16px;
        font-size: 16px;
    }

    .thumbs {
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 4px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .thumbs::-webkit-scrollbar {
        display: none;
    }

    .thumb {
        flex: 0 0 auto;
        width: 62px;
        height: 62px;
    }

    .route-section,
    .reviews-section {
        margin: 18px 12px;
        padding: 18px 12px;
        border-radius: 14px;
    }

    #map {
        height: 320px;
    }

    .leaflet-popup-content {
        width: min(260px, 78vw) !important;
        max-width: min(260px, 78vw);
    }
}




.reviews-section {
    max-width: 1200px;
    margin: 30px auto;
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
    color: #555;
    line-height: 1.7;
    margin-bottom: 20px;
    font-size: 15px;
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
    padding: 20px;
    border-radius: 14px;
    border: 1px solid #e0e0e0;
    margin-bottom: 30px;
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
    font-size: 26px;
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
    min-height: 90px;
    padding: 12px;
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
    padding: 10px 24px;
    border: none;
    border-radius: 12px;
    font-size: 15px;
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
@endpush



<div class="item-page">
    <div class="item-left">
        {{-- Большое фото --}}
        <div class="main-image">
            <img loading="lazy" decoding="async" id="activeImage" src="{{ asset('storage/' . $item->main_image) }}" alt="{{ $item->title }}">
        </div>

        {{-- Мини-фото --}}
        <div class="thumbs">
            {{-- Главное фото как первая миниатюра --}}
            <img loading="lazy" decoding="async" src="{{ asset('storage/' . $item->main_image) }}"
                 class="thumb"
                 alt="Main"
                 onclick="changeImage(this.src)">

            {{-- Галерея --}}
            @php
                $gallery = is_array($item->gallery) ? $item->gallery : [];
            @endphp

            @if(count($gallery) > 1)
                @foreach(array_slice($gallery, 1) as $img)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/'.$img) }}" class="thumb" alt="Gallery" onclick="changeImage(this.src)">
                @endforeach
            @endif
        </div>
    </div>

    <div class="item-right">
        <h4>{{ $item->title }}</h4>
        <p><strong>Тип:</strong> {{ $item->activity_type }}</p>
        <p style="word-wrap: break-word; overflow-wrap: break-word;">{{ $item->description ?? '' }}</p>
        <p><strong>Допустимое количество людей:</strong> {{ $item->max_people ?? 'не указано' }}</p>
        <p><strong>Длительность маршрута:</strong> {{ $item->duration_minutes ?? 'не указано' }} минут</p>
        <p><strong>Минимальный возраст:</strong> {{ $item->min_age ?? '0' }}</p>
        <p class="price">{{ $item->price }} ₽ / час</p>

        <button class="btn-book" type="button" onclick="openItemBookingModal()">Забронировать</button>
    </div>
</div>

<div id="itemBookingModal" class="booking-modal" style="display:none;">
    <div class="booking-modal-content" role="dialog" aria-modal="true" aria-labelledby="itemBookingModalTitle">
        <div class="booking-modal-header">
            <h3 id="itemBookingModalTitle">Бронирование аренды</h3>
            <button class="booking-modal-close" type="button" onclick="closeItemBookingModal()" aria-label="Закрыть форму бронирования">&times;</button>
        </div>
        <div class="booking-modal-body">
            <form action="{{ route('items.book') }}" method="POST" class="item-booking-form">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <div class="booking-row">
                    <label for="booking_date">Дата</label>
                    <input type="date" name="booking_date" id="booking_date" required min="{{ now()->toDateString() }}">
                </div>
                <div class="booking-row booking-time-row">
                    <div>
                        <label for="start_time">С</label>
                        <input type="time" name="start_time" id="start_time" required>
                    </div>
                    <div>
                        <label for="end_time">До</label>
                        <input type="time" name="end_time" id="end_time" required>
                    </div>
                </div>
                <div class="booking-row">
                    <label for="booking_people">Людей</label>
                    <input type="number" name="people" id="booking_people" value="1" min="1" max="{{ $item->max_people ?? 10 }}" required>
                </div>
                <div class="booking-row">
                    <label for="booking_comment">Комментарий</label>
                    <textarea name="comment" id="booking_comment" rows="3" placeholder="Напишите пожелания к аренде"></textarea>
                </div>
                <p id="booking_total_preview" class="booking-total-preview">Итоговая цена: {{ number_format($item->price, 0, ',', ' ') }} ₽</p>
                <button class="btn-book" type="submit">Подтвердить бронирование</button>
            </form>
        </div>
    </div>
</div>

{{-- Блок с маршрутом на карте --}}
@if($item->route && $item->route->points->count())
    <div class="route-section">
        <h3>🗺️ Маршрут</h3>
        <div id="map"
             data-visible-points='@json($item->route->allPoints)'
             data-line-points='@json($item->route->allLinePoints)'
             data-center='@json(["lat" => $item->route->start_lat ?? 59.9343, "lng" => $item->route->start_lng ?? 30.3351])'>
        </div>
    </div>
@endif

<!-- Отзывы -->
<section class="reviews-section container">
    <h3 class="reviews-title">Отзывы <span class="reviews-count">({{ $reviews->total() }})</span></h3>

    <!-- Форма добавления отзыва -->
    @auth
        @if($canReview)
        <div class="add-review-form">
            <h4>Оставить отзыв</h4>
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="rating" id="item-rating-input" value="0">

                <div class="rating-stars" id="item-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star" data-value="{{ $i }}">★</span>
                    @endfor
                </div>

                <textarea name="text" maxlength="256" placeholder="Напишите ваш отзыв..." required></textarea>
                <button type="submit">Отправить отзыв</button>
            </form>
        </div>
        @else
        <div class="login-message">Оставить отзыв можно только после заказа со статусом «Выполнен».</div>
        @endif
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
    


<script>
// Рейтинг для формы отзыва
document.addEventListener('DOMContentLoaded', function() {
    const ratingContainer = document.getElementById('item-rating');
    const ratingInput = document.getElementById('item-rating-input');
    
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
function changeImage(src) {
    document.getElementById('activeImage').src = src;
}

// Обработка лайков/дизлайков для отзывов
document.addEventListener('DOMContentLoaded', function() {
    const reviewLikes = document.querySelectorAll('.review-reactions .likes');
    const reviewDislikes = document.querySelectorAll('.review-reactions .dislikes');

    function handleReaction(reviewId, type, element) {
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
            if (data.success) {
                const likesEl = element.closest('.review-reactions').querySelector('.likes .reaction-count');
                const dislikesEl = element.closest('.review-reactions').querySelector('.dislikes .reaction-count');
                
                likesEl.textContent = data.likes;
                dislikesEl.textContent = data.dislikes;

                if (type === 'like') {
                    element.classList.toggle('active');
                } else {
                    element.classList.toggle('active');
                }
            }
        })
        .catch(error => console.error('Ошибка реакции:', error));
    }

    reviewLikes.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const reviewId = this.closest('.review-card').dataset.reviewId;
            if (reviewId) {
                handleReaction(reviewId, 'like', this);
            }
        });
    });

    reviewDislikes.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const reviewId = this.closest('.review-card').dataset.reviewId;
            if (reviewId) {
                handleReaction(reviewId, 'dislike', this);
            }
        });
    });
});

// Инициализация карты с маршрутом
</script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
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
</script>


<script>
function openItemBookingModal(){ document.getElementById('itemBookingModal').style.display='flex'; }
function closeItemBookingModal(){ document.getElementById('itemBookingModal').style.display='none'; }

document.addEventListener('DOMContentLoaded', function () {
  const start = document.getElementById('start_time');
  const end = document.getElementById('end_time');
  const people = document.getElementById('booking_people');
  const preview = document.getElementById('booking_total_preview');
  const pricePerHour = {{ $item->price }};
  function recalc() {
    if (!start || !end || !people || !preview || !start.value || !end.value) return;
    const [sh, sm] = start.value.split(':').map(Number);
    const [eh, em] = end.value.split(':').map(Number);
    const startM = sh * 60 + sm;
    const endM = eh * 60 + em;
    if (endM <= startM) { preview.textContent = 'Укажите корректное время'; return; }
    const hours = Math.ceil((endM - startM) / 60);
    const total = hours * pricePerHour * (parseInt(people.value || '1', 10));
    preview.textContent = 'Итоговая цена: ' + total.toLocaleString('ru-RU') + ' ₽';
  }
  [start, end, people].forEach(el => el && el.addEventListener('input', recalc));
});
</script>
@endsection
