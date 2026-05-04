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
