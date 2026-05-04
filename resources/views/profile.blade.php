@extends('layouts.app2')
@section('content')

<div class="profile-page container">
    <div class="profile-layout">
        <!-- Левая часть - профиль -->
        <aside class="profile-sidebar">
            <div class="profile-avatar">
                @if($user->avatar)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $user->avatar) }}" alt="Аватар" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Ccircle cx=%2250%22 cy=%2250%22 r=%2250%22 fill=%22%234A90D9%22/%3E%3Ctext x=%2250%22 y=%2260%22 font-size=%2240%22 text-anchor=%22middle%22 fill=%22white%22%3E{{ strtoupper(substr($user->login, 0, 1)) }}%3C/text%3E%3C/svg%3E'">
                @else
                    <img loading="lazy" decoding="async" src="/img/default-avatar.png" alt="Аватар" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Ccircle cx=%2250%22 cy=%2250%22 r=%2250%22 fill=%22%234A90D9%22/%3E%3Ctext x=%2250%22 y=%2260%22 font-size=%2240%22 text-anchor=%22middle%22 fill=%22white%22%3E{{ strtoupper(substr($user->login, 0, 1)) }}%3C/text%3E%3C/svg%3E'">
                @endif
            </div>
            
            <h2 class="profile-name">{{ $user->name ?? $user->login }} <button type="button" class="edit-profile-btn" onclick="openProfileEditModal()">✏️</button></h2>
            
            <p class="profile-register-date">
                Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}
            </p>
            <div class="profile-divider"></div>
            
            <nav class="profile-menu">
                <a href="{{ route('profile.index', ['tab' => 'bookings']) }}" class="profile-menu-item {{ $activeTab === 'bookings' ? 'active' : '' }}">
                    Мои поездки
                </a>
                <a href="{{ route('profile.index', ['tab' => 'reviews']) }}" class="profile-menu-item {{ $activeTab === 'reviews' ? 'active' : '' }}">
                    Мои отзывы
                </a>
                <a href="{{ route('profile.index', ['tab' => 'favorites']) }}" class="profile-menu-item {{ $activeTab === 'favorites' ? 'active' : '' }}">
                    Избранное
                </a>
            </nav>
            
            <div class="profile-divider"></div>
            
            <form action="{{ route('logout') }}" method="GET" class="profile-logout">
                <button type="submit" class="profile-logout-btn">Выйти</button>
            </form>
        </aside>

        <!-- Правая часть - контент -->
        <main class="profile-content">
            <!-- Мои поездки -->
            <section id="bookings" class="profile-section {{ $activeTab === 'bookings' ? '' : 'hidden' }}">
                <h2 class="section-title">Мои поездки</h2>

                @if($bookings->count() > 0)
                    <div class="bookings-list">
                        @foreach($bookings as $booking)
                            <div class="booking-card" @if($booking->trip) onclick="location.href='{{ route('trips.show', $booking->trip->id) }}'" style="cursor: pointer;" @endif>
                                @if($booking->trip)
                                    <div class="booking-image">
                                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $booking->trip->main_image) }}" alt="{{ $booking->trip->title }}" onerror="this.src='{{ asset('img/empty.png') }}'; this.onerror=null;">
                                    </div>
                                    <div class="booking-info">
                                        <div class="booking-header">
                                            <h4>{{ $booking->trip->title }}</h4>
                                            <div class="booking-status-badge status-{{ $booking->status }}">
                                                @php
                                                    $statusIcons = [
                                                        'pending' => '⏳',
                                                        'confirmed' => '✅',
                                                        'completed' => '🎉',
                                                        'cancelled' => '❌'
                                                    ];
                                                    $statusTexts = [
                                                        'pending' => 'Ожидает',
                                                        'confirmed' => 'Подтверждено',
                                                        'completed' => 'Завершено',
                                                        'cancelled' => 'Отменено'
                                                    ];
                                                @endphp
                                                <span class="status-icon">{{ $statusIcons[$booking->status] ?? '⏳' }}</span>
                                                <span class="status-text">{{ $statusTexts[$booking->status] ?? $booking->status }}</span>
                                            </div>
                                        </div>
                                        <p class="booking-type">{{ $booking->trip->activity_type }}</p>
                                        <div class="booking-details">
                                            <div class="detail-item">
                                                <span class="detail-icon">📅</span>
                                                <span>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d.m.Y') : 'Не указана' }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-icon">👥</span>
                                                <span>{{ $booking->people ?? $booking->participants }} чел.</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-icon">💰</span>
                                                <span>{{ number_format($booking->total_price ?? $booking->trip->price * ($booking->people ?? $booking->participants), 0, '.', ' ') }} ₽</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="booking-arrow">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 18L15 12L9 6" stroke="#4A90D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                @elseif($booking->item)
                                    <div class="booking-image">
                                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $booking->item->main_image) }}" alt="{{ $booking->item->title }}" onerror="this.src='{{ asset('img/empty.png') }}'; this.onerror=null;">
                                    </div>
                                    <div class="booking-info">
                                        <div class="booking-header"><h4>{{ $booking->item->title }}</h4>
                                            <div class="booking-status-badge status-{{ $booking->status }}">
                                                @php
                                                    $statusIcons = [
                                                        'pending' => '⏳',
                                                        'confirmed' => '✅',
                                                        'completed' => '🎉',
                                                        'cancelled' => '❌'
                                                    ];
                                                    $statusTexts = [
                                                        'pending' => 'Ожидает',
                                                        'confirmed' => 'Подтверждено',
                                                        'completed' => 'Завершено',
                                                        'cancelled' => 'Отменено'
                                                    ];
                                                @endphp
                                                <span class="status-icon">{{ $statusIcons[$booking->status] ?? '⏳' }}</span>
                                                <span class="status-text">{{ $statusTexts[$booking->status] ?? $booking->status }}</span>
                                            </div>
                                        </div>
                                        <div class="booking-header"><h4>{{ $booking->item->title }}</h4></div>
                                        <p class="booking-type">Аренда: {{ $booking->item->activity_type }}</p>
                                        <div class="booking-details">
                                            <div class="detail-item"><span class="detail-icon">📅</span><span>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d.m.Y') : 'Не указана' }}</span></div>
                                            <div class="detail-item"><span class="detail-icon">🕒</span><span>{{ $booking->start_time }} - {{ $booking->end_time }}</span></div>
                                            <div class="detail-item"><span class="detail-icon">👥</span><span>{{ $booking->people }} чел.</span></div>
                                            <div class="detail-item"><span class="detail-icon">💰</span><span>{{ number_format($booking->total_price ?? 0, 0, '.', ' ') }} ₽</span></div>
                                        </div>
                                    </div>
                                @else
                                    <p>Заказ удален</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Пагинация для поездок -->
                    <div class="pagination-container">
                        {{ $bookings->appends(['tab' => 'bookings'])->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <p class="empty-message">У вас пока нет забронированных поездок</p>
                @endif
            </section>

            <!-- Мои отзывы -->
            <section id="reviews" class="profile-section {{ $activeTab === 'reviews' ? '' : 'hidden' }}">
                <h3 class="section-title">Мои отзывы</h3>

                @if($reviews->count() > 0)
                    <div class="reviews-list">
                        @foreach($reviews as $review)
                            @if($review->item)
                                <div class="review-card-wrapper" onclick="location.href='{{ route('items.show', $review->item->id) }}'" style="cursor: pointer;">
                                    <div class="review-card" data-review-id="{{ $review->id }}">
                                        <div class="review-card-header">
                                            <div class="review-rating-badge">
                                                @for($i = 0; $i < 5; $i++)
                                                    <span class="star {{ $i < $review->rating ? 'filled' : '' }}">★</span>
                                                @endfor
                                            </div>
                                            <span class="review-date">{{ $review->created_at->format('d.m.Y') }}</span>
                                        </div>
                                        <p class="review-text">{{ $review->text }}</p>
                                        <div class="review-meta">
                                            <span class="review-likes">
                                                <span class="reaction-icon">👍</span>
                                                <span class="reaction-count">{{ $review->likes }}</span>
                                            </span>
                                            <span class="review-dislikes">
                                                <span class="reaction-icon">👎</span>
                                                <span class="reaction-count">{{ $review->dislikes }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Пагинация для отзывов -->
                    <div class="pagination-container">
                        {{ $reviews->appends(['tab' => 'reviews'])->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <p class="empty-message">У вас пока нет отзывов</p>
                @endif
            </section>

            <!-- Избранное -->
            <section id="favorites" class="profile-section {{ $activeTab === 'favorites' ? '' : 'hidden' }}">
                <h3 class="section-title">Избранное</h3>

                @if($favorites->count() > 0)
                    <div class="favorites-list">
                        @foreach($favorites as $item)
                            <div class="favorite-card" onclick="location.href='{{ route('items.show', $item->id) }}'" style="cursor: pointer;">
                                <div class="favorite-image">
                                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $item->main_image) }}" alt="{{ $item->title }}" onerror="this.src='{{ asset('img/empty.png') }}'; this.onerror=null;">
                                </div>
                                <div class="favorite-info">
                                    <div class="favorite-header">
                                        <h4>{{ $item->title }}</h4>
                                        <div class="favorite-price-badge">
                                            <span class="price-icon">💰</span>
                                            <span class="price-text">{{ $item->price }} ₽</span>
                                        </div>
                                    </div>
                                    @if($item->activity_type)
                                        <p class="favorite-type">{{ $item->activity_type }}</p>
                                    @endif
                                    <div class="favorite-details">
                                        @if($item->max_people)
                                            <div class="detail-item">
                                                <span class="detail-icon">👥</span>
                                                <span>до {{ $item->max_people }} чел.</span>
                                            </div>
                                        @endif
                                        <div class="detail-item">
                                            <span class="detail-icon">⏱️</span>
                                            <span>1 час</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="favorite-arrow">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 18L15 12L9 6" stroke="#4A90D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Пагинация для избранного -->
                    <div class="pagination-container">
                        {{ $favorites->appends(['tab' => 'favorites'])->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <p class="empty-message">У вас пока нет избранных поездок</p>
                @endif
            </section>
        </main>

<div id="profileEditModal" class="profile-modal" style="display:none;">
  <div class="profile-modal-content">
    <div class="profile-modal-header"><h3>Редактировать профиль</h3><span onclick="closeProfileEditModal()">&times;</span></div>
    <form method="POST" action="{{ route('profile.update') }}" class="profile-edit-form">@csrf
      <label>Имя</label><input type="text" name="name" value="{{ $user->name }}">
      <label>Логин</label><input type="text" name="login" value="{{ $user->login }}" required>
      <label>Email</label><input type="email" name="email" value="{{ $user->email }}" required>
      <button type="submit">Сохранить</button>
    </form>
    <form action="{{ route('profile.avatar.upload') }}" method="POST" enctype="multipart/form-data" class="profile-edit-form">@csrf
      <label>Аватар</label><input type="file" name="avatar" accept="image/*" required>
      <button type="submit">Обновить аватар</button>
    </form>
    @if($user->avatar)
    <form action="{{ route('profile.avatar.delete') }}" method="POST" class="profile-edit-form">@csrf
      <button type="submit" class="avatar-btn delete">Удалить аватар</button>
    </form>
    @endif
  </div>
</div>

    </div>
</div>

<style>

.profile-layout {
    margin-top:50px;
    display: flex;
    gap: 40px;
}

.profile-avatar {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    margin: 0;
}

.profile-name {
    margin: 20px 0;
    color: #2b2b2b;
    display:flex;
    align-items:center;
    gap:8px;
}
.edit-profile-btn{background:transparent;border:none;padding:0;line-height:1;cursor:pointer;font-size:18px;box-shadow:none;}

.profile-register-date {
    color: #B2AEAE;
    font-size: 24px;
    margin-bottom: 20px;
    margin-top: 0px;
}

.avatar-upload {
    margin-bottom: 30px;
}

.avatar-btn {
    width: 250px;
    height: 50px;
    color: white;
    background-color: #377FC1;
    border: 0;
    border-radius: 8px;
    font-size: 24px;
    font-family: "Montserrat", sans-serif;
    cursor: pointer;
}

.avatar-btn:hover {
    background: #357ABD;
}

.avatar-btn.delete {
    background: #dc3545;
}

.avatar-btn.delete:hover {
    background: #c82333;
}

.profile-divider {
    height: 1px;
    background: #377FC1;
    margin: 30px 0;
}

.profile-menu {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.profile-menu-item {
    color: #2b2b2b;
    text-decoration: none;
    margin-bottom: 30px;
    transition: background 0.3s;
}

.profile-menu-item:last-child{
    margin-bottom:0px;
}

.profile-menu-item:hover {
    color:#377FC1;
}

.profile-menu-item.active {
    color: #377FC1;
}

.profile-logout-btn {
    background: none;
    border: none;
    color: #dc3545;
    text-align:center;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
}

.profile-logout-btn:hover {
    color: #c82333;
}

.profile-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 40px;
}



.profile-section.hidden {
    display: none;
}

.section-title {
    margin-bottom: 25px;
    color: #2B2B2B;
    text-align: center;
}

.empty-message {
    color: #666;
    text-align: center;
    padding: 40px;
    font-size: 16px;
}

.bookings-list, .reviews-list, .favorites-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.booking-card {
    display: grid;
    grid-template-columns: 280px 1fr auto;
    gap: 25px;
    padding: 25px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    align-items: start;
    width: 100%;
}

.booking-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    transform: translateY(-3px);
    border-color: #4A90D9;
}

.booking-image {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    height: 220px;
    min-width: 280px;
    flex-shrink: 0;
}

.booking-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.booking-card:hover .booking-image img {
    transform: scale(1.05);
}

.booking-info {
    flex: 1;
    min-width: 0;
}

.booking-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 10px;
}

.booking-header h4 {
    font-size: 20px;
    margin: 0;
    color: #2c3e50;
    font-weight: 600;
    line-height: 1.3;
    flex: 1;
}

.booking-status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    backdrop-filter: blur(10px);
    flex-shrink: 0;
}

.booking-status-badge .status-icon {
    font-size: 16px;
}

.booking-status-badge.status-pending {
    background: rgba(255, 193, 7, 0.95);
    color: #fff;
}

.booking-status-badge.status-confirmed {
    background: rgba(40, 167, 69, 0.95);
    color: #fff;
}

.booking-status-badge.status-completed {
    background: rgba(23, 162, 184, 0.95);
    color: #fff;
}

.booking-status-badge.status-cancelled {
    background: rgba(220, 53, 69, 0.95);
    color: #fff;
}

.booking-type {
    color: #4A90D9;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.booking-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    color: #555;
}

.detail-icon {
    font-size: 18px;
    width: 24px;
    text-align: center;
}

.booking-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #f8f9fa;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.booking-card:hover .booking-arrow {
    background: #4A90D9;
}

