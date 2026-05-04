@extends('layouts.admin')

@section('content')
<div class="admin-page">
    <div class="page-header">
        <h1>Редактировать поездку</h1>
        <a href="{{ route('admin.trips') }}" class="btn-secondary">← Назад</a>
    </div>

    <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST" enctype="multipart/form-data" class="admin-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="route">

        <div class="form-group">
            <label for="title">Название поездки *</label>
            <input type="text" name="title" id="title" value="{{ old('title', $trip->title) }}" required placeholder="Например: Водная прогулка по Неве">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="activity_type">Тип активности *</label>
                <select name="activity_type" id="activity_type" required>
                    <option value="">Выберите тип</option>
                    <option value="гидроцикл" {{ old('activity_type', $trip->activity_type) === 'гидроцикл' ? 'selected' : '' }}>Гидроцикл</option>
                    <option value="банан" {{ old('activity_type', $trip->activity_type) === 'банан' ? 'selected' : '' }}>Банан</option>
                    <option value="флайборд" {{ old('activity_type', $trip->activity_type) === 'флайборд' ? 'selected' : '' }}>Флайборд</option>
                    <option value="сапборд" {{ old('activity_type', $trip->activity_type) === 'сапборд' ? 'selected' : '' }}>Сапборд</option>
                    <option value="катамаран" {{ old('activity_type', $trip->activity_type) === 'катамаран' ? 'selected' : '' }}>Катамаран</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Цена (₽) *</label>
                <input type="number" name="price" id="price" value="{{ old('price', $trip->price) }}" required min="0" placeholder="5000">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="event_date">Дата проведения</label>
                <input type="date" name="event_date" id="event_date" value="{{ old('event_date', $trip->event_date?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label for="guide_id">Гид</label>
                <select name="guide_id" id="guide_id">
                    <option value="">Без гида</option>
                    @foreach($guides as $guide)
                        <option value="{{ $guide->id }}" {{ old('guide_id', $trip->guide_id) == $guide->id ? 'selected' : '' }}>
                            {{ $guide->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="max_people">Макс. человек</label>
                <input type="number" name="max_people" id="max_people" value="{{ old('max_people', $trip->max_people) }}" min="1" placeholder="2">
            </div>

            <div class="form-group">
                <label for="duration_minutes">Длительность (мин)</label>
                <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', $trip->duration_minutes) }}" min="1" placeholder="60">
            </div>

            <div class="form-group">
                <label for="min_age">Мин. возраст</label>
                <input type="number" name="min_age" id="min_age" value="{{ old('min_age', $trip->min_age) }}" min="1" placeholder="16">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea name="description" id="description" rows="4" placeholder="Описание поездки...">{{ old('description', $trip->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Текущие фотографии</label>
            <div class="current-images">
                @if($trip->gallery && count($trip->gallery) > 0)
                    @foreach($trip->gallery as $image)
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

        <!-- Секция маршрута -->
        <div class="route-section">
            <h3>🗺️ Маршрут на карте</h3>

            <div class="form-group">
                <label>Точки маршрута</label>
                <div class="route-points-list" id="route-points-list">
                    @if($trip->route && $trip->route->points)
                        @foreach($trip->route->points->sortBy('sort_order') as $index => $point)
                            <div class="route-point-item {{ $point->type }}" data-index="{{ $index }}" data-type="{{ $point->type }}">
                                <div class="point-header">
                                    <span class="point-badge badge-{{ $point->type }}">
                                        @if($point->type === 'start') 🚩
                                        @elseif($point->type === 'stop') 📍
                                        @elseif($point->type === 'turn') 🔄
                                        @elseif($point->type === 'end') 🏁
                                        @endif
                                        {{ $point->type === 'start' ? 'Старт' : ($point->type === 'stop' ? 'Остановка' : ($point->type === 'turn' ? 'Точка поворота' : 'Финиш')) }}
                                    </span>
                                    <button type="button" class="btn-remove-point" onclick="removePoint(this, '{{ $point->type }}')">✕</button>
                                </div>
                                <div class="point-body">
                                    @if($point->type !== 'turn')
                                        <div class="point-row">
                                            <input type="text" name="route_points[{{ $index }}][title]" placeholder="Название точки" value="{{ old('route_points.'.$index.'.title', $point->title) }}" required>
                                            <input type="text" name="route_points[{{ $index }}][lat]" class="point-lat" placeholder="Широта" value="{{ old('route_points.'.$index.'.lat', $point->lat) }}" readonly required>
                                            <input type="text" name="route_points[{{ $index }}][lng]" class="point-lng" placeholder="Долгота" value="{{ old('route_points.'.$index.'.lng', $point->lng) }}" readonly required>
                                        </div>
                                        <div class="point-row">
                                            <input type="text" name="route_points[{{ $index }}][description]" placeholder="Описание" value="{{ old('route_points.'.$index.'.description', $point->description) }}">
                                            @if($point->image)
                                                <div class="current-point-image">
                                                    <img loading="lazy" decoding="async" src="{{ asset('storage/'.$point->image) }}" alt="Фото точки">
                                                    <input type="hidden" name="route_points[{{ $index }}][existing_image]" value="{{ $point->image }}">
                                                </div>
                                            @endif
                                            <input type="file" name="route_points[{{ $index }}][image_file]" accept="image/*">
                                        </div>
                                    @endif
                                    <input type="hidden" name="route_points[{{ $index }}][type]" value="{{ $point->type }}">
                                    <input type="hidden" name="route_points[{{ $index }}][is_visible]" value="{{ $point->is_visible ? '1' : '0' }}">
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="route-point-item start" data-index="0" data-type="start">
                            <div class="point-header">
                                <span class="point-badge badge-start">🚩 Старт</span>
                                <button type="button" class="btn-remove-point" onclick="removePoint(this, 'start')">✕</button>
                            </div>
                            <div class="point-body">
                                <div class="point-row">
                                    <input type="text" name="route_points[0][title]" placeholder="Название точки" required>
                                    <input type="text" name="route_points[0][lat]" class="point-lat" placeholder="Широта" readonly required>
                                    <input type="text" name="route_points[0][lng]" class="point-lng" placeholder="Долгота" readonly required>
                                </div>
                                <div class="point-row">
                                    <input type="text" name="route_points[0][description]" placeholder="Описание">
                                    <input type="file" name="route_points[0][image_file]" accept="image/*">
                                </div>
                                <input type="hidden" name="route_points[0][type]" value="start">
                                <input type="hidden" name="route_points[0][is_visible]" value="1">
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="point-types">
                    <button type="button" class="btn-add-point btn-stop" onclick="addPoint('stop')">📍 Добавить остановку</button>
                    <button type="button" class="btn-add-point btn-turn" onclick="addPoint('turn')">🔄 Добавить точку поворота</button>
                    <button type="button" class="btn-add-point btn-end" onclick="addPoint('end')">🏁 Добавить финиш</button>
                </div>
            </div>

            <div class="form-group">
                <label>📍 Кликните на карту, чтобы добавить координаты в последнюю выбранную точку</label>
                <div id="map" style="height: 500px; border-radius: 10px;"></div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Сохранить изменения</button>
            <a href="{{ route('admin.trips') }}" class="btn-cancel">Отмена</a>
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

/* Стили для маршрута */
.route-section {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 12px;
    margin: 30px 0;
    border: 1px solid #e0e0e0;
}

.route-section h3 {
    margin-bottom: 20px;
    color: #2c3e50;
}

.route-points-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 20px;
}

.route-point-item {
    background: #fff;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    overflow: hidden;
}

.route-point-item.start {
    border-color: #17a2b8;
}

.route-point-item.stop {
    border-color: #28a745;
}

.route-point-item.turn {
    border-color: #6c757d;
}

.route-point-item.end {
    border-color: #dc3545;
}

.point-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.point-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-start {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
}

.badge-stop {
    background: linear-gradient(135deg, #28a745, #218838);
    color: white;
}

.badge-turn {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
}

.badge-end {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.btn-remove-point {
    background: #dc3545;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-remove-point:hover {
    background: #c82333;
}

.point-body {
    padding: 15px;
}

.point-row {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.point-row:last-child {
    margin-bottom: 0;
}

.point-row input[type="text"] {
    flex: 1;
    min-width: 120px;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.point-row input[type="text"]:focus {
    outline: none;
    border-color: #3498db;
}

.point-row input[type="text"][readonly] {
    background: #f0f0f0;
    cursor: not-allowed;
    width: 100px;
    flex: none;
}

.point-row input[type="file"] {
    flex: 1;
    min-width: 150px;
    padding: 8px;
    font-size: 14px;
}

.current-point-image img {
    width: 100px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
}

.point-types {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 15px;
}

.btn-add-point {
    flex: 1;
    min-width: 180px;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-add-point.btn-stop {
    background: linear-gradient(135deg, #28a745, #218838);
}

.btn-add-point.btn-stop:hover {
    background: linear-gradient(135deg, #218838, #1e7e34);
    transform: translateY(-2px);
}

.btn-add-point.btn-turn {
    background: linear-gradient(135deg, #6c757d, #5a6268);
}

.btn-add-point.btn-turn:hover {
    background: linear-gradient(135deg, #5a6268, #495057);
    transform: translateY(-2px);
}

.btn-add-point.btn-end {
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.btn-add-point.btn-end:hover {
    background: linear-gradient(135deg, #c82333, #bd2130);
    transform: translateY(-2px);
}

#map {
    margin-top: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
}
</style>

<script>
let map;
let pointIndex = {{ $trip->route && $trip->route->points ? $trip->route->points->count() : 1 }};
let selectedPoint = null;
let markers = {};

// Инициализация карты
function initMap() {
    map = L.map('map', {
        zoomControl: true,
        attributionControl: false
    }).setView([59.9343, 30.3351], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '',
        noWrap: true
    }).addTo(map);
    
    // Клик по карте
    map.on('click', function(e) {
        if (!selectedPoint) {
            alert('Сначала выберите точку (кликните на неё)');
            return;
        }
        
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);
        
        // Заполняем поля выбранной точки
        const latInput = selectedPoint.querySelector('.point-lat');
        const lngInput = selectedPoint.querySelector('.point-lng');
        
        if (latInput && lngInput) {
            latInput.value = lat;
            lngInput.value = lng;
        }
        
        // Удаляем старый маркер для этой точки
        const pointIndex = selectedPoint.dataset.index;
        if (markers[pointIndex]) {
            map.removeLayer(markers[pointIndex]);
        }
        
        // В админке показываем маркер и для точки поворота
        const icon = getPointIcon(selectedPoint.dataset.type);
        markers[pointIndex] = L.marker([lat, lng], { icon }).addTo(map);
    });
}

// Получение иконки для типа точки
function getPointIcon(type) {
    const config = {
        'start': { color: '#17a2b8', icon: '🚩' },
        'stop': { color: '#28a745', icon: '📍' },
        'turn': { color: '#6c757d', icon: '🔄' },
        'end': { color: '#dc3545', icon: '🏁' }
    };
    
    const cfg = config[type] || config['stop'];
    
    return L.divIcon({
        className: 'custom-marker',
        html: `<div style="
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
        ">${cfg.icon}</div>`,
        iconSize: [35, 35],
        iconAnchor: [17, 17],
    });
}

// Добавление точки
function addPoint(type) {
    // Проверка: нельзя добавить две точки финиша
    if (type === 'end') {
        const endPoints = document.querySelectorAll('.route-point-item.end');
        if (endPoints.length > 0) {
            alert('Точка финиша может быть только одна!');
            return;
        }
    }
    
    pointIndex++;
    
    const container = document.getElementById('route-points-list');
    const newPoint = document.createElement('div');
    newPoint.className = `route-point-item ${type}`;
    newPoint.dataset.index = pointIndex;
    newPoint.dataset.type = type;
    
    const typeLabels = {
        'start': '🚩 Старт',
        'stop': '📍 Остановка',
        'turn': '🔄 Точка поворота',
        'end': '🏁 Финиш'
    };
    
    // Для точки поворота не показываем поля названия, описания и фото
    const isTurn = type === 'turn';
    const titleField = isTurn ? '' : `<input type="text" name="route_points[${pointIndex}][title]" placeholder="Название точки" required>`;
    const extraFields = isTurn ? '' : `
        <div class="point-row">
            <input type="text" name="route_points[${pointIndex}][description]" placeholder="Описание">
            <input type="file" name="route_points[${pointIndex}][image_file]" accept="image/*">
        </div>
    `;
    
    const isVisible = isTurn ? '0' : '1';
    const latLngFields = isTurn ? `
        <input type="hidden" name="route_points[${pointIndex}][lat]" class="point-lat hidden-field" required>
        <input type="hidden" name="route_points[${pointIndex}][lng]" class="point-lng hidden-field" required>
    ` : `
        <input type="text" name="route_points[${pointIndex}][lat]" class="point-lat" placeholder="Широта" readonly required>
        <input type="text" name="route_points[${pointIndex}][lng]" class="point-lng" placeholder="Долгота" readonly required>
    `;
    
    newPoint.innerHTML = `
        <div class="point-header">
            <span class="point-badge badge-${type}">${typeLabels[type]}</span>
            <button type="button" class="btn-remove-point" onclick="removePoint(this, '${type}')">✕</button>
        </div>
        <div class="point-body">
            <div class="point-row">
                ${titleField}
                ${latLngFields}
            </div>
            ${extraFields}
            <input type="hidden" name="route_points[${pointIndex}][type]" value="${type}">
            <input type="hidden" name="route_points[${pointIndex}][is_visible]" value="${isVisible}">
        </div>
    `;
    
    // Добавляем клик для выделения точки
    newPoint.addEventListener('click', function(e) {
        if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON') {
            selectPoint(newPoint);
        }
    });
    
    container.appendChild(newPoint);
    selectPoint(newPoint);
}

// Удаление точки
function removePoint(button, type) {
    if (type === 'start') {
        alert('Нельзя удалить точку старта!');
        return;
    }
    
    const point = button.closest('.route-point-item');
    const pointIndex = point.dataset.index;
    
    // Удаляем маркер с карты
    if (markers[pointIndex]) {
        map.removeLayer(markers[pointIndex]);
        delete markers[pointIndex];
    }
    
    point.remove();
    
    if (selectedPoint === point) {
        selectedPoint = null;
    }
}

// Выделение точки
function selectPoint(point) {
    // Снимаем выделение со всех точек
    document.querySelectorAll('.route-point-item').forEach(p => {
        p.style.boxShadow = 'none';
        p.style.borderColor = '';
    });
    
    // Выделяем выбранную
    point.style.boxShadow = '0 0 0 3px rgba(52, 152, 219, 0.3)';
    selectedPoint = point;
}

// Инициализация после загрузки страницы
document.addEventListener('DOMContentLoaded', function() {
    // Выделяем первую точку (старт) или последнюю
    const firstPoint = document.querySelector('.route-point-item.start') || 
                       document.querySelector('.route-point-item:last-child');
    if (firstPoint) {
        selectPoint(firstPoint);
    }
});
</script>

@push('scripts')
    <script>
    (function () {
        function bootMap() {
            if (typeof window.initMap === 'function') {
                window.initMap();
            }
        }

        function loadLeafletAndInit() {
            if (window.L) {
                bootMap();
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.onload = bootMap;
            script.onerror = function () {
                const fallback = document.createElement('script');
                fallback.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js';
                fallback.onload = bootMap;
                fallback.onerror = function () {
                    const mapEl = document.getElementById('map');
                    if (mapEl) {
                        mapEl.innerHTML = '<div style="padding:16px;color:#c0392b;">Не удалось загрузить скрипт карты (Leaflet). Проверьте доступ к CDN.</div>';
                    }
                };
                document.body.appendChild(fallback);
            };

            document.body.appendChild(script);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', loadLeafletAndInit);
        } else {
            loadLeafletAndInit();
        }
    })();
    </script>
@endpush

@endsection
