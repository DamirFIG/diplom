<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Booking;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function show($id)
    {
        $trip = Trip::with(['reviews.user' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        $reviews = $trip->reviews()->with('user')->orderBy('created_at', 'desc')->paginate(10);

        $canReview = auth()->check() && Booking::where('user_id', auth()->id())
            ->where('trip_id', $trip->id)
            ->where('status', 'completed')
            ->exists();

        return view('trips.show', compact('trip', 'reviews', 'canReview'));
    }

    public function book(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'people' => 'required|integer|min:1',
            'comment' => 'nullable|string|max:500',
        ]);

        $trip = Trip::findOrFail($request->trip_id);

        // Проверка максимального количества людей
        if ($request->people > ($trip->max_people ?? 10)) {
            return redirect()->back()
                ->with('error', 'Превышено максимальное количество участников: ' . ($trip->max_people ?? 10));
        }

        // Создаем бронирование
        Booking::create([
            'user_id' => auth()->id(),
            'trip_id' => $trip->id,
            'people' => $request->people,
            'comment' => $request->comment,
            'total_price' => $trip->price * $request->people,
            'status' => 'pending',
        ]);

        return redirect()->route('profile.index', ['tab' => 'bookings'])
            ->with('success', 'Бронирование успешно создано!');
    }
}
