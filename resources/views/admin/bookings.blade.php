@extends('layouts.admin')

@section('content')
<div class="admin-page">
    <div class="page-header">
        <h1>Заказы пользователей</h1>
    </div>

    <div class="bookings-table-wrap">
        <table class="bookings-table">
            <thead>
            <tr>
                <th>ID</th><th>Пользователь</th><th>Объект</th><th>Дата</th><th>Время</th><th>Люди</th><th>Сумма</th><th>Статус</th><th>Изменить</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->user->login ?? '—' }}</td>
                    <td>{{ $booking->trip->title ?? ($booking->item->title ?? '—') }}</td>
                    <td>{{ $booking->booking_date ? $booking->booking_date->format('d.m.Y') : '—' }}</td>
                    <td>{{ $booking->start_time && $booking->end_time ? $booking->start_time.'-'.$booking->end_time : '—' }}</td>
                    <td>{{ $booking->people ?? '—' }}</td>
                    <td>{{ number_format($booking->total_price ?? 0, 0, '.', ' ') }} ₽</td>
                    <td><span class="status-badge status-{{ $booking->status }}">{{ $booking->status }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="status-form">
                            @csrf
                            <select name="status">
                                @foreach(['pending' => 'Ожидает', 'confirmed' => 'Подтвержден', 'completed' => 'Выполнен', 'cancelled' => 'Отменен'] as $key => $label)
                                    <option value="{{ $key }}" @selected($booking->status === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="submit">Сохранить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-container">{{ $bookings->links('vendor.pagination.bootstrap-5') }}</div>
</div>

<style>
.bookings-table-wrap{background:#fff;border-radius:14px;padding:16px;box-shadow:0 4px 14px rgba(0,0,0,.08);overflow:auto}
.bookings-table{width:100%;border-collapse:collapse;font-size:14px}
.bookings-table th,.bookings-table td{padding:12px 10px;border-bottom:1px solid #eef1f5;text-align:left;white-space:nowrap}
.bookings-table thead th{color:#566070;font-weight:700;background:#f8fbff}
.status-badge{padding:6px 10px;border-radius:14px;color:#fff;font-size:12px;font-weight:600;text-transform:capitalize}
.status-pending{background:#f0ad4e}.status-confirmed{background:#28a745}.status-completed{background:#17a2b8}.status-cancelled{background:#dc3545}
.status-form{display:flex;gap:8px;align-items:center}
.status-form select{padding:8px;border:1px solid #d9e1ea;border-radius:8px}
.status-form button{padding:8px 12px;border:none;border-radius:8px;background:#4A90D9;color:#fff;cursor:pointer}
.status-form button:hover{background:#357ABD}
</style>
@endsection
