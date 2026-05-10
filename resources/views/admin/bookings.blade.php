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
        @foreach($bookings as $booking)
            <div class="order-card">
                <div class="order-head">
                    <div class="order-title">
                        <span class="order-number">Заказ #{{ $booking->id }}</span>
                        <span class="order-user">{{ $booking->user->login ?? 'Пользователь не указан' }}</span>
                    </div>
                    <div class="order-head-right">
                        <span class="status-badge status-{{ $booking->status }}">{{ $statusLabels[$booking->status] ?? $booking->status }}</span>
                        <div class="order-object">{{ $booking->trip->title ?? ($booking->item->title ?? 'Объект не указан') }}</div>
                    <div><strong>#{{ $booking->id }}</strong> · {{ $booking->user->login ?? '—' }}</div>
                    <div class="order-head-right">
                        <span class="status-badge status-{{ $booking->status }}">{{ $statusLabels[$booking->status] ?? $booking->status }}</span>
                        <div class="order-object">{{ $booking->trip->title ?? ($booking->item->title ?? '—') }}</div>
                    </div>
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
                    <select name="status">
                        @foreach($statusLabels as $key => $label)
                            <option value="{{ $key }}" @selected($booking->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Сохранить</button>
                </form>
            </div>
        @endforeach
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

.order-head-right {
    display: flex;
    align-items: center;
    gap: 12px;
    justify-content: flex-end;
    flex-wrap: wrap;
    text-align: right;
}

.order-object {
    color: #377FC1;
    font-size: 15px;
    font-weight: 600;
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

.status-badge {
    display: inline-flex;
    align-items: center;
    min-height: 28px;
    padding: 7px 12px;
    border-radius: 999px;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
    text-transform: none;
    white-space: nowrap;
}

.status-pending { background: #f0ad4e; }
.status-confirmed { background: #28a745; }
.status-completed { background: #17a2b8; }
.status-cancelled { background: #dc3545; }

.status-form {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
    padding-top: 18px;
    border-top: 1px solid #edf2f7;
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

@media (max-width: 900px) {
    .order-card {
        padding: 22px;
    }

    .order-head {
        flex-direction: column;
        gap: 14px;
    }

    .order-head-right {
        justify-content: flex-start;
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

    .status-form select,
    .status-form button {
        width: 100%;
    }
}
</style>


.orders-grid{display:grid;grid-template-columns:1fr;gap:14px}
.order-card{background:#fff;border:1px solid #e6ecf2;border-radius:14px;padding:16px;box-shadow:0 4px 14px rgba(0,0,0,.06)}
.order-head{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:8px;font-weight:600;color:#2b2b2b}
.order-head-right{display:flex;align-items:center;gap:10px;justify-content:flex-end;flex-wrap:wrap;text-align:right}
.order-object{color:#4A90D9}
.order-meta{display:grid;grid-template-columns:repeat(2,minmax(180px,1fr));gap:8px;color:#526173;font-size:14px;margin-bottom:12px}
.status-badge{display:inline-flex;align-items:center;padding:6px 10px;border-radius:14px;color:#fff;font-size:12px;font-weight:600;line-height:1;text-transform:none;white-space:nowrap}
.status-pending{background:#f0ad4e}.status-confirmed{background:#28a745}.status-completed{background:#17a2b8}.status-cancelled{background:#dc3545}
.status-form{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.status-form select{padding:8px 10px;border:1px solid #d9e1ea;border-radius:8px;background:#fff}
.status-form button{padding:8px 12px;border:none;border-radius:8px;background:#4A90D9;color:#fff;cursor:pointer}
.status-form button:hover{background:#357ABD}
</style>

@endsection
