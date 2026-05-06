<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ItemController extends Controller
{
    public function show($id)
    {
        $item = Item::with(['reviews.user' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        $reviews = $item->reviews()->with('user')->orderBy('created_at', 'desc')->paginate(10);

        $canReview = auth()->check() && Booking::where('user_id', auth()->id())
            ->where('item_id', $item->id)
            ->where('status', 'completed')
            ->exists();

        return view('items.show', compact('item', 'reviews', 'canReview'));
    }

    public function book(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'people' => 'required|integer|min:1',
            'comment' => 'nullable|string|max:500',
        ]);

        $start = Carbon::createFromFormat('H:i', $request->start_time);
        $end = Carbon::createFromFormat('H:i', $request->end_time);

        if ($end->lessThanOrEqualTo($start)) {
            return back()->with('error', 'Время окончания должно быть позже времени начала');
        }

        $hours = (int) ceil($start->diffInMinutes($end) / 60);
        $item = Item::findOrFail($request->item_id);
        $total = $item->price * $hours * $request->people;

        Booking::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'people' => $request->people,
            'hours' => $hours,
            'comment' => $request->comment,
            'total_price' => $total,
            'status' => 'pending',
        ]);

        return redirect()->route('profile.index', ['tab' => 'bookings'])
            ->with('success', 'Бронирование отправлено. Ожидайте подтверждения.');
    }
}