.booking-card:hover .booking-arrow svg path {
    stroke: #fff;
}

.review-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
}

.review-card-wrapper {
    width: 100%;
}

.review-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.review-rating-badge {
    background: linear-gradient(135deg, #fff9e6 0%, #fff3cc 100%);
    padding: 6px 12px;
    border-radius: 16px;
    display: flex;
    gap: 2px;
    box-shadow: 0 2px 6px rgba(255, 193, 7, 0.15);
}

.review-rating-badge .star {
    color: #ffc107;
    font-size: 16px;
}

.review-rating-badge .star:not(.filled) {
    color: #d0d0d0;
}

.star {
    color: #ddd;
    font-size: 20px;
}

.star.filled {
    color: #ffc107;
}

.review-text {
    color: #555;
    line-height: 1.6;
    margin-bottom: 15px;
    font-size: 14px;
    flex-grow: 1;
}

.review-date {
    color: #95a5a6;
    font-size: 12px;
}

.review-meta {
    display: flex;
    gap: 10px;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
}

.review-meta .review-likes,
.review-meta .review-dislikes {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 14px;
    background: #f8f9fa;
    font-size: 13px;
    transition: background 0.2s ease;
    cursor: pointer;
}

.review-meta .review-likes:hover {
    background: #d4edda;
}

.review-meta .review-dislikes:hover {
    background: #f8d7da;
}

.review-meta .review-likes.active {
    background: #d4edda;
}

.review-meta .review-dislikes.active {
    background: #f8d7da;
}

.reaction-icon {
    font-size: 14px;
}

.reaction-count {
    font-weight: 600;
    color: #555;
}

.favorite-card {
    display: grid;
    grid-template-columns: 280px 1fr auto;
    gap: 25px;
    padding: 25px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    align-items: start;
    width: 100%;
}

.favorite-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    transform: translateY(-3px);
    border-color: #4A90D9;
}

