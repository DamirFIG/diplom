@extends('layouts.app')
@section('title', 'Главная')

@section('content')

@php
    $types = [
        'гидроцикл' => 'Гидроцикл',
        'банан' => 'Банан',
        'флайборд' => 'Флайборд',
        'сапборд' => 'Сапборд',
        'катамаран' => 'Катамаран',
    ];
@endphp

<style>
.catalog-top-filters {
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap:30px;
    flex-wrap: wrap;
}

.catalog-top-left {
    display: flex;
    align-items: center;
}

.price-slider-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}

.price-label {
    font-weight: 500;
    color: #2B2B2B;
    white-space: nowrap;
}

.price-slider {
    -webkit-appearance: none;
    width: 120px;
    height: 4px;
    background: #ddd;
    border-radius: 2px;
    outline: none;
}

.price-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #4A90D9;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(74, 144, 217, 0.3);
    transition: transform 0.2s;
}

.price-slider::-webkit-slider-thumb:hover {
    transform: scale(1.1);
}

.price-slider::-moz-range-thumb {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #4A90D9;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 4px rgba(74, 144, 217, 0.3);
}

.price-value {
    font-size: 24px;
    font-weight:500;
    color: #4A90D9;
    white-space: nowrap;
    width: 70px;
}

.search-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    padding: 10px 15px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border: 1px solid #e0e0e0;
}

.search-wrapper svg {
    flex-shrink: 0;
}

.search-input {
    border: none;
    outline: none;
    font-size: 15px;
    color: #2B2B2B;
    background: transparent;
    min-width: 200px;
}

.search-input::placeholder {
    color: #999;
}

.sort {
    padding: 12px 20px;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    background: #fff;
    color: #2B2B2B;
    font-size: 15px;
    cursor: pointer;
    outline: none;
    transition: all 0.2s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.sort:hover {
    border-color: #4A90D9;
}

.sort:focus {
    border-color: #4A90D9;
    box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.1);
}

/* Стили для сердечка избранного */
.item-card-image {
    position: relative;
}

.favorite-btn {
    padding: 0;
    position: absolute;
    bottom: 15px;
    right: 15px;
    width: 45px;
    height: 45px;
    border: none;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.3s ease;
    z-index: 10;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
}

.item-card:hover .favorite-btn {
    opacity: 1;
    transform: scale(1);
}

.favorite-btn:hover {
    background: #fff !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.favorite-btn .heart-icon {
    width: 24px;
    height: 24px;
    fill: none;
    stroke: #dc3545;
    stroke-width: 2;
    transition: all 0.3s ease;
}

.favorite-btn.active .heart-icon {
    fill: #dc3545;
    stroke: #dc3545;
}

.favorite-btn:active {
    transform: scale(0.95);
}

/* Стили для поездок */
.trips-title {
    color: #2b2b2b;
    text-align: center;
    margin-bottom: 10px;
}

.trips-subtitle {
    font-size: 18px;
    color: #7f8c8d;
    text-align: center;
    margin-bottom: 40px;
}

.trips-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    flex: 1;
    min-width: 0;
}

.trip-card {
    min-height: 520px;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
    display: flex;
    flex-direction: column;
}

.trip-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
    border-color: #4A90D9;
}

.trip-card-image {
    position: relative;
    height: 240px;
    overflow: hidden;
}

.trip-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.trip-card:hover .trip-card-image img {
    transform: scale(1.1);
}

.trip-card-content {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.trip-card-title {
    font-size: 22px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 12px;
    line-height: 1.3;
}

.trip-card-details {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
}

.trip-card-details .detail {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #555;
    background: #f8f9fa;
    padding: 8px 14px;
    border-radius: 12px;
}

.detail-icon {
    font-size: 18px;
}

.trip-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.trip-card-price {
    display: flex;
    flex-direction: column;
}

.price-label {
    font-size: 13px;
    color: #999;
    margin-top: 5px;
}

.trip-card-btn {
    background: linear-gradient(135deg, #4A90D9 0%, #357ABD 100%);
    color: #fff;
    padding: 12px 24px;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(74, 144, 217, 0.3);
}

.trip-card-btn:hover {
    background: linear-gradient(135deg, #357ABD 0%, #2c6aa3 100%);
    transform: translateX(5px);
    box-shadow: 0 6px 18px rgba(74, 144, 217, 0.4);
}

.trip-card-btn svg {
    transition: transform 0.3s ease;
}

.trip-card-btn:hover svg {
    transform: translateX(3px);
}

.empty-message {
    text-align: center;
    padding: 60px 20px;
    color: #999;
    font-size: 18px;
}

@media (max-width: 768px) {
    .trips-list {
        grid-template-columns: 1fr;
    }

    .trips-title {
        font-size: 28px;
    }

    .trips-subtitle {
        font-size: 16px;
    }
}

/* Стили для карточек аренды */
.catalog-wrapper {
    display: flex;
    gap: 20px;
    margin-top: 30px;
}

.catalog-filters {
    min-width: 200px;
    background: #fff;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    height: fit-content;
    position: sticky;
    top: 90px;
}

.catalog-filters ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.catalog-filters li {
    margin-bottom: 10px;
}

.catalog-filters li:last-child {
    margin-bottom: 0;
}

.catalog-filters a {
    display: block;
    padding: 12px 16px;
    color: #555;
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.2s ease;
    font-size: 15px;
}

.catalog-filters a:hover,
.catalog-filters li.active a {
    background: linear-gradient(135deg, #4A90D9 0%, #357ABD 100%);
    color: #fff;
}

.catalog-items {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
}

.item-card {
    min-height: 520px;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
    display: flex;
    flex-direction: column;
}

.item-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
    border-color: #4A90D9;
}

.item-card-image {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.item-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.item-card:hover .item-card-image img {
    transform: scale(1.1);
}

.item-card-content {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.item-card-title {
    font-size: 20px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 8px;
    line-height: 1.3;
}

.item-card-type {
    font-size: 14px;
    color: #4A90D9;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 15px;
}

.item-card-details {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
    flex: 1;
}

.item-card-details .detail {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #555;
    background: #f8f9fa;
    padding: 8px 14px;
    border-radius: 12px;
}

.item-card-footer {
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.item-card-btn {
    width: 100%;
    background: linear-gradient(135deg, #4A90D9 0%, #357ABD 100%);
    color: #fff;
    padding: 12px 24px;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(74, 144, 217, 0.3);
}

.item-card-btn:hover {
    background: linear-gradient(135deg, #357ABD 0%, #2c6aa3 100%);
    box-shadow: 0 6px 18px rgba(74, 144, 217, 0.4);
}

.item-card-btn svg {
    transition: transform 0.3s ease;
}

.item-card-btn:hover svg {
    transform: translateX(3px);
}

@media (max-width: 768px) {
    .catalog-items {
        grid-template-columns: 1fr;
    }

    .catalog-wrapper {
        flex-direction: column;
    }

    .catalog-filters {
        position: static;
        min-width: 100%;
    }

    .trips-list {
        grid-template-columns: 1fr;
    }

    .trips-title {
        font-size: 28px;
    }

    .trips-subtitle {
        font-size: 16px;
    }
}

/* FAQ */
.faq {
    margin-top: 50px;
}

.faq h2 {
    text-align: center;
    font-size: 36px;
    font-weight: 700;
    color: #2B2B2B;
    margin-bottom: 40px;
}

.faq details {
    background: #fff;
    border-radius: 16px;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.faq details:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

.faq details[open] {
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.faq summary {
    padding: 20px 25px;
    font-size: 18px;
    font-weight: 600;
    color: #2B2B2B;
    cursor: pointer;
    list-style: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.faq summary::-webkit-details-marker {
    display: none;
}

.faq summary::after {
    content: '+';
    font-size: 24px;
    font-weight: 400;
    color: #4A90D9;
    transition: transform 0.3s ease;
    flex-shrink: 0;
    margin-left: 15px;
}

.faq details[open] summary::after {
    content: '−';
}

.faq details[open] summary {
    background: linear-gradient(135deg, #4A90D9 0%, #357ABD 100%);
    color: #fff;
}

.faq details[open] summary::after {
    color: #fff;
}

.faq details p {
    padding: 20px 25px;
    margin: 0;
    font-size: 16px;
    line-height: 1.7;
    color: #555;
    background: #fff;
    border-top: 1px solid #f0f0f0;
}

@media (max-width: 768px) {
    .faq {
        padding: 60px 0;
    }

    .faq h2 {
        font-size: 28px;
        margin-bottom: 30px;
    }

    .faq summary {
        font-size: 16px;
        padding: 18px 20px;
    }

    .faq details p {
        font-size: 15px;
        padding: 18px 20px;
    }
}
</style>

<!-- Баннер -->
<section class="banner">
    <h1>Водный отдых</h1>
    <h3>Лучшие развлечения на воде только у нас</h3>
</section>

<!-- О НАС -->
<section class="about-section">
    <h2 class="about-title">О нас</h2>
    <div class="about-container">
        <div class="about-content">
            <div class="about-text">
                <p class="about-description">
                    Мы занимаемся организацией отдыха на водных видах транспорта.
                    Предлагаем прогулки, маршруты и аренду для активного и спокойного отдыха.
                </p>
                <div class="about-features">
                    <div class="feature-item">
                        <div class="feature-icon">🌊</div>
                        <div class="feature-text">
                            <h4>Богатый опыт</h4>
                            <p>Более 5 лет на рынке водного отдыха</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">⚡</div>
                        <div class="feature-text">
                            <h4>Современное оборудование</h4>
                            <p>Только новые и надежные гидроциклы</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">👨‍👩‍👧‍👦</div>
                        <div class="feature-text">
                            <h4>Профессиональные инструкторы</h4>
                            <p>Опытные гиды для безопасного отдыха</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="about-gallery">
            <div class="gallery-item gallery-item-large">
                <img src="/img/about-1.jpg" alt="Наша команда" class="gallery-image" onclick="openImageModal(this.src)" onerror="this.src='/img/empty.png'">
            </div>
            <div class="gallery-item">
                <img src="/img/about-2.jpg" alt="Процесс" class="gallery-image" onclick="openImageModal(this.src)" onerror="this.src='/img/empty.png'">
            </div>
            <div class="gallery-item">
                <img src="/img/about-3.jpg" alt="Оборудование" class="gallery-image" onclick="openImageModal(this.src)" onerror="this.src='/img/empty.png'">
            </div>
        </div>
    </div>
</section>

<!-- КАТАЛОГ -->
<section class="catalog container" id="catalog">
    <h2>Аренда</h2>

    <!-- ПОИСК / СОРТИРОВКА / ЦЕНА -->
    <div class="catalog-top-filters">
            <div class="price-slider-wrapper">
                <span class="price-label">Цена:</span>
                <input type="range" id="priceSlider" class="price-slider" min="0" max="100000" value="100000" step="100">
                <span class="price-value" id="priceValue">100 000 ₽</span>
            </div>


            <div class="search-wrapper">
                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.95673 0.00262737C12.5779 -0.0568686 15.1211 0.896025 17.0573 2.66376C18.9936 4.43159 20.1736 6.87804 20.3522 9.49384C20.5308 12.1097 19.6944 14.6941 18.0163 16.7087L24.9997 23.6921L23.6921 24.9997L16.7087 18.0163C14.6941 19.6944 12.1097 20.5308 9.49384 20.3522C6.87804 20.1736 4.43159 18.9936 2.66376 17.0573C0.896025 15.1211 -0.0568686 12.5779 0.00262737 9.95673C0.0621937 7.33544 1.13007 4.83808 2.98407 2.98407C4.83808 1.13007 7.33544 0.0621937 9.95673 0.00262737ZM11.8278 2.04169C10.2135 1.72059 8.53992 1.88543 7.01923 2.51532C5.4986 3.14522 4.1993 4.21222 3.28485 5.58075C2.37046 6.94924 1.88163 8.55795 1.88153 10.2038C1.88398 12.4103 2.76174 14.5264 4.32196 16.0866C5.88208 17.6467 7.99748 18.5245 10.2038 18.527C11.8498 18.527 13.4592 18.0382 14.8278 17.1237C16.1963 16.2093 17.2634 14.91 17.8933 13.3893C18.5231 11.8687 18.688 10.1951 18.3669 8.58075C18.0458 6.96637 17.2534 5.48294 16.0895 4.31903C14.9256 3.15512 13.4422 2.36281 11.8278 2.04169Z" fill="#2B2B2B"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Поиск..." class="search-input" value="{{ request('search') }}">
            </div>

            <select name="sort" id="sortSelect" class="sort" data-ajax-filter>
                <option value="">По умолчанию</option>
                <option value="price_asc" @selected(request('sort') === 'price_asc')>Сначала дешевле</option>
                <option value="price_desc" @selected(request('sort') === 'price_desc')>Сначала дороже</option>
            </select>

    </div>

    <div class="catalog-wrapper">

        <!-- ЛЕВАЯ ПАНЕЛЬ -->
        <aside class="catalog-filters">
            <ul>
                <li class="{{ request('activity_type') === null ? 'active' : '' }}">
                    <a href="#" data-ajax-filter data-activity-type="" data-section="catalog">
                        Все
                    </a>
                </li>

                @foreach($types as $key => $label)
                    <li class="{{ request('activity_type') === $key ? 'active' : '' }}">
                        <a href="#" data-ajax-filter data-activity-type="{{ $key }}" data-section="catalog">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <!-- КАРТОЧКИ -->
        <div class="catalog-items">
            @forelse($items as $item)
                <div class="item-card" data-item-id="{{ $item->id }}" onclick="location.href='{{ route('items.show', $item->id) }}'" style="cursor: pointer;">
                    <div class="item-card-image">
                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $item->main_image) }}" alt="{{ $item->title }}" onerror="this.src='{{ asset('img/empty.png') }}'; this.onerror=null;">
                        @auth
                            <button class="favorite-btn" data-item-id="{{ $item->id }}" title="Добавить в избранное" onclick="event.stopPropagation()">
                                <svg class="heart-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.1 8.64c-.92-1.1-2.26-1.74-3.71-1.74C6.07 6.9 4.5 8.5 4.5 10.57c0 2.69 2.45 5.25 6.21 8.67l1.29 1.17 1.29-1.17c3.76-3.42 6.21-5.98 6.21-8.67 0-2.07-1.57-3.67-3.89-3.67-1.45 0-2.79.64-3.71 1.74l-.3.36-.3-.36z"/>
                                </svg>
                            </button>
                        @endauth
                    </div>
                    <div class="item-card-content">
                        <h3 class="item-card-title">{{ $item->title }}</h3>
                        <p class="item-card-type">{{ $item->activity_type }}</p>
                        <div class="item-card-details">
                            <div class="detail">
                                <span class="detail-icon">💰</span>
                                <span>{{ $item->price }} ₽ / час</span>
                            </div>
                            @if($item->max_people)
                                <div class="detail">
                                    <span class="detail-icon">👥</span>
                                    <span>до {{ $item->max_people }} чел</span>
                                </div>
                            @endif
                        </div>
                        <div class="item-card-footer">
                            <button class="item-card-btn">
                                Забронировать
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="empty-message">Ничего не найдено</p>
            @endforelse
        </div>

    </div>

    <!-- ПАГИНАЦИЯ -->
    @if ($items->lastPage() > 1)
        <div class="pagination-wrapper">
            <ul class="pagination">
                {{-- ⬅ Назад --}}
                @if ($items->onFirstPage())
                    <li class="disabled arrow">
                        <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="">
                    </li>
                @else
                    <li class="arrow">
                        <a href="{{ $items->previousPageUrl() }}#catalog">
                            <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="">
                        </a>
                    </li>
                @endif

                <!-- Цифры -->
                @for ($i = 1; $i <= $items->lastPage(); $i++)
                    @if ($i == $items->currentPage())
                        <li class="active">
                            <span>{{ $i }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $items->url($i) }}#catalog">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                <!-- Вперёд -->
                @if ($items->hasMorePages())
                    <li class="arrow">
                        <a href="{{ $items->nextPageUrl() }}#catalog">
                            <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="" class="arrow-right">
                        </a>
                    </li>
                @else
                    <li class="disabled arrow">
                        <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="" class="arrow-right">
                    </li>
                @endif
            </ul>
        </div>
    @endif

</section>
<!-- ПОЕЗДКИ -->
<section class="trips container" id="trips">
    <h2 class="trips-title">Поездки</h2>

    <!-- ПОИСК / СОРТИРОВКА / ФИЛЬТРЫ -->
    <div class="catalog-top-filters">
        <button type="button" id="openTripFilters" class="sort" style="display: flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 5H17M3 10H17M3 15H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Фильтры
        </button>

        <div class="search-wrapper">
            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.95673 0.00262737C12.5779 -0.0568686 15.1211 0.896025 17.0573 2.66376C18.9936 4.43159 20.1736 6.87804 20.3522 9.49384C20.5308 12.1097 19.6944 14.6941 18.0163 16.7087L24.9997 23.6921L23.6921 24.9997L16.7087 18.0163C14.6941 19.6944 12.1097 20.5308 9.49384 20.3522C6.87804 20.1736 4.43159 18.9936 2.66376 17.0573C0.896025 15.1211 -0.0568686 12.5779 0.00262737 9.95673C0.0621937 7.33544 1.13007 4.83808 2.98407 2.98407C4.83808 1.13007 7.33544 0.0621937 9.95673 0.00262737ZM11.8278 2.04169C10.2135 1.72059 8.53992 1.88543 7.01923 2.51532C5.4986 3.14522 4.1993 4.21222 3.28485 5.58075C2.37046 6.94924 1.88163 8.55795 1.88153 10.2038C1.88398 12.4103 2.76174 14.5264 4.32196 16.0866C5.88208 17.6467 7.99748 18.5245 10.2038 18.527C11.8498 18.527 13.4592 18.0382 14.8278 17.1237C16.1963 16.2093 17.2634 14.91 17.8933 13.3893C18.5231 11.8687 18.688 10.1951 18.3669 8.58075C18.0458 6.96637 17.2534 5.48294 16.0895 4.31903C14.9256 3.15512 13.4422 2.36281 11.8278 2.04169Z" fill="#2B2B2B"/>
            </svg>
            <input type="text" id="tripSearchInput" placeholder="Поиск..." class="search-input" value="{{ request('trip_search') }}">
        </div>

        <select name="trip_sort" id="tripSortSelect" class="sort" data-ajax-filter data-section="trips">
            <option value="">По умолчанию</option>
            <option value="price_asc" @selected(request('trip_sort') === 'price_asc')>Сначала дешевле</option>
            <option value="price_desc" @selected(request('trip_sort') === 'price_desc')>Сначала дороже</option>
            <option value="date_asc" @selected(request('trip_sort') === 'date_asc')>Сначала ранние даты</option>
            <option value="date_desc" @selected(request('trip_sort') === 'date_desc')>Сначала поздние даты</option>
        </select>
    </div>

    <div class="catalog-wrapper">

        <!-- ЛЕВАЯ ПАНЕЛЬ (ФИЛЬТРЫ) -->
        <aside class="catalog-filters">
            <ul>
                <!-- Тип активности -->
                <li style="margin-bottom: 15px;">
                    <label style="font-weight: 500; font-size: 14px; color: #2B2B2B; display: block; margin-bottom: 8px;">Тип активности</label>
                </li>
                <li class="{{ request('trip_activity_type') === null ? 'active' : '' }}">
                    <a href="#" data-ajax-filter data-activity-type="" data-section="trips">
                        Все
                    </a>
                </li>

                @foreach($types as $key => $label)
                    <li class="{{ request('trip_activity_type') === $key ? 'active' : '' }}">
                        <a href="#" data-ajax-filter data-activity-type="{{ $key }}" data-section="trips">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <!-- ПОП-АП ФИЛЬТРОВ -->
        <div id="tripFiltersModal" class="filters-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div class="filters-modal-content" style="background: #fff; border-radius: 20px; padding: 30px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto; position: relative; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                <button type="button" id="closeTripFilters" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; color: #999; padding: 5px; line-height: 1;">&times;</button>
                
                <h3 style="margin: 0 0 25px 0; font-size: 24px; font-weight: 700; color: #2B2B2B;">Фильтры поездок</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <!-- Цена от -->
                    <div>
                        <label style="font-weight: 500; font-size: 14px; color: #2B2B2B; display: block; margin-bottom: 8px;">Цена от, ₽</label>
                        <input type="number" id="tripPriceFrom" class="trip-filter-input" data-filter="trip_price_from" placeholder="0" value="{{ request('trip_price_from') }}" style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                    </div>

                    <!-- Цена до -->
                    <div>
                        <label style="font-weight: 500; font-size: 14px; color: #2B2B2B; display: block; margin-bottom: 8px;">Цена до, ₽</label>
                        <input type="number" id="tripPriceTo" class="trip-filter-input" data-filter="trip_price_to" placeholder="100 000" value="{{ request('trip_price_to') }}" style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <!-- Дата от -->
                    <div>
                        <label style="font-weight: 500; font-size: 14px; color: #2B2B2B; display: block; margin-bottom: 8px;">Дата от</label>
                        <input type="date" id="tripDateFrom" class="trip-filter-input" data-filter="trip_date_from" value="{{ request('trip_date_from') }}" style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                    </div>

                    <!-- Дата до -->
                    <div>
                        <label style="font-weight: 500; font-size: 14px; color: #2B2B2B; display: block; margin-bottom: 8px;">Дата до</label>
                        <input type="date" id="tripDateTo" class="trip-filter-input" data-filter="trip_date_to" value="{{ request('trip_date_to') }}" style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                    </div>
                </div>

                <!-- Гид -->
                <div style="margin-bottom: 25px;">
                    <label style="font-weight: 500; font-size: 14px; color: #2B2B2B; display: block; margin-bottom: 8px;">Гид</label>
                    <select id="tripGuide" class="trip-filter-select" data-filter="trip_guide" style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                        <option value="">Все гиды</option>
                        @foreach($guides as $guide)
                            <option value="{{ $guide->id }}" @selected(request('trip_guide') == $guide->id)>
                                {{ $guide->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Кнопки -->
                <div style="display: flex; gap: 10px;">
                    <button type="button" id="applyTripFilters" style="flex: 1; padding: 14px 20px; background: linear-gradient(135deg, #4A90D9 0%, #357ABD 100%); color: #fff; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(74, 144, 217, 0.3);">
                        Применить
                    </button>
                    <button type="button" id="resetTripFilters" style="flex: 1; padding: 14px 20px; background: #f5f5f5; color: #666; border: 1px solid #e0e0e0; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                        Сбросить
                    </button>
                </div>
            </div>
        </div>

        <!-- КАРТОЧКИ -->
        <div class="trips-list">
            @forelse($trips as $trip)
                <div class="trip-card" onclick="location.href='{{ route('trips.show', $trip->id) }}'" style="cursor: pointer;">
                    <div class="trip-card-image">
                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $trip->main_image) }}" alt="{{ $trip->title }}" onerror="this.src='{{ asset('img/empty.png') }}'; this.onerror=null;">
                    </div>
                    <div class="trip-card-content">
                        <h3 class="trip-card-title">{{ $trip->title }}</h3>
                        <div class="trip-card-details">
                            <div class="detail">
                                <span class="detail-icon">📅</span>
                                <span>{{ \Carbon\Carbon::parse($trip->event_date)->format('d.m.Y') }}</span>
                            </div>
                            <div class="detail">
                                <span class="detail-icon">⏱️</span>
                                <span>{{ $trip->duration_minutes }} мин</span>
                            </div>
                            <div class="detail">
                                <span class="detail-icon">👥</span>
                                <span>до {{ $trip->max_people }} чел</span>
                            </div>
                        </div>
                        <div class="trip-card-footer">
                            <div class="trip-card-price">
                                <span class="price-value">{{ $trip->price }} ₽</span>
                                <span class="price-label">с человека</span>
                            </div>
                            <button class="trip-card-btn">
                                Подробнее
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="empty-message">Поездок пока нет</p>
            @endforelse
        </div>

    </div>

    <!-- ПАГИНАЦИЯ -->
    @if ($trips->lastPage() > 1)
        <div class="pagination-wrapper">
            <ul class="pagination">
                {{-- ⬅ Назад --}}
                @if ($trips->onFirstPage())
                    <li class="disabled arrow">
                        <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="">
                    </li>
                @else
                    <li class="arrow">
                        <a href="{{ $trips->previousPageUrl() }}#trips">
                            <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="">
                        </a>
                    </li>
                @endif

                <!-- Цифры -->
                @for ($i = 1; $i <= $trips->lastPage(); $i++)
                    @if ($i == $trips->currentPage())
                        <li class="active">
                            <span>{{ $i }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $trips->url($i) }}#trips">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                <!-- Вперёд -->
                @if ($trips->hasMorePages())
                    <li class="arrow">
                        <a href="{{ $trips->nextPageUrl() }}#trips">
                            <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="" class="arrow-right">
                        </a>
                    </li>
                @else
                    <li class="disabled arrow">
                        <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="" class="arrow-right">
                    </li>
                @endif
            </ul>
        </div>
    @endif

</section>

<!-- ГИДЫ -->
<section class="guides">
    <div class="container">
        <h2>Наши гиды</h2>

        <div class="guides-slider" id="guidesSlider">
            @forelse($guides as $guide)
                <div class="guide-card {{ empty($guide->photo) ? 'guide-card-no-photo' : '' }}" 
                     @if(!empty($guide->photo)) style="background-image: url('{{ asset('storage/' . $guide->photo) }}')" @endif>
                    <div class="guide-info">
                        <p>{{ $guide->name }}</p>
                        @if(!empty($guide->bio))
                            <p>{{ $guide->bio }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="no-guides">Гиды пока не добавлены</p>
            @endforelse
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="faq container">
    <h2>Часто задаваемые вопросы</h2>
    
    <details>
        <summary>Нужен ли опыт управления гидроциклом?</summary>
        <p>Нет, опыт не требуется. Перед началом аренды наш инструктор проведёт подробный инструктаж по управлению гидроциклом и технике безопасности. Даже если вы впервые садитесь за руль, вы быстро освоитесь.</p>
    </details>
    
    <details>
        <summary>Какой график работы?</summary>
        <p>Мы работаем ежедневно с 10:00 до 22:00. В летний сезон возможно продление времени работы. Рекомендуем бронировать время заранее, особенно в выходные дни.</p>
    </details>
    
    <details>
        <summary>Что нужно иметь с собой?</summary>
        <p>Для аренды гидроцикла необходим документ, удостоверяющий личность (паспорт или водительские права). Также рекомендуем взять с собой сменную одежду, полотенце и солнцезащитные очки. Спасательные жилеты выдаются бесплатно.</p>
    </details>
    
    <details>
        <summary>Можно ли с детьми?</summary>
        <p>Да, у нас есть гидроциклы, на которых можно кататься с детьми. Ребёнок должен быть старше 5 лет и ростом не менее 110 см. Дети до 12 лет катаются только в сопровождении взрослого.</p>
    </details>
    
    <details>
        <summary>Как происходит бронирование?</summary>
        <p>Вы можете забронировать гидроцикл или тур прямо на сайте, выбрав удобное время. Также доступно бронирование по телефону. Для подтверждения брони требуется предоплата 20%.</p>
    </details>
    
    <details>
        <summary>Что если плохая погода?</summary>
        <p>В случае неблагоприятных погодных условий (сильный ветер, гроза) мы переносим бронирование на другое время или возвращаем полную стоимость. Решение о переносе принимается совместно с клиентом.</p>
    </details>
    
    <details>
        <summary>Есть ли скидки?</summary>
        <p>Да, мы предоставляем скидки группам от 4 человек, а также при бронировании на несколько часов. Действуют специальные цены для постоянных клиентов. Уточняйте актуальные акции у администратора.</p>
    </details>
    
    <details>
        <summary>Можно ли проложить свой маршрут?</summary>
        <p>Да, помимо готовых туров вы можете выбрать индивидуальный маршрут в пределах разрешённой акватории. Инструктор покажет безопасные зоны и интересные места для посещения.</p>
    </details>
</section>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('priceSlider');
    const priceValue = document.getElementById('priceValue');
    const cards = document.querySelectorAll('.catalog-items .item-card');

    // Получаем максимальную цену
    let maxPrice = 0;
    cards.forEach(card => {
        const priceText = card.querySelector('.item-card-details .detail span:last-child').textContent;
        const price = parseInt(priceText.replace(/\D/g, ''));
        if (price > maxPrice) maxPrice = price;
    });

    if (maxPrice > 0) {
        slider.max = Math.ceil(maxPrice / 100) * 100;
        slider.value = slider.max;
        priceValue.textContent = parseInt(slider.value).toLocaleString('ru-RU') + ' ₽';
    }

    slider.addEventListener('input', function() {
        const value = parseInt(this.value);
        priceValue.textContent = value.toLocaleString('ru-RU') + ' ₽';

        cards.forEach(card => {
            const priceText = card.querySelector('.item-card-details .detail span:last-child').textContent;
            const price = parseInt(priceText.replace(/\D/g, ''));
            card.style.display = price <= value ? 'block' : 'none';
        });
    });

    // AJAX-фильтрация (категории и сортировка)
    function loadItemsContent(params, scrollPosition) {
        fetch('{{ route("search.items") }}?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            document.querySelector('.catalog-items').innerHTML = html;
            window.history.pushState({path: '?' + params.toString() + '#catalog'}, '', '?' + params.toString() + '#catalog');
            window.scrollTo(0, scrollPosition);
            initFavoriteButtons();
            initSortSelects(); // Переинициализируем select-ы
        })
        .catch(error => console.error('Ошибка фильтрации:', error));
    }

    function loadTripsContent(params, scrollPosition) {
        fetch('{{ route("search.trips") }}?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            document.querySelector('.trips-list').innerHTML = html;
            window.history.pushState({path: '?' + params.toString() + '#trips'}, '', '?' + params.toString() + '#trips');
            window.scrollTo(0, scrollPosition);
            initSortSelects();
        })
        .catch(error => console.error('Ошибка фильтрации:', error));
    }

    // Инициализация select-ов сортировки
    function initSortSelects() {
        document.querySelectorAll('[data-ajax-filter]:not([data-activity-type])').forEach(select => {
            // Удаляем старые обработчики (клонированием)
            const newSelect = select.cloneNode(true);
            select.parentNode.replaceChild(newSelect, select);
            
            newSelect.addEventListener('change', function() {
                const section = this.dataset.section;
                const scrollPosition = document.getElementById(section).offsetTop - 100;

                const params = new URLSearchParams(window.location.search);

                if (section === 'catalog') {
                    if (this.value) {
                        params.set('sort', this.value);
                    } else {
                        params.delete('sort');
                    }
                    params.delete('page');
                    loadItemsContent(params, scrollPosition);
                } else if (section === 'trips') {
                    if (this.value) {
                        params.set('trip_sort', this.value);
                    } else {
                        params.delete('trip_sort');
                    }
                    params.delete('page');
                    loadTripsContent(params, scrollPosition);
                }
            });
        });
    }

    // Обработка кликов по категориям
    document.querySelectorAll('[data-ajax-filter][data-activity-type]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const section = this.dataset.section;
            const activityType = this.dataset.activityType;
            const scrollPosition = document.getElementById(section).offsetTop - 100;
            
            const params = new URLSearchParams(window.location.search);
            
            if (section === 'catalog') {
                if (activityType) {
                    params.set('activity_type', activityType);
                } else {
                    params.delete('activity_type');
                }
                params.delete('page');
                loadItemsContent(params, scrollPosition);
                
                // Обновляем активный класс
                document.querySelectorAll('#catalog [data-ajax-filter]').forEach(l => l.parentElement.classList.remove('active'));
                this.parentElement.classList.add('active');
            } else if (section === 'trips') {
                if (activityType) {
                    params.set('trip_activity_type', activityType);
                } else {
                    params.delete('trip_activity_type');
                }
                params.delete('page');
                loadTripsContent(params, scrollPosition);
                
                // Обновляем активный класс
                document.querySelectorAll('#trips [data-ajax-filter]').forEach(l => l.parentElement.classList.remove('active'));
                this.parentElement.classList.add('active');
            }
        });
    });

    // Инициализация при загрузке страницы
    initSortSelects();

    // Поиск по аренде (AJAX)
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const params = new URLSearchParams(window.location.search);
                if (this.value) {
                    params.set('search', this.value);
                } else {
                    params.delete('search');
                }
                params.delete('page');
                const scrollPosition = document.getElementById('catalog').offsetTop - 100;
                loadItemsContent(params, scrollPosition);
            }, 300);
        });
    }

    // Поиск по поездкам (AJAX)
    const tripSearchInput = document.getElementById('tripSearchInput');
    let tripSearchTimeout;
    if (tripSearchInput) {
        tripSearchInput.addEventListener('input', function() {
            clearTimeout(tripSearchTimeout);
            tripSearchTimeout = setTimeout(() => {
                const params = new URLSearchParams(window.location.search);
                if (this.value) {
                    params.set('trip_search', this.value);
                } else {
                    params.delete('trip_search');
                }
                params.delete('page');
                const scrollPosition = document.getElementById('trips').offsetTop - 100;
                loadTripsContent(params, scrollPosition);
            }, 300);
        });
    }

    // Фильтры для поездок (цена, дата, гид) - поп-ап
    const tripFiltersModal = document.getElementById('tripFiltersModal');
    const openTripFiltersBtn = document.getElementById('openTripFilters');
    const closeTripFiltersBtn = document.getElementById('closeTripFilters');
    const applyTripFiltersBtn = document.getElementById('applyTripFilters');
    const resetTripFiltersBtn = document.getElementById('resetTripFilters');

    // Открытие модального окна
    if (openTripFiltersBtn) {
        openTripFiltersBtn.addEventListener('click', function() {
            tripFiltersModal.style.display = 'flex';
        });
    }

    // Закрытие модального окна
    if (closeTripFiltersBtn) {
        closeTripFiltersBtn.addEventListener('click', function() {
            tripFiltersModal.style.display = 'none';
        });
    }

    // Закрытие по клику вне окна
    tripFiltersModal.addEventListener('click', function(e) {
        if (e.target === tripFiltersModal) {
            tripFiltersModal.style.display = 'none';
        }
    });

    // Применение фильтров
    if (applyTripFiltersBtn) {
        applyTripFiltersBtn.addEventListener('click', function() {
            const params = new URLSearchParams(window.location.search);
            
            const priceFrom = document.getElementById('tripPriceFrom')?.value;
            const priceTo = document.getElementById('tripPriceTo')?.value;
            const dateFrom = document.getElementById('tripDateFrom')?.value;
            const dateTo = document.getElementById('tripDateTo')?.value;
            const guide = document.getElementById('tripGuide')?.value;
            
            if (priceFrom) params.set('trip_price_from', priceFrom);
            else params.delete('trip_price_from');
            
            if (priceTo) params.set('trip_price_to', priceTo);
            else params.delete('trip_price_to');
            
            if (dateFrom) params.set('trip_date_from', dateFrom);
            else params.delete('trip_date_from');
            
            if (dateTo) params.set('trip_date_to', dateTo);
            else params.delete('trip_date_to');
            
            if (guide) params.set('trip_guide', guide);
            else params.delete('trip_guide');
            
            params.delete('page');
            const scrollPosition = document.getElementById('trips').offsetTop - 100;
            
            tripFiltersModal.style.display = 'none';
            loadTripsContent(params, scrollPosition);
        });
    }

    // Сброс фильтров
    if (resetTripFiltersBtn) {
        resetTripFiltersBtn.addEventListener('click', function() {
            document.getElementById('tripPriceFrom').value = '';
            document.getElementById('tripPriceTo').value = '';
            document.getElementById('tripDateFrom').value = '';
            document.getElementById('tripDateTo').value = '';
            document.getElementById('tripGuide').value = '';
            
            const params = new URLSearchParams(window.location.search);
            params.delete('trip_price_from');
            params.delete('trip_price_to');
            params.delete('trip_date_from');
            params.delete('trip_date_to');
            params.delete('trip_guide');
            params.delete('page');
            
            const scrollPosition = document.getElementById('trips').offsetTop - 100;
            tripFiltersModal.style.display = 'none';
            loadTripsContent(params, scrollPosition);
        });
    }

    // Инициализация кнопок избранного после AJAX-запроса
    function initFavoriteButtons() {
        @auth
            const favoriteBtns = document.querySelectorAll('.favorite-btn');
            favoriteBtns.forEach(btn => {
                const itemId = btn.dataset.itemId;
                fetch(`/favorites/${itemId}/check`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.is_favorite) {
                            btn.classList.add('active');
                        }
                    })
                    .catch(e => console.error('Ошибка загрузки состояния избранного:', e));
                
                btn.addEventListener('click', async function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    
                    const itemId = this.dataset.itemId;
                    
                    try {
                        const response = await fetch(`/favorites/${itemId}/toggle`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            if (data.is_favorite) {
                                this.classList.add('active');
                            } else {
                                this.classList.remove('active');
                            }
                        }
                    } catch (e) {
                        console.error('Ошибка обновления избранного:', e);
                    }
                });
            });
        @endauth
    }

    // Запускаем инициализацию избранного при загрузке страницы
    initFavoriteButtons();

    // Подсветка активных категорий при загрузке
    function updateActiveCategoryLinks() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Аренда
        const catalogActivityType = urlParams.get('activity_type');
        document.querySelectorAll('#catalog [data-ajax-filter][data-activity-type]').forEach(link => {
            const isActive = (catalogActivityType === null && link.dataset.activityType === '') || 
                             (catalogActivityType === link.dataset.activityType);
            link.parentElement.classList.toggle('active', isActive);
        });
        
        // Поездки
        const tripsActivityType = urlParams.get('trip_activity_type');
        document.querySelectorAll('#trips [data-ajax-filter][data-activity-type]').forEach(link => {
            const isActive = (tripsActivityType === null && link.dataset.activityType === '') || 
                             (tripsActivityType === link.dataset.activityType);
            link.parentElement.classList.toggle('active', isActive);
        });
    }
    
    updateActiveCategoryLinks();
});
</script>

