<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Booking;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function show($id)
    {
        $item = Item::with(['reviews.user' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        $reviews = $item->reviews()->with('user')->orderBy('created_at', 'desc')->paginate(10);

        $canReview = auth()->check() && Booking::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->whereHas('trip', function($q) use ($item) {
                $q->where('activity_type', $item->activity_type);
            })
            ->exists();

        return view('items.show', compact('item', 'reviews', 'canReview'));
    }
}