.favorite-image {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    height: 220px;
    min-width: 280px;
    flex-shrink: 0;
}

.favorite-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.favorite-card:hover .favorite-image img {
    transform: scale(1.05);
}

.favorite-info {
    flex: 1;
    min-width: 0;
}

.favorite-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 10px;
}

.favorite-info h4 {
    font-size: 20px;
    margin: 0;
    color: #2c3e50;
    font-weight: 600;
    line-height: 1.3;
    flex: 1;
}

.favorite-price-badge {
    padding: 8px 16px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    backdrop-filter: blur(10px);
    flex-shrink: 0;
    background: linear-gradient(135deg, #4A90D9 0%, #357ABD 100%);
    color: #fff;
}

.favorite-price-badge .price-icon {
    font-size: 16px;
}

.favorite-price-badge .price-text {
    font-size: 15px;
}

.favorite-type {
    color: #4A90D9;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.favorite-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.favorite-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #f8f9fa;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.favorite-card:hover .favorite-arrow {
    background: #4A90D9;
}

.favorite-card:hover .favorite-arrow svg path {
    stroke: #fff;
}

/* Пагинация */
.pagination-container {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}

.pagination-container nav {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    justify-content: center;
}

.pagination-container .page-link {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    color: #4A90D9;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s ease;
}

.pagination-container .page-link:hover {
    background: #4A90D9;
    color: #fff;
    border-color: #4A90D9;
}

.pagination-container .page-item.active .page-link {
    background: #4A90D9;
    border-color: #4A90D9;
    color: #fff;
}

.pagination-container .page-item.disabled .page-link {
    color: #ccc;
    cursor: not-allowed;
}

/* Адаптив для карточек бронирований и отзывов */
@media (max-width: 768px) {
    .booking-card {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .booking-image {
        height: 200px;
        min-width: 100%;
    }

    .booking-arrow {
        display: none;
    }

    .booking-details {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .detail-item {
        flex: 1;
        min-width: 140px;
    }

    .favorite-card {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .favorite-image {
        height: 200px;
        min-width: 100%;
    }

    .favorite-arrow {
        display: none;
    }

    .favorite-details {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .favorite-details .detail-item {
        flex: 1;
        min-width: 140px;
    }

    .review-card {
        padding: 15px;
    }

    .review-text {
        font-size: 14px;
    }
}
</style>

<script>
// Обработка лайков/дизлайков для отзывов
document.addEventListener('DOMContentLoaded', function() {
    const reviewLikes = document.querySelectorAll('.review-likes');
    const reviewDislikes = document.querySelectorAll('.review-dislikes');

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
                const likesEl = element.closest('.review-meta').querySelector('.review-likes .reaction-count');
                const dislikesEl = element.closest('.review-meta').querySelector('.review-dislikes .reaction-count');

                likesEl.textContent = data.likes;
                dislikesEl.textContent = data.dislikes;

                // Визуальное выделение
                if (type === 'like') {
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
</script>
<script>
function openProfileEditModal(){ const m=document.getElementById('profileEditModal'); if(m) m.style.display='block'; }
function closeProfileEditModal(){ const m=document.getElementById('profileEditModal'); if(m) m.style.display='none'; }
window.addEventListener('click', function(e){ const m=document.getElementById('profileEditModal'); if(m && e.target===m) closeProfileEditModal(); });
</script>
@endsection