<script>
// Drag-to-scroll for guides slider
(function() {
    const slider = document.getElementById('guidesSlider');
    if (!slider) return;

    let isDown = false;
    let startX;
    let scrollLeft;
    let velX = 0;
    let momentumID;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('dragging');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
        cancelMomentum();
    });

    slider.addEventListener('mouseleave', () => {
        if (isDown) beginMomentum();
    });

    slider.addEventListener('mouseup', () => {
        if (isDown) beginMomentum();
    });

    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 1.5;
        slider.scrollLeft = scrollLeft - walk;
        velX = slider.scrollLeft - (scrollLeft - walk);
    });

    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('dragging');
    });

    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('dragging');
    });

    function beginMomentum() {
        isDown = false;
        slider.classList.remove('dragging');
        cancelMomentum();
        momentumID = requestAnimationFrame(momentum);
    }

    function cancelMomentum() {
        cancelAnimationFrame(momentumID);
    }

    function momentum() {
        velX *= 0.92;
        if (Math.abs(velX) > 0.5) {
            momentumID = requestAnimationFrame(momentum);
            slider.scrollLeft += velX;
        }
    }

    slider.addEventListener('dragstart', (e) => e.preventDefault());
})();

// Функции для модального окна изображений
function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    
    modal.style.display = "block";
    modalImg.src = src;
    
    // Блокируем прокрутку страницы
    document.body.style.overflow = "hidden";
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = "none";
    
    // Разблокируем прокрутку страницы
    document.body.style.overflow = "auto";
}

// Закрытие модального окна по нажатию клавиши Escape
document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        closeImageModal();
    }
});
</script>

<!-- Модальное окно для просмотра изображений -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="modal-close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

@endsection