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
