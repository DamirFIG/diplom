@extends('layouts.admin')

@section('content')
@php
    $statusLabels = [
        'pending' => 'Ожидает',
        'confirmed' => 'Подтвержден',
        'completed' => 'Выполнен',
        'cancelled' => 'Отменен',
    ];
@endphp

<div class="admin-page">
    <div class="page-header">
        <h1>Заказы пользователей</h1>
    </div>

    <div class="orders-grid">
        @forelse($bookings as $booking)
            <div class="order-card">
                <div class="order-head">
                    <div class="order-title">
                        <span class="order-number">Заказ #{{ $booking->id }}</span>
                        <span class="order-user">{{ $booking->user->login ?? 'Пользователь не указан' }}</span>
                    </div>
                    <div class="order-object">{{ $booking->trip->title ?? ($booking->item->title ?? 'Объект не указан') }}</div>
                </div>

                <div class="order-meta">
                    <div class="order-meta-item">
                        <span class="order-meta-label">Дата</span>
                        <span class="order-meta-value">{{ $booking->booking_date ? $booking->booking_date->format('d.m.Y') : '—' }}</span>
                    </div>
                    <div class="order-meta-item">
                        <span class="order-meta-label">Время</span>
                        <span class="order-meta-value">{{ $booking->start_time && $booking->end_time ? $booking->start_time.'–'.$booking->end_time : '—' }}</span>
                    </div>
                    <div class="order-meta-item">
                        <span class="order-meta-label">Люди</span>
                        <span class="order-meta-value">{{ $booking->people ?? '—' }}</span>
                    </div>
                    <div class="order-meta-item">
                        <span class="order-meta-label">Сумма</span>
                        <span class="order-meta-value">{{ number_format($booking->total_price ?? 0, 0, '.', ' ') }} ₽</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="status-form">
                    @csrf
                    <label for="booking-status-{{ $booking->id }}">Статус заказа</label>
                    <select id="booking-status-{{ $booking->id }}" name="status">
                        @foreach($statusLabels as $key => $label)
                            <option value="{{ $key }}" @selected($booking->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Сохранить</button>
                </form>
            </div>
        @empty
            <div class="orders-empty">Заказов пока нет</div>
        @endforelse
    </div>

    <div class="pagination-container">{{ $bookings->links('vendor.pagination.bootstrap-5') }}</div>
</div>

<style>
.orders-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 18px;
}

.order-card {
    background: #fff;
    border: 1px solid #e5edf6;
    border-radius: 20px;
    padding: 26px 28px;
    box-shadow: 0 12px 32px rgba(27, 44, 68, 0.08);
}

.order-head {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    align-items: flex-start;
    margin-bottom: 22px;
}

.order-title {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.order-number {
    color: #1f2d3d;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.2;
}

.order-user {
    color: #697889;
    font-size: 14px;
    font-weight: 500;
}

.order-object {
    color: #377FC1;
    font-size: 15px;
    font-weight: 600;
    line-height: 1.35;
    text-align: right;
}

.order-meta {
    display: grid;
    grid-template-columns: repeat(4, minmax(150px, 1fr));
    gap: 12px;
    margin-bottom: 22px;
}

.order-meta-item {
    background: #f7fafe;
    border: 1px solid #edf3fa;
    border-radius: 14px;
    padding: 13px 14px;
}

.order-meta-label {
    display: block;
    color: #7d8b9a;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 5px;
}

.order-meta-value {
    color: #2c3e50;
    font-size: 15px;
    font-weight: 600;
}

.status-form {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
    padding-top: 18px;
    border-top: 1px solid #edf2f7;
}

.status-form label {
    color: #697889;
    font-size: 13px;
    font-weight: 600;
    margin-right: 2px;
}

.status-form select {
    min-width: 180px;
    min-height: 42px;
    padding: 9px 12px;
    border: 1px solid #d9e1ea;
    border-radius: 10px;
    background: #fff;
    color: #2c3e50;
    font-size: 14px;
}

.status-form button {
    min-height: 42px;
    padding: 10px 18px;
    border: none;
    border-radius: 10px;
    background: #4A90D9;
    color: #fff;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
}

.status-form button:hover {
    background: #357ABD;
}

.orders-empty {
    background: #fff;
    border: 1px solid #e5edf6;
    border-radius: 18px;
    padding: 28px;
    color: #697889;
    text-align: center;
}

@media (max-width: 900px) {
    .order-card {
        padding: 22px;
    }

    .order-head {
        flex-direction: column;
        gap: 14px;
    }

    .order-object {
        text-align: left;
    }

    .order-meta {
        grid-template-columns: repeat(2, minmax(150px, 1fr));
    }
}

@media (max-width: 560px) {
    .order-meta {
        grid-template-columns: 1fr;
    }

    .status-form label,
    .status-form select,
    .status-form button {
        width: 100%;
    }
}
</style>
@endsection
