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
                    <div><strong>#{{ $booking->id }}</strong> · {{ $booking->user->login ?? '—' }}</div>
                    <div class="order-head-right">
                        <span class="status-badge status-{{ $booking->status }}">{{ $statusLabels[$booking->status] ?? $booking->status }}</span>
                        <div class="order-object">{{ $booking->trip->title ?? ($booking->item->title ?? '—') }}</div>
                    </div>
                </div>
                <div class="order-meta">
                    <span>Дата: {{ $booking->booking_date ? $booking->booking_date->format('d.m.Y') : '—' }}</span>
                    <span>Время: {{ $booking->start_time && $booking->end_time ? $booking->start_time.'-'.$booking->end_time : '—' }}</span>
                    <span>Люди: {{ $booking->people ?? '—' }}</span>
                    <span>Сумма: {{ number_format($booking->total_price ?? 0, 0, '.', ' ') }} ₽</span>
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
