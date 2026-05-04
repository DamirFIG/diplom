@extends('layouts.admin')

@section('content')
<div class="admin-page">
    <div class="page-header">
        <h1>Заказы пользователей</h1>
    </div>

    <div class="admin-table">
        <table>
            <thead>
            <tr>
                <th>ID</th><th>Пользователь</th><th>Поездка</th><th>Дата</th><th>Сумма</th><th>Статус</th><th>Изменить</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->user->login ?? '—' }}</td>
                    <td>{{ $booking->trip->title ?? '—' }}</td>
                    <td>{{ $booking->booking_date ? $booking->booking_date->format('d.m.Y') : '—' }}</td>
                    <td>{{ number_format($booking->total_price ?? 0, 0, '.', ' ') }} ₽</td>
                    <td>{{ $booking->status }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}">
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

    {{ $bookings->links('vendor.pagination.bootstrap-5') }}
</div>
@endsection